<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $admin = Auth::guard('admin')->user();
            
            // Check if admin is active
            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Your admin account has been deactivated. Please contact the system administrator.'
                ])->withInput();
            }

            // Update last login
            $admin->update(['last_login_at' => now()]);

            $request->session()->regenerate();

            Log::info('Admin logged in successfully', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $admin->name . '!');
        }

        Log::warning('Failed admin login attempt', [
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin) {
            Log::info('Admin logged out', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email
            ]);
        }

        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Send password reset email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if admin exists
        $admin = \App\Models\Admin::where('email', $request->email)->first();
        
        if (!$admin) {
            return back()->withErrors([
                'email' => 'We could not find an admin with that email address.'
            ]);
        }

        // Generate reset token
        $token = \Illuminate\Support\Str::random(60);
        
        // Store token in database
        \DB::table('admin_password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => \Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send email
        try {
            \Log::info('Attempting to send password reset email to: ' . $request->email);
            
            \Mail::to($request->email)->send(new \App\Mail\AdminPasswordReset($token, $request->email));
            
            \Log::info('Password reset email sent successfully to: ' . $request->email);
            
            return back()->with('status', 'We have emailed your password reset link! Please check your inbox and spam folder.');
        } catch (\Swift_TransportException $e) {
            \Log::error('SMTP Transport Error: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => 'Email server configuration issue. Please contact the administrator.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Password reset email failed: ' . $e->getMessage());
            \Log::error('Exception type: ' . get_class($e));
            
            // More specific error message based on common issues
            $errorMessage = 'Failed to send password reset email. ';
            if (str_contains($e->getMessage(), 'Username and Password not accepted')) {
                $errorMessage .= 'Email authentication failed. Please contact the administrator.';
            } elseif (str_contains($e->getMessage(), 'Connection timeout')) {
                $errorMessage .= 'Email server connection timeout. Please try again later.';
            } else {
                $errorMessage .= 'Please try again later or contact support.';
            }
            
            return back()->withErrors([
                'email' => $errorMessage
            ]);
        }
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Find the reset record
        $resetRecord = \DB::table('admin_password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors([
                'email' => 'Invalid reset token.'
            ]);
        }

        // Check if token matches
        if (!\Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors([
                'token' => 'Invalid reset token.'
            ]);
        }

        // Check if token is not expired (24 hours)
        if (now()->diffInHours($resetRecord->created_at) > 24) {
            // Delete expired token
            \DB::table('admin_password_reset_tokens')
                ->where('email', $request->email)
                ->delete();
                
            return back()->withErrors([
                'token' => 'Reset token has expired. Please request a new one.'
            ]);
        }

        // Find admin and update password
        $admin = \App\Models\Admin::where('email', $request->email)->first();
        
        if (!$admin) {
            return back()->withErrors([
                'email' => 'Admin not found.'
            ]);
        }

        // Update password
        $admin->update([
            'password' => \Hash::make($request->password)
        ]);

        // Delete the reset token
        \DB::table('admin_password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Log the admin in
        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Your password has been reset successfully!');
    }
}
