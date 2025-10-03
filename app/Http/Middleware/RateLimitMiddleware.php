<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = (int) $maxAttempts;
        $decayMinutes = (int) $decayMinutes;

        if ($this->tooManyAttempts($key, $maxAttempts)) {
            $this->logRateLimitExceeded($request, $key);
            return $this->buildResponse($key, $maxAttempts);
        }

        $this->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return sha1('rate_limit:' . $user->id . '|' . $request->ip());
        }

        return sha1('rate_limit:' . $request->ip() . '|' . $request->userAgent());
    }

    /**
     * Determine if the given key has been "accessed" too many times.
     */
    protected function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        return $this->attempts($key) >= $maxAttempts;
    }

    /**
     * Get the number of attempts for the given key.
     */
    protected function attempts(string $key): int
    {
        return Cache::get($key, 0);
    }

    /**
     * Increment the counter for a given key for a given decay time.
     */
    protected function hit(string $key, int $decaySeconds = 60): int
    {
        Cache::add($key, 0, $decaySeconds);
        
        $attempts = (int) Cache::increment($key);
        
        Cache::put($key, $attempts, $decaySeconds);
        
        return $attempts;
    }

    /**
     * Calculate the number of remaining attempts.
     */
    protected function calculateRemainingAttempts(string $key, int $maxAttempts): int
    {
        return max(0, $maxAttempts - $this->attempts($key));
    }

    /**
     * Create a 'too many attempts' response.
     */
    protected function buildResponse(string $key, int $maxAttempts): Response
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = [
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => 0,
            'Retry-After' => $retryAfter,
            'X-RateLimit-Reset' => now()->addSeconds($retryAfter)->timestamp,
        ];

        return response()->json([
            'message' => 'Too many requests. Please try again later.',
            'retry_after' => $retryAfter
        ], 429, $headers);
    }

    /**
     * Get the number of seconds until the next retry.
     */
    protected function getTimeUntilNextRetry(string $key): int
    {
        return Cache::get($key . ':timer', 60);
    }

    /**
     * Add the limit header information to the given response.
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts): Response
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);

        return $response;
    }

    /**
     * Log rate limit exceeded event.
     */
    protected function logRateLimitExceeded(Request $request, string $key): void
    {
        Log::warning('Rate limit exceeded', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'user_id' => $request->user()?->id,
            'key' => $key,
            'timestamp' => now()->toISOString(),
        ]);
    }
}
