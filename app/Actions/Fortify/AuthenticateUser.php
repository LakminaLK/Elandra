<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserLoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class AuthenticateUser
{
    /**
     * Authenticate a user with lockout protection
     */
    public function __invoke(Request $request)
    {
        $email = $request->input(Fortify::username());
        $password = $request->input('password');
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Check for existing login attempts
        $loginAttempt = UserLoginAttempt::getCurrentAttempt($email, $ipAddress);
        
        // Check if account is currently locked
        if ($loginAttempt && $loginAttempt->isLocked()) {
            $remainingTime = $loginAttempt->getRemainingLockoutFormatted();
            
            Log::warning('User login attempt on locked account', [
                'email' => $email,
                'ip_address' => $ipAddress,
                'remaining_lockout' => $remainingTime,
                'user_agent' => $userAgent
            ]);
            
            throw ValidationException::withMessages([
                Fortify::username() => [
                    "Your account is temporarily locked. Try again in {$remainingTime}."
                ],
            ])->status(429);
        }

        // Find user by email
        $user = User::where(Fortify::username(), $email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($password, $user->password)) {
            // Record failed login attempt
            $attempt = UserLoginAttempt::recordFailedAttempt($email, $ipAddress, $userAgent);
            
            Log::warning('Failed user login attempt', [
                'email' => $email,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'attempts_count' => $attempt->attempts_count,
                'is_locked' => $attempt->isLocked()
            ]);

            // Prepare error message based on attempt count
            $errorMessage = 'These credentials do not match our records.';
            $remainingAttempts = 5 - $attempt->attempts_count;
            
            if ($attempt->isLocked()) {
                $lockoutTime = $attempt->getRemainingLockoutFormatted();
                $errorMessage = "Your account is temporarily locked. Try again in {$lockoutTime}.";
            } elseif ($remainingAttempts <= 2 && $remainingAttempts > 0) {
                $errorMessage = "Invalid credentials. You have {$remainingAttempts} attempt(s) remaining before your account will be locked.";
            }

            throw ValidationException::withMessages([
                Fortify::username() => [$errorMessage],
            ]);
        }

        // Record successful login and clear any failed attempts
        UserLoginAttempt::recordSuccessfulAttempt($email, $ipAddress, $userAgent);
        
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);

        return $user;
    }
}