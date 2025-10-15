<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains various security settings for the application.
    | These settings control rate limiting, content security, logging,
    | and other security-related features.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for different types of requests.
    | Values are in requests per minute unless otherwise specified.
    |
    */
    'rate_limiting' => [
        'api' => [
            'default' => 100,
            'authenticated' => 200,
            'admin' => 500,
        ],
        'auth' => [
            'login_attempts' => 5,
            'registration' => 3,
            'password_reset' => 3,
        ],
        'search' => [
            'default' => 30,
            'authenticated' => 60,
        ],
        'cart' => [
            'add_item' => 20,
            'update_quantity' => 15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy (CSP)
    |--------------------------------------------------------------------------
    |
    | Define content security policy directives.
    | When using ngrok, we need to allow assets from the ngrok domain.
    |
    */
    'csp' => [
        'default-src' => "'self' https://*.ngrok-free.dev https://*.ngrok.app",
        'script-src' => "'self' 'unsafe-inline' 'unsafe-eval' https://*.ngrok-free.dev https://*.ngrok.app https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com",
        'style-src' => "'self' 'unsafe-inline' https://*.ngrok-free.dev https://*.ngrok.app https://fonts.googleapis.com https://fonts.bunny.net https://cdnjs.cloudflare.com",
        'font-src' => "'self' https://*.ngrok-free.dev https://*.ngrok.app https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com",
        'img-src' => "'self' data: https: https://*.ngrok-free.dev https://*.ngrok.app",
        'connect-src' => "'self' https://*.ngrok-free.dev https://*.ngrok.app https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://ipapi.co",
        'frame-src' => "'none'",
        'object-src' => "'none'",
        'media-src' => "'self' https://*.ngrok-free.dev https://*.ngrok.app",
        'form-action' => "'self' https://*.ngrok-free.dev https://*.ngrok.app",
        'frame-ancestors' => "'none'",
        'base-uri' => "'self'",
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Additional security headers to be added to responses.
    |
    */
    'headers' => [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation
    |--------------------------------------------------------------------------
    |
    | Security settings for input validation and sanitization.
    |
    */
    'validation' => [
        'max_input_length' => 10000,
        'max_file_size' => 10485760, // 10MB in bytes
        'allowed_file_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'forbidden_patterns' => [
            'sql_injection' => '/(\bunion\b.*\bselect\b)|(\bselect\b.*\bfrom\b)|(\binsert\b.*\binto\b)|(\bupdate\b.*\bset\b)|(\bdelete\b.*\bfrom\b)/i',
            'xss' => '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi',
            'path_traversal' => '/\.\.\/|\.\.\\\/i',
            'command_injection' => '/(\;|\||\&|\$\(|\`)/i',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Security settings for user sessions.
    |
    */
    'session' => [
        'lifetime' => 120, // minutes
        'idle_timeout' => 30, // minutes
        'regenerate_on_login' => true,
        'secure_cookies' => env('SESSION_SECURE_COOKIES', true),
        'http_only_cookies' => true,
        'same_site_cookies' => 'Strict',
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Security
    |--------------------------------------------------------------------------
    |
    | Password policy and security requirements.
    |
    */
    'password' => [
        'min_length' => 8,
        'require_mixed_case' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'check_compromised' => true,
        'history_count' => 5, // Prevent reusing last 5 passwords
        'max_age_days' => 90, // Force password change after 90 days
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Security event logging settings.
    |
    */
    'logging' => [
        'enabled' => env('SECURITY_LOGGING_ENABLED', true),
        'log_channel' => env('SECURITY_LOG_CHANNEL', 'security'),
        'log_failed_logins' => true,
        'log_suspicious_requests' => true,
        'log_admin_actions' => true,
        'log_sensitive_data' => false,
        'retention_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security
    |--------------------------------------------------------------------------
    |
    | API-specific security settings.
    |
    */
    'api' => [
        'require_https' => env('API_REQUIRE_HTTPS', true),
        'token_expiry' => 60, // minutes
        'max_requests_per_token' => 1000,
        'allowed_origins' => [
            'localhost:3000',
            'localhost:8000',
        ],
        'blocked_user_agents' => [
            'curl',
            'wget',
            'python-requests',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Security settings for file uploads.
    |
    */
    'uploads' => [
        'max_file_size' => 5242880, // 5MB
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
        ],
        'scan_for_viruses' => env('SCAN_UPLOADS_FOR_VIRUSES', false),
        'quarantine_suspicious_files' => true,
        'store_outside_webroot' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Security
    |--------------------------------------------------------------------------
    |
    | Database security and query monitoring.
    |
    */
    'database' => [
        'log_slow_queries' => true,
        'slow_query_threshold' => 2000, // milliseconds
        'monitor_suspicious_queries' => true,
        'limit_query_results' => 1000,
        'encrypt_sensitive_data' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Emergency Settings
    |--------------------------------------------------------------------------
    |
    | Emergency security measures that can be activated.
    |
    */
    'emergency' => [
        'maintenance_mode' => env('EMERGENCY_MAINTENANCE', false),
        'disable_registration' => env('EMERGENCY_DISABLE_REGISTRATION', false),
        'admin_only_access' => env('EMERGENCY_ADMIN_ONLY', false),
        'block_suspicious_ips' => env('EMERGENCY_BLOCK_SUSPICIOUS_IPS', false),
    ],
];