<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\Auth\CustomPasswordResetController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

// Health check routes
Route::get('/health', [HealthController::class, 'simpleHealth'])->name('health.simple');
Route::get('/health/detailed', [HealthController::class, 'healthCheck'])->name('health.detailed');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/category/{category:slug}', [ProductController::class, 'category'])->name('category');
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    
    Route::match(['GET', 'POST'], '/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/cancel', [App\Http\Controllers\CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/success/{order}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    
    // Customer Orders
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/photo', [App\Http\Controllers\ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    Route::delete('/profile/photo', [App\Http\Controllers\ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('products.index');
    
    Route::get('/products/{product}/edit', function (App\Models\Product $product) {
        return view('admin.products.edit', compact('product'));
    })->name('products.edit');
    
    Route::get('/orders', function () {
        return view('admin.orders.index');
    })->name('orders.index');
    
    // Product Image Management Routes
    Route::prefix('products/{product}')->name('products.images.')->group(function () {
        Route::get('/images', [ProductImageController::class, 'index'])->name('index');
        Route::post('/images/upload', [ProductImageController::class, 'upload'])->name('upload');
        Route::delete('/images', [ProductImageController::class, 'delete'])->name('delete');
        Route::patch('/images/main', [ProductImageController::class, 'setMain'])->name('set-main');
    });
    
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');
    
    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('categories.index');
});

// OTP Routes
Route::post('/register', [\App\Http\Controllers\Auth\OtpController::class, 'sendOtp'])->name('register');
Route::post('/verify-otp', [\App\Http\Controllers\Auth\OtpController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/resend-otp', [\App\Http\Controllers\Auth\OtpController::class, 'resendOtp'])->name('resend.otp');

// Custom Password Reset Routes
Route::get('/forgot-password', [CustomPasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [CustomPasswordResetController::class, 'sendResetEmail'])->name('password.email.send');
Route::get('/reset-password/{token}', [CustomPasswordResetController::class, 'showResetForm'])->name('password.reset.show');
Route::post('/reset-password', [CustomPasswordResetController::class, 'resetPassword'])->name('password.update.custom');

// Test route for API debugging
Route::get('/test-customer-api', function () {
    return view('test-customer-api');
})->name('test.customer.api');

// Preview welcome email template
Route::get('/preview-welcome-email', function () {
    $user = (object) [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ];
    return view('emails.welcome', ['user' => $user]);
})->name('preview.welcome.email');

// Preview password reset email template
Route::get('/preview-password-reset-email', function () {
    $user = (object) [
        'name' => 'Lakmina Welagedara',
        'email' => 'lakmina@example.com'
    ];
    $resetUrl = 'http://127.0.0.1:8000/reset-password/sample-token-12345';
    return view('emails.password-reset', ['user' => $user, 'resetUrl' => $resetUrl]);
})->name('preview.password.reset.email');

// Preview password changed email template
Route::get('/preview-password-changed-email', function () {
    $user = (object) [
        'name' => 'Lakmina Welagedara',
        'email' => 'lakmina@example.com'
    ];
    return view('emails.password-changed', ['user' => $user]);
})->name('preview.password.changed.email');

// Test MongoDB products
Route::get('/test-products', function () {
    try {
        $products = \App\Models\MongoProduct::take(5)->get();
        $totalProducts = \App\Models\MongoProduct::count();
        
        return response()->json([
            'status' => 'success',
            'total_products' => $totalProducts,
            'sample_products' => $products->map(function($product) {
                return [
                    'id' => $product->_id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'brand' => $product->brand,
                    'price' => $product->price,
                    'stock_quantity' => $product->stock_quantity,
                ];
            })
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('test.products');
