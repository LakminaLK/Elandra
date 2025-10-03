<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class CustomPasswordResetController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password-custom');
    }

    /**
     * Send password reset email
     */
    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'This email address is not registered with us.'
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'No account found with this email address.');
        }

        // Generate reset token
        $token = Str::random(60);
        
        // Store reset data in session (you can also use database table)
        Session::put('password_reset_data', [
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(15) // 15 minute expiry
        ]);

        try {
            // Send reset email
            Mail::send('emails.password-reset', [
                'user' => $user,
                'token' => $token,
                'resetUrl' => route('password.reset.show', ['token' => $token])
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Password Reset - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('app.name'));
            });

            return back()->with('success', 'Password reset email sent successfully! Please check your email and click the reset link to continue.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reset email. Please try again.');
        }
    }

    /**
     * Show password reset form
     */
    public function showResetForm($token)
    {
        $resetData = Session::get('password_reset_data');
        
        if (!$resetData || $resetData['token'] !== $token || now()->gt($resetData['expires_at'])) {
            return redirect()->route('password.request')
                           ->with('error', 'Invalid or expired reset token. Please request a new one.');
        }

        return view('auth.reset-password-custom', [
            'token' => $token,
            'email' => $resetData['email']
        ]);
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $resetData = Session::get('password_reset_data');
        
        if (!$resetData || $resetData['token'] !== $request->token || now()->gt($resetData['expires_at'])) {
            return redirect()->route('password.request')
                           ->with('error', 'Invalid or expired reset token. Please request a new one.');
        }

        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Check if new password is same as current password
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'New password cannot be the same as your current password.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear reset data
        Session::forget('password_reset_data');

        // Send password changed notification
        try {
            Mail::send('emails.password-changed', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Password Changed Successfully - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('app.name'));
            });
        } catch (\Exception $e) {
            // Email sending failed but password was reset successfully
        }

        return redirect()->route('login')
                       ->with('success', 'Password has been reset successfully! Please login with your new password.');
    }
}
