<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

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
        'mobile',
        'address',
        'city',
        'country',
        'profile_photo_path',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
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
            'password' => 'hashed',
            'address' => 'array',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Determine if the user is a customer.
     * Note: User model only handles customers. Admins are in separate Admin model.
     */
    public function isCustomer(): bool
    {
        return true; // All users in this table are customers
    }
    
    /**
     * Boot method to set default role for users.
     * Users table only contains customers - admins use separate Admin model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            // Force all users in this table to be customers
            $user->role = 'customer';
        });
    }

    /**
     * Get the user's cart items.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the user's orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's addresses (ER Diagram relationship).
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the user's default shipping address.
     */
    public function defaultShippingAddress()
    {
        return $this->hasOne(Address::class)->where('type', 'shipping')->where('is_default', true);
    }

    /**
     * Get the user's default billing address.
     */
    public function defaultBillingAddress()
    {
        return $this->hasOne(Address::class)->where('type', 'billing')->where('is_default', true);
    }

    /**
     * Get the user's full name with title accessor.
     */
    public function getFullNameAttribute(): string
    {
        return ucfirst($this->name);
    }

    /**
     * Get the user's total orders count.
     */
    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    /**
     * Get the user's total spent amount.
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->orders()->where('status', 'completed')->sum('total_amount');
    }

    /**
     * Get the user's initials for avatar display.
     */
    public function getInitialsAttribute()
    {
        $nameParts = explode(' ', trim($this->name));
        $initials = '';
        
        // Get first letter of first name
        if (isset($nameParts[0])) {
            $initials .= strtoupper(substr($nameParts[0], 0, 1));
        }
        
        // Get first letter of last name (if exists)
        if (isset($nameParts[1])) {
            $initials .= strtoupper(substr($nameParts[1], 0, 1));
        } elseif (strlen($nameParts[0]) > 1) {
            // If no last name, use second letter of first name
            $initials .= strtoupper(substr($nameParts[0], 1, 1));
        }
        
        return $initials;
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        
        // Return a default avatar using the user's initials
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'initials',
    ];
}
