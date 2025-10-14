<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminLoginAttempt extends Model
{
    protected $fillable = [
        'email',
        'ip_address',
        'attempts_count',
        'locked_until',
        'is_successful',
        'user_agent'
    ];

    protected $casts = [
        'locked_until' => 'datetime',
        'is_successful' => 'boolean',
    ];

    /**
     * Check if the account is currently locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Get remaining lockout time in seconds
     */
    public function getRemainingLockoutSeconds(): int
    {
        if (!$this->locked_until || !$this->locked_until->isFuture()) {
            return 0;
        }
        
        return now()->diffInSeconds($this->locked_until);
    }

    /**
     * Get remaining lockout time formatted
     */
    public function getRemainingLockoutFormatted(): string
    {
        if (!$this->locked_until) {
            return 'Account is not locked';
        }
        
        if (!$this->locked_until->isFuture()) {
            return 'Account is no longer locked';
        }
        
        $seconds = $this->getRemainingLockoutSeconds();
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        
        if ($minutes > 0) {
            if ($remainingSeconds > 30) {
                $minutes++; // Round up if more than 30 seconds
            }
            return $minutes === 1 ? "1 minute" : "{$minutes} minutes";
        } else {
            return $seconds > 1 ? "{$seconds} seconds" : "a few seconds";
        }
    }

    /**
     * Get current attempt for email and IP combination
     */
    public static function getCurrentAttempt(string $email, string $ipAddress): ?self
    {
        return self::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->where('is_successful', false)
            ->first();
    }

    /**
     * Record a failed login attempt
     */
    public static function recordFailedAttempt(string $email, string $ipAddress, string $userAgent = null): self
    {
        $attempt = self::getCurrentAttempt($email, $ipAddress);
        
        if ($attempt) {
            // Increment existing attempt count
            $attempt->attempts_count++;
            
            // Lock account after 5 attempts for 10 minutes
            if ($attempt->attempts_count >= 5) {
                $attempt->locked_until = now()->addMinutes(10);
            }
            
            $attempt->user_agent = $userAgent;
            $attempt->save();
        } else {
            // Create new attempt record
            $attempt = self::create([
                'email' => $email,
                'ip_address' => $ipAddress,
                'attempts_count' => 1,
                'user_agent' => $userAgent,
                'is_successful' => false,
            ]);
        }
        
        return $attempt;
    }

    /**
     * Record a successful login attempt
     */
    public static function recordSuccessfulAttempt(string $email, string $ipAddress, string $userAgent = null): self
    {
        // Clear any existing failed attempts
        self::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->where('is_successful', false)
            ->delete();
        
        // Record successful attempt
        return self::create([
            'email' => $email,
            'ip_address' => $ipAddress,
            'attempts_count' => 1,
            'user_agent' => $userAgent,
            'is_successful' => true,
        ]);
    }

    /**
     * Clean up old login attempt records (older than 24 hours)
     */
    public static function cleanup(): void
    {
        self::where('created_at', '<', now()->subHours(24))->delete();
    }
}
