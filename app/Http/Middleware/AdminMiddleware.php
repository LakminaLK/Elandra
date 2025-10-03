<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated
        if (!auth()->guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // Check if admin is active
        $admin = auth()->guard('admin')->user();
        if (!$admin->is_active) {
            auth()->guard('admin')->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Your admin account has been deactivated.']);
        }

        return $next($request);
    }
}