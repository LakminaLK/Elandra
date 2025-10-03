# Security Implementation Documentation

## Overview
This document outlines the comprehensive security measures implemented in the Elandra e-commerce application built with Laravel 11. The application follows Laravel security best practices and implements multiple layers of protection against common web vulnerabilities.

## Table of Contents
1. [Authentication & Authorization](#authentication--authorization)
2. [CSRF Protection](#csrf-protection)
3. [XSS Prevention](#xss-prevention)
4. [SQL Injection Prevention](#sql-injection-prevention)
5. [Input Validation](#input-validation)
6. [Data Encryption](#data-encryption)
7. [API Security](#api-security)
8. [Session Security](#session-security)
9. [File Upload Security](#file-upload-security)
10. [Security Headers](#security-headers)
11. [Rate Limiting](#rate-limiting)
12. [Logging & Monitoring](#logging--monitoring)

## 1. Authentication & Authorization

### Laravel Jetstream Authentication
- **Implementation**: Laravel Jetstream with Livewire
- **Features**: 
  - User registration and login
  - Email verification
  - Password reset functionality
  - Two-factor authentication support
  - Profile management

### Role-Based Access Control
```php
// User Model - Admin check
public function isAdmin(): bool
{
    return $this->role === 'admin';
}

// Middleware protection in routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Admin routes
    });
});
```

### Authorization Policies
```php
// OrderPolicy - Secure order access
public function view(User $user, Order $order): bool
{
    return $user->id === $order->user_id || $user->isAdmin();
}
```

## 2. CSRF Protection

### Automatic CSRF Protection
Laravel automatically protects all POST, PUT, PATCH, DELETE requests with CSRF tokens.

### Livewire CSRF Protection
```blade
<!-- CSRF token in layout -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Livewire automatically handles CSRF for all wire: actions -->
<button wire:click="addToCart">Add to Cart</button>
```

### Form Protection
```blade
<!-- All forms include CSRF protection -->
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Sign out</button>
</form>
```

## 3. XSS Prevention

### Blade Template Escaping
```blade
<!-- Automatic escaping of user input -->
<h1>{{ $product->name }}</h1> <!-- Safe -->
<p>{{ $user->description }}</p> <!-- Safe -->

<!-- Raw output only when explicitly needed and trusted -->
{!! $trustedHtmlContent !!} <!-- Used sparingly -->
```

### Input Sanitization
```php
// Model mutators for data cleaning
public function setNameAttribute($value)
{
    $this->attributes['name'] = strip_tags(trim($value));
}

public function setDescriptionAttribute($value)
{
    $this->attributes['description'] = strip_tags($value, '<p><br><strong><em>');
}
```

### Content Security Policy Headers
```php
// In middleware or service provider
response()->header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");
```

## 4. SQL Injection Prevention

### Eloquent ORM Protection
```php
// Safe - uses parameter binding
Product::where('name', 'like', '%' . $search . '%')->get();

// Safe - Eloquent relationships
$user->orders()->where('status', 'completed')->get();

// Safe - Query builder with bindings
DB::table('products')
  ->where('price', '>=', $minPrice)
  ->where('price', '<=', $maxPrice)
  ->get();
```

### Validation Rules
```php
// Request validation prevents malicious input
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1|max:999',
    ];
}
```

## 5. Input Validation

### Form Request Validation
```php
// CreateProductRequest
public function rules(): array
{
    return [
        'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-_\.]+$/',
        'description' => 'required|string|max:2000',
        'price' => 'required|numeric|between:0.01,99999.99',
        'stock_quantity' => 'required|integer|min:0|max:99999',
        'category_id' => 'required|exists:categories,id',
        'sku' => 'required|string|max:100|unique:products,sku',
    ];
}

public function messages(): array
{
    return [
        'name.regex' => 'Product name contains invalid characters.',
        'price.between' => 'Price must be between $0.01 and $99,999.99.',
    ];
}
```

### Livewire Validation
```php
// In Livewire components
protected $rules = [
    'quantity' => 'required|integer|min:1|max:99',
    'search' => 'nullable|string|max:255',
];

public function addToCart()
{
    $this->validate();
    // Process validated data
}
```

### Server-Side Validation
```php
// Additional validation in controllers
public function store(CreateProductRequest $request)
{
    // Additional business logic validation
    if ($request->sale_price >= $request->price) {
        throw ValidationException::withMessages([
            'sale_price' => 'Sale price must be less than regular price.'
        ]);
    }
}
```

## 6. Data Encryption

### Database Encryption
```php
// Sensitive data encryption in models
protected $casts = [
    'credit_card_number' => 'encrypted',
    'ssn' => 'encrypted',
    'personal_notes' => 'encrypted',
];

// Automatic encryption/decryption
$user->credit_card_number = '1234-5678-9012-3456'; // Encrypted in DB
$decrypted = $user->credit_card_number; // Automatically decrypted
```

### Password Hashing
```php
// Automatic password hashing in User model
protected function password(): Attribute
{
    return Attribute::make(
        set: fn ($value) => Hash::make($value),
    );
}

// Verification
if (Hash::check($password, $user->password)) {
    // Password is correct
}
```

### Configuration Encryption
```env
# Environment variables are encrypted in production
APP_KEY=base64:generated_key_here
DB_PASSWORD=encrypted_password
MAIL_PASSWORD=encrypted_password
```

## 7. API Security

### Laravel Sanctum Implementation
```php
// API authentication with Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::post('cart/add', [CartController::class, 'store']);
});

// Token creation with abilities
$token = $user->createToken('api-token', ['products:read', 'cart:write']);
```

### API Rate Limiting
```php
// In RouteServiceProvider
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by(
        $request->user()?->id ?: $request->ip()
    );
});

// Applied to API routes
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // API routes
});
```

### API Validation
```php
// API request validation
public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|between:1,99',
    ]);
    
    // Process validated data
}
```

## 8. Session Security

### Session Configuration
```php
// config/session.php
'lifetime' => 120, // 2 hours
'expire_on_close' => true,
'encrypt' => true,
'http_only' => true,
'same_site' => 'strict',
'secure' => env('SESSION_SECURE_COOKIE', true), // HTTPS only in production
```

### Session Regeneration
```php
// Automatic session regeneration on login
public function authenticate(Request $request)
{
    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate(); // Prevent session fixation
        return redirect()->intended();
    }
}
```

## 9. File Upload Security

### Upload Validation
```php
public function uploadImage(Request $request)
{
    $request->validate([
        'image' => [
            'required',
            'image',
            'mimes:jpeg,png,jpg,gif',
            'max:2048', // 2MB max
            'dimensions:max_width=2000,max_height=2000',
        ],
    ]);
    
    // Store outside public directory
    $path = $request->file('image')->store('products', 'private');
}
```

### File Type Validation
```php
// Custom validation rule for file types
public function rules(): array
{
    return [
        'document' => [
            'required',
            'file',
            function ($attribute, $value, $fail) {
                $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'];
                if (!in_array($value->getMimeType(), $allowedMimes)) {
                    $fail('Invalid file type.');
                }
            },
        ],
    ];
}
```

## 10. Security Headers

### HTTP Security Headers
```php
// Middleware for security headers
public function handle($request, Closure $next)
{
    $response = $next($request);
    
    return $response->withHeaders([
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
    ]);
}
```

## 11. Rate Limiting

### Login Rate Limiting
```php
// Throttle login attempts
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 attempts per minute

// In controller
public function login(Request $request)
{
    $key = Str::lower($request->input('email')).'|'.$request->ip();
    
    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        return response()->json([
            'message' => "Too many login attempts. Try again in {$seconds} seconds."
        ], 429);
    }
    
    if (Auth::attempt($request->only('email', 'password'))) {
        RateLimiter::clear($key);
        return response()->json(['message' => 'Login successful']);
    }
    
    RateLimiter::hit($key, 60); // Lock for 1 minute
    return response()->json(['message' => 'Invalid credentials'], 401);
}
```

### API Rate Limiting
```php
// Different limits for different user types
RateLimiter::for('api', function (Request $request) {
    if ($request->user()?->isAdmin()) {
        return Limit::none(); // No limit for admins
    }
    
    return $request->user()
        ? Limit::perMinute(100)->by($request->user()->id)
        : Limit::perMinute(20)->by($request->ip());
});
```

## 12. Logging & Monitoring

### Security Event Logging
```php
// Log security events
public function login(Request $request)
{
    if (Auth::attempt($request->only('email', 'password'))) {
        Log::info('User logged in', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    } else {
        Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
```

### Audit Trail
```php
// Model events for audit trail
protected static function booted()
{
    static::created(function ($product) {
        Log::info('Product created', [
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'action' => 'created',
        ]);
    });
    
    static::updated(function ($product) {
        Log::info('Product updated', [
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'changes' => $product->getDirty(),
            'action' => 'updated',
        ]);
    });
}
```

## Security Best Practices Implemented

### ✅ Authentication & Access Control
- Multi-factor authentication ready
- Role-based permissions
- Secure password policies
- Session management

### ✅ Data Protection
- Input validation and sanitization
- Output encoding and escaping
- SQL injection prevention
- XSS protection

### ✅ Communication Security
- HTTPS enforcement
- Secure cookie configuration
- CSRF protection
- Security headers

### ✅ API Security
- Token-based authentication
- Rate limiting
- Input validation
- Proper error handling

### ✅ Monitoring & Logging
- Security event logging
- Failed login attempt tracking
- Audit trail for sensitive operations
- Error logging without sensitive data exposure

## Compliance & Standards

This implementation follows:
- **OWASP Top 10** security guidelines
- **Laravel Security Best Practices**
- **PHP Security Standards**
- **Web Security Standards**

## Regular Security Maintenance

1. **Dependencies**: Regular updates of Laravel and packages
2. **Penetration Testing**: Regular security assessments
3. **Code Reviews**: Security-focused code reviews
4. **Monitoring**: Continuous security monitoring
5. **Backup & Recovery**: Secure backup procedures

---

*This security documentation is maintained and updated regularly to reflect the current security posture of the Elandra e-commerce application.*