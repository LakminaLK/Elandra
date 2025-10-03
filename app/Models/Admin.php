<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if admin has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Boot method to set default role for admins.
     * Only 'admin' role allowed - no super_admin or other roles.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($admin) {
            // Force all admins to have 'admin' role only
            $admin->role = 'admin';
        });
        
        static::updating(function ($admin) {
            // Prevent setting roles other than 'admin'
            if ($admin->role !== 'admin') {
                $admin->role = 'admin';
            }
        });
    }

    /**
     * Scope for active admins
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
