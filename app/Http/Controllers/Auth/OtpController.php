<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);
        
        // Store registration data and OTP in session
        Session::put('registration_data', [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);
        
        Session::put('pending_email', $request->email);
        Session::put('showOtpModal', true);

        try {
            // Send OTP email
            Mail::send('emails.otp-verification', ['otpCode' => $otp], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Email Verification - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('app.name'));
            });

            return redirect()->back()->with('success', 'OTP sent to your email address');
        } catch (\Exception $e) {
            Session::forget(['registration_data', 'pending_email', 'showOtpModal']);
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'email' => 'required|email',
        ]);

        $registrationData = Session::get('registration_data');
        
        if (!$registrationData || $registrationData['email'] !== $request->email) {
            return redirect()->route('register')->with('error', 'Invalid session. Please try again.');
        }

        if (now()->gt($registrationData['otp_expires_at'])) {
            Session::forget(['registration_data', 'pending_email', 'showOtpModal']);
            return redirect()->route('register')->with('error', 'OTP has expired. Please register again.');
        }

        if ((int)$request->otp !== (int)$registrationData['otp']) {
            return redirect()->back()
                ->with('showOtpModal', true)
                ->with('otp_error', 'Invalid OTP. Please try again.');
        }

        try {
            // Create user account
            $user = User::create([
                'name' => $registrationData['name'],
                'email' => $registrationData['email'],
                'mobile' => $registrationData['mobile'],
                'password' => $registrationData['password'],
                'email_verified_at' => now(),
            ]);

            // Send welcome email
            $user->notify(new \App\Notifications\WelcomeEmail());

            // Clear session data
            Session::forget(['registration_data', 'pending_email', 'showOtpModal']);

            // Redirect to login with success message
            return redirect()->route('login')->with([
                'success' => 'Welcome to Elandra! Your account has been created successfully.',
                'email_sent' => 'A welcome email has been sent to ' . $user->email . ' with important information about your account.',
                'registered_email' => $user->email
            ]);
        } catch (\Exception $e) {
            Session::forget(['registration_data', 'pending_email', 'showOtpModal']);
            return redirect()->route('register')->with('error', 'Failed to create account. Please try again.');
        }
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $registrationData = Session::get('registration_data');
        
        if (!$registrationData || $registrationData['email'] !== $request->email) {
            return redirect()->route('register')->with('error', 'Invalid session. Please try again.');
        }

        // Generate new OTP
        $otp = random_int(100000, 999999);
        
        // Update registration data with new OTP
        $registrationData['otp'] = $otp;
        $registrationData['otp_expires_at'] = now()->addMinutes(10);
        Session::put('registration_data', $registrationData);
        Session::put('otp_last_resend', time());

        try {
            // Send new OTP email
            Mail::send('emails.otp-verification', ['otpCode' => $otp], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Email Verification - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('app.name'));
            });

            return redirect()->back()
                ->with('showOtpModal', true)
                ->with('success', 'New OTP sent to your email address');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('showOtpModal', true)
                ->with('otp_error', 'Failed to send OTP. Please try again.');
        }
    }
}
