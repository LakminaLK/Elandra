<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MongoProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ValidationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Validation routes (no auth required)
Route::middleware('throttle:60,1')->group(function () {
    Route::post('check-email', [ValidationController::class, 'checkEmail']);
    Route::post('check-mobile', [ValidationController::class, 'checkMobile']);
});

// Public routes with rate limiting
Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Public product routes
Route::prefix('products')->group(function () {
    Route::get('/', [MongoProductController::class, 'index']);
    Route::get('/categories', [MongoProductController::class, 'categories']);
    Route::get('/brands', [MongoProductController::class, 'brands']);
    Route::get('/search', [MongoProductController::class, 'search']);
    Route::get('/stats', [MongoProductController::class, 'stats']);
    Route::get('/{id}', [MongoProductController::class, 'show']);
});

// Protected routes with enhanced security
Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('tokens', [AuthController::class, 'tokens']);
        Route::post('tokens', [AuthController::class, 'createTokenWithAbilities']);
        Route::delete('tokens/{tokenId}', [AuthController::class, 'revokeToken']);
        Route::delete('tokens', [AuthController::class, 'revokeAllTokens']);
    });

    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'add']);
        Route::put('/{cartItem}', [CartController::class, 'update']);
        Route::delete('/{cartItem}', [CartController::class, 'remove']);
        Route::delete('/', [CartController::class, 'clear']);
        Route::get('/total', [CartController::class, 'total']);
    });

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
    });

        // Admin only routes with enhanced capabilities
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Advanced Analytics API
        Route::prefix('analytics')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Api\Admin\AnalyticsController::class, 'getDashboardStats']);
            Route::get('/activity-feed', [App\Http\Controllers\Api\Admin\AnalyticsController::class, 'getActivityFeed']);
            Route::post('/export', [App\Http\Controllers\Api\Admin\AnalyticsController::class, 'exportAnalytics']);
        });

        // Advanced Product Management API (MongoDB)
        Route::prefix('products')->group(function () {
            Route::get('/', [MongoProductController::class, 'index']);
            Route::post('/', [MongoProductController::class, 'store']);
            Route::get('/{id}', [MongoProductController::class, 'show']);
            Route::put('/{id}', [MongoProductController::class, 'update']);
            Route::delete('/{id}', [MongoProductController::class, 'destroy']);
        });

        // Advanced Order Management API
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'adminIndex']);
            Route::get('/statistics', [OrderController::class, 'getOrderStatistics']);
            Route::get('/{order}', [OrderController::class, 'adminShow']);
            Route::put('/{order}/status', [OrderController::class, 'updateStatus']);
            Route::post('/{order}/refund', [OrderController::class, 'processRefund']);
            Route::get('/{order}/timeline', [OrderController::class, 'getOrderTimeline']);
        });

        // Advanced User Management API
        Route::prefix('users')->group(function () {
            Route::get('/', [AuthController::class, 'adminUsers']);
            Route::post('/', [AuthController::class, 'createUser']);
            Route::get('/{user}', [AuthController::class, 'adminUserDetails']);
            Route::put('/{user}', [AuthController::class, 'updateUser']);
            Route::delete('/{user}', [AuthController::class, 'deleteUser']);
            Route::post('/{user}/toggle-status', [AuthController::class, 'updateUserStatus']);
            Route::get('/{user}/orders', [AuthController::class, 'getUserOrders']);
            Route::get('/{user}/analytics', [AuthController::class, 'getUserAnalytics']);
        });

        // Category Management now handled by Livewire components
        // MongoDB categories are managed through the CategoryBrandManagement Livewire component

        // System Management API
        Route::prefix('system')->group(function () {
            Route::get('/health', [App\Http\Controllers\Api\Admin\SystemController::class, 'healthCheck']);
            Route::get('/logs', [App\Http\Controllers\Api\Admin\SystemController::class, 'getLogs']);
            Route::post('/cache/clear', [App\Http\Controllers\Api\Admin\SystemController::class, 'clearCache']);
            Route::get('/performance', [App\Http\Controllers\Api\Admin\SystemController::class, 'getPerformanceMetrics']);
        });
    });
});

// Customer Management API Routes (for admin use)
Route::prefix('customers')->middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {
    Route::get('/', [App\Http\Controllers\Api\CustomerApiController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Api\CustomerApiController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'destroy']);
    Route::patch('/{id}/toggle-status', [App\Http\Controllers\Api\CustomerApiController::class, 'toggleStatus']);
    Route::get('/test/api', [App\Http\Controllers\Api\CustomerApiController::class, 'test']);
});

// Admin Customer API Routes (protected for admin access only)
Route::prefix('admin/customers')->middleware(['throttle:100,1'])->group(function () {
    Route::get('/', [App\Http\Controllers\Api\CustomerApiController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Api\CustomerApiController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'destroy']);
    Route::patch('/{id}/toggle-status', [App\Http\Controllers\Api\CustomerApiController::class, 'toggleStatus']);
    Route::get('/test/api', [App\Http\Controllers\Api\CustomerApiController::class, 'test']);
});

// Public Customer API Routes (no authentication required for testing)
Route::prefix('public/customers')->middleware(['throttle:60,1'])->group(function () {
    Route::get('/', [App\Http\Controllers\Api\CustomerApiController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Api\CustomerApiController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\Api\CustomerApiController::class, 'destroy']);
    Route::patch('/{id}/toggle-status', [App\Http\Controllers\Api\CustomerApiController::class, 'toggleStatus']);
    Route::get('/test/api', [App\Http\Controllers\Api\CustomerApiController::class, 'test']);
});

// Fallback route for API
Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'API endpoint not found'
    ], 404);
});
