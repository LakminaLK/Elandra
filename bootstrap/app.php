<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            // 'security.log' => \App\Http\Middleware\SecurityLoggingMiddleware::class, // Disabled due to regex issues
        ]);
        
        // Add security headers to all web requests
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            // \App\Http\Middleware\SecurityLoggingMiddleware::class, // Disabled due to regex issues
        ]);
        
        // Add rate limiting to API requests
        $middleware->api(append: [
            \App\Http\Middleware\RateLimitMiddleware::class.':100,1', // 100 requests per minute
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
