<?php

use Illuminate\Support\Facades\Route;

// Debug route to check authentication
Route::get('/debug-auth', function () {
    $adminAuth = auth()->guard('admin')->check();
    $adminUser = auth()->guard('admin')->user();
    
    return response()->json([
        'admin_authenticated' => $adminAuth,
        'admin_user' => $adminUser ? $adminUser->toArray() : null,
        'session_data' => session()->all(),
        'csrf_token' => csrf_token()
    ]);
});
?>