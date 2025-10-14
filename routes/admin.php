<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CustomerManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

// Admin Authentication Routes (without middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    
    // Email Test Route (remove in production)
    Route::get('/test-email', function () {
        try {
            \Mail::raw('This is a test email from Elandra admin system. If you receive this, your SMTP configuration is working correctly!', function ($message) {
                $message->to('elandra.live@gmail.com')
                        ->subject('Elandra SMTP Test - ' . now()->format('Y-m-d H:i:s'));
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Test email sent successfully! Check your inbox.',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send test email: ' . $e->getMessage(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 500);
        }
    })->name('test.email');
});

// Admin Protected Routes (with admin middleware)
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // System Monitoring
    Route::get('/monitoring', function () {
        return view('admin.monitoring');
    })->name('monitoring');
    
    // Debug routes
    Route::get('/debug-auth', function () {
        $adminAuth = auth()->guard('admin')->check();
        $adminUser = auth()->guard('admin')->user();
        
        return response()->json([
            'admin_authenticated' => $adminAuth,
            'admin_user' => $adminUser ? $adminUser->toArray() : null,
            'csrf_token' => csrf_token()
        ]);
    });
    
    Route::get('/debug-api', function () {
        return view('debug-api');
    });
    
    Route::get('/debug-customers-api', function () {
        return view('debug-customers-api');
    });
    
    // Simple test endpoint
    Route::get('/test-api', function () {
        return response()->json([
            'success' => true,
            'message' => 'API is working!',
            'data' => [
                ['id' => 1, 'name' => 'Test Customer 1', 'email' => 'test1@test.com'],
                ['id' => 2, 'name' => 'Test Customer 2', 'email' => 'test2@test.com']
            ]
        ]);
    });
    
    // Debug routes for testing
    Route::get('/debug-customers', function () {
        return view('debug-customers');
    });
    
    Route::get('/simple-test', function () {
        return view('simple-test');
    });
    
    // Authentication Test Page
    Route::get('/auth-test', function () {
        return view('admin.auth-test');
    })->name('auth-test');
    
    // CSRF Test Endpoint
    Route::post('/test-csrf', function () {
        return response()->json([
            'success' => true,
            'message' => 'CSRF token validated successfully!',
            'csrf_token' => csrf_token(),
            'timestamp' => now()
        ]);
    });
    
    // Product Management (Livewire)
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
    });
    
    // Category & Brand Management
    Route::get('/categories-brands', [App\Http\Controllers\Admin\CategoryBrandController::class, 'index'])->name('categories-brands.index');
    
    // Customer Management (API-driven)
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerManagementController::class, 'index'])->name('index');
        Route::get('/export', [CustomerManagementController::class, 'exportCsv'])->name('export');
        Route::get('/create', [CustomerManagementController::class, 'create'])->name('create');
        Route::post('/', [CustomerManagementController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerManagementController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerManagementController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerManagementController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{customer}/toggle-status', [CustomerManagementController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderManagementController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderManagementController::class, 'show'])->name('show');
        Route::patch('/{order}/update-status', [OrderManagementController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{order}/payment-status', [OrderManagementController::class, 'updatePaymentStatus'])->name('update-payment-status');
        Route::post('/bulk-update', [OrderManagementController::class, 'bulkUpdateStatus'])->name('bulk-update');
        Route::get('/report', [OrderManagementController::class, 'generateReport'])->name('report');
        Route::get('/{order}/invoice', [OrderManagementController::class, 'generateInvoice'])->name('invoice');
        Route::post('/{order}/send-email', [OrderManagementController::class, 'sendEmail'])->name('send-email');
        Route::delete('/{order}', [OrderManagementController::class, 'destroy'])->name('destroy');
    });
    
    // Category Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryManagementController::class, 'index'])->name('index');
        Route::get('/create', [CategoryManagementController::class, 'create'])->name('create');
        Route::post('/', [CategoryManagementController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryManagementController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryManagementController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryManagementController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{category}/toggle-status', [CategoryManagementController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('index');
        Route::post('/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
        Route::post('/update-password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('update-password');
    });
    
    // Settings & Configuration
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('admin.settings.index');
        })->name('index');
        
        Route::get('/profile', function () {
            return view('admin.settings.profile');
        })->name('profile');
    });
    
    // Reports & Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            return view('admin.reports.index');
        })->name('index');
        
        Route::get('/sales', function () {
            return view('admin.reports.sales');
        })->name('sales');
        
        Route::get('/products', function () {
            return view('admin.reports.products');
        })->name('products');
        
        Route::get('/users', function () {
            return view('admin.reports.users');
        })->name('users');
    });
    
    // Address Management (New - matches ER diagram)
    Route::get('/addresses', function () {
        return view('admin.addresses.index');
    })->name('addresses.index');
    
    // Payment Management (New - matches ER diagram)  
    Route::get('/payments', function () {
        return view('admin.payments.index');
    })->name('payments.index');
});