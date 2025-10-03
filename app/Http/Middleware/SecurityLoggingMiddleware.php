<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityLoggingMiddleware
{
    /**
     * Suspicious patterns to monitor.
     */
    protected array $suspiciousPatterns = [
        // SQL Injection patterns
        '/(\bunion\b.*\bselect\b)|(\bselect\b.*\bfrom\b)|(\binsert\b.*\binto\b)|(\bupdate\b.*\bset\b)|(\bdelete\b.*\bfrom\b)/i',
        // XSS patterns
        '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i',
        '/javascript:/i',
        '/on\w+\s*=/i',
        // Path traversal
        '/\.\.\/|\.\.\\\\\/i',
        // Command injection
        '/[;|&$`()]/i',
        // File inclusion
        '/\b(php|asp|jsp|cfm|pl|py|rb|cgi):/i',
    ];

    /**
     * Sensitive endpoints that require extra logging.
     */
    protected array $sensitiveEndpoints = [
        '/login',
        '/register',
        '/password',
        '/admin',
        '/api',
        '/cart',
        '/checkout',
        '/orders',
        '/profile',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Log suspicious requests before processing
        $this->logSuspiciousActivity($request);
        
        // Log access to sensitive endpoints
        $this->logSensitiveAccess($request);

        $response = $next($request);

        // Log response and timing
        $this->logRequestComplete($request, $response, $startTime);

        return $response;
    }

    /**
     * Log suspicious activity patterns.
     */
    protected function logSuspiciousActivity(Request $request): void
    {
        $allInput = $request->all();
        $queryString = $request->getQueryString();
        $userAgent = $request->userAgent();
        $referer = $request->header('referer');

        // Check for suspicious patterns in all inputs
        foreach ($this->suspiciousPatterns as $pattern) {
            // Check query parameters
            if ($queryString && preg_match($pattern, $queryString)) {
                $this->logSecurityEvent('suspicious_query_string', $request, [
                    'pattern' => $pattern,
                    'query_string' => $queryString,
                ]);
            }

            // Check POST data
            foreach ($allInput as $key => $value) {
                if (is_string($value) && preg_match($pattern, $value)) {
                    $this->logSecurityEvent('suspicious_input', $request, [
                        'pattern' => $pattern,
                        'field' => $key,
                        'value' => substr($value, 0, 500), // Limit logged data
                    ]);
                }
            }

            // Check User-Agent
            if ($userAgent && preg_match($pattern, $userAgent)) {
                $this->logSecurityEvent('suspicious_user_agent', $request, [
                    'pattern' => $pattern,
                    'user_agent' => $userAgent,
                ]);
            }

            // Check Referer
            if ($referer && preg_match($pattern, $referer)) {
                $this->logSecurityEvent('suspicious_referer', $request, [
                    'pattern' => $pattern,
                    'referer' => $referer,
                ]);
            }
        }

        // Check for unusual request characteristics
        $this->checkUnusualRequests($request);
    }

    /**
     * Check for unusual request patterns.
     */
    protected function checkUnusualRequests(Request $request): void
    {
        // Unusually long query strings
        if (strlen($request->getQueryString() ?? '') > 2000) {
            $this->logSecurityEvent('long_query_string', $request, [
                'length' => strlen($request->getQueryString()),
            ]);
        }

        // Unusually large POST bodies
        $contentLength = $request->header('content-length');
        if ($contentLength && $contentLength > 1048576) { // 1MB
            $this->logSecurityEvent('large_post_body', $request, [
                'content_length' => $contentLength,
            ]);
        }

        // Missing or suspicious User-Agent
        $userAgent = $request->userAgent();
        if (!$userAgent || strlen($userAgent) < 10) {
            $this->logSecurityEvent('missing_or_short_user_agent', $request, [
                'user_agent' => $userAgent,
            ]);
        }

        // Multiple rapid requests from same IP
        $this->checkRapidRequests($request);
    }

    /**
     * Check for rapid requests that might indicate automated attacks.
     */
    protected function checkRapidRequests(Request $request): void
    {
        $ip = $request->ip();
        $key = "rapid_requests:{$ip}";
        $requests = cache()->get($key, []);
        
        $now = time();
        $requests[] = $now;
        
        // Keep only requests from last minute
        $requests = array_filter($requests, fn($time) => $now - $time < 60);
        
        cache()->put($key, $requests, 300); // 5 minutes cache
        
        // If more than 100 requests per minute
        if (count($requests) > 100) {
            $this->logSecurityEvent('rapid_requests', $request, [
                'requests_per_minute' => count($requests),
                'threshold' => 100,
            ]);
        }
    }

    /**
     * Log access to sensitive endpoints.
     */
    protected function logSensitiveAccess(Request $request): void
    {
        $path = $request->path();
        
        foreach ($this->sensitiveEndpoints as $endpoint) {
            if (str_starts_with($path, trim($endpoint, '/'))) {
                Log::info('Sensitive endpoint access', [
                    'endpoint' => $endpoint,
                    'path' => $path,
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_id' => $request->user()?->id,
                    'user_agent' => $request->userAgent(),
                    'referer' => $request->header('referer'),
                    'timestamp' => now()->toISOString(),
                ]);
                break;
            }
        }
    }

    /**
     * Log request completion with timing.
     */
    protected function logRequestComplete(Request $request, Response $response, float $startTime): void
    {
        $duration = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        
        // Log slow requests (over 2 seconds)
        if ($duration > 2000) {
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration_ms' => round($duration, 2),
                'status_code' => $response->getStatusCode(),
                'ip' => $request->ip(),
                'user_id' => $request->user()?->id,
            ]);
        }

        // Log failed authentication attempts
        if (in_array($response->getStatusCode(), [401, 403])) {
            $this->logSecurityEvent('authentication_failure', $request, [
                'status_code' => $response->getStatusCode(),
            ]);
        }

        // Log server errors
        if ($response->getStatusCode() >= 500) {
            Log::error('Server error occurred', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'status_code' => $response->getStatusCode(),
                'ip' => $request->ip(),
                'user_id' => $request->user()?->id,
                'user_agent' => $request->userAgent(),
            ]);
        }
    }

    /**
     * Log security events with consistent format.
     */
    protected function logSecurityEvent(string $eventType, Request $request, array $additionalData = []): void
    {
        Log::warning("Security event: {$eventType}", array_merge([
            'event_type' => $eventType,
            'ip' => $request->ip(),
            'user_id' => $request->user()?->id,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'timestamp' => now()->toISOString(),
            'session_id' => $request->session()?->getId(),
        ], $additionalData));
    }
}
