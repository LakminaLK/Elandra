<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Elandra')); ?> - <?php echo $__env->yieldContent('title', 'Luxury Handbags'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="<?php echo e(asset('elandra-logo.webp')); ?>">
    <link rel="shortcut icon" href="<?php echo e(asset('elandra-logo.webp')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('elandra-logo.webp')); ?>">
    <meta name="msapplication-TileImage" content="<?php echo e(asset('elandra-logo.webp')); ?>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- AOS - Animate On Scroll Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary: #dc2626;
            --secondary-light: #ef4444;
            --secondary-dark: #b91c1c;
            --accent: #7c3aed;
            --light-bg: #f9fafb;
            --dark-bg: #111827;
            --text-light: #f3f4f6;
            --text-dark: #1f2937;
            --subtle-bg: #f0f5ff;
            --border-light: rgba(209, 213, 219, 0.5);
        }
        
        /* Custom Animation Classes */
        .animate-fade-in {
            animation: fadeIn 1s ease-in-out forwards;
            opacity: 0;
        }
        
        .animate-slide-up {
            animation: slideUp 1s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .animate-scale-in {
            animation: scaleIn 0.8s ease-out forwards;
            opacity: 0;
            transform: scale(0.9);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes scaleIn {
            from { 
                opacity: 0;
                transform: scale(0.9);
            }
            to { 
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Enhanced animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(0.95); }
        }
        
        /* Alpine.js cloaking */
        [x-cloak] { 
            display: none !important; 
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes wave {
            0% { transform: translateX(0) translateZ(0) scaleY(1); }
            50% { transform: translateX(-25%) translateZ(0) scaleY(0.8); }
            100% { transform: translateX(-50%) translateZ(0) scaleY(1); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-pulse-custom {
            animation: pulse 3s ease-in-out infinite;
        }
        
        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }
        
        /* Better contrast for readability */
        .text-high-contrast {
            color: #1a1a1a;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }
        
        .bg-dark-overlay {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.6));
        }
        
        /* Smooth hover transitions */
        .smooth-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        /* Professional hover effects */
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Professional gradient backgrounds */
        .bg-professional {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        .bg-subtle-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%);
        }
        
        /* Card effects */
        .card-hover-effect {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .card-hover-effect:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased overflow-x-hidden">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center h-20">
                    <!-- Logo - Fixed width -->
                    <div class="flex items-center w-64 flex-shrink-0">
                        <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-3 group">
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-primary-light to-secondary rounded-full blur-sm opacity-70 group-hover:opacity-100 transition-all duration-300"></div>
                                <img src="<?php echo e(asset('elandra-logo.webp')); ?>" alt="Elandra Logo" class="w-12 h-12 object-contain rounded-full relative border-2 border-white shadow-lg" onerror="this.style.display='none'">
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent group-hover:from-primary group-hover:to-secondary transition-all duration-300">Elandra</span>
                        </a>
                    </div>

                    <!-- Centered Navigation -->
                    <div class="hidden md:flex items-center justify-center flex-1">
                        <div class="flex items-center space-x-8">
                            <a href="<?php echo e(route('home')); ?>" class="relative px-3 py-3 text-sm font-medium transition-all overflow-hidden group <?php echo e(request()->routeIs('home') ? 'text-primary border-b-2 border-primary' : 'text-gray-700 hover:text-primary'); ?>">
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all group-hover:w-full duration-300"></span>
                                <span class="relative">Home</span>
                            </a>
                            <a href="<?php echo e(route('products.index')); ?>" class="relative px-3 py-3 text-sm font-medium transition-all overflow-hidden group <?php echo e(request()->routeIs('products.index') ? 'text-primary border-b-2 border-primary' : 'text-gray-700 hover:text-primary'); ?>">
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all group-hover:w-full duration-300"></span>
                                <span class="relative">Products</span>
                            </a>
                            <a href="#" class="relative px-3 py-3 text-sm font-medium transition-all overflow-hidden group text-gray-700 hover:text-primary">
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all group-hover:w-full duration-300"></span>
                                <span class="relative">Categories</span>
                            </a>
                            <a href="#" class="relative px-3 py-3 text-sm font-medium transition-all overflow-hidden group text-gray-700 hover:text-primary">
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all group-hover:w-full duration-300"></span>
                                <span class="relative">About</span>
                            </a>
                            <a href="#" class="relative px-3 py-3 text-sm font-medium transition-all overflow-hidden group text-gray-700 hover:text-primary">
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all group-hover:w-full duration-300"></span>
                                <span class="relative">Contact</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Side - Fixed width to match left -->
                    <div class="flex items-center justify-end space-x-4 w-64 flex-shrink-0">

                        <?php if(auth()->guard()->check()): ?>
                            <!-- Cart Button -->
                            <a href="<?php echo e(route('cart')); ?>" class="relative inline-flex items-center px-3 py-2 text-gray-700 hover:text-primary hover:bg-gray-50 transition-all duration-300 group">
                                <i class="fas fa-shopping-cart text-lg group-hover:scale-110 transition-transform duration-300"></i>
                                <!-- Visible cart count positioned below -->
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('cart-count');

$__html = app('livewire')->mount($__name, $__params, 'lw-2402888153-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            </a>
                            
                            <!-- User Menu Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center px-3 py-2 text-gray-700 hover:text-primary hover:bg-gray-50 transition-all duration-300 group">
                                    <i class="fas fa-user text-lg group-hover:scale-110 transition-transform duration-300"></i>
                                </button>
                                    
                                    <div x-show="open" @click.away="open = false" x-cloak 
                                         x-transition:enter="transition ease-out duration-200" 
                                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2" 
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150" 
                                         x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                                         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                         class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 py-2 overflow-hidden z-50">
                                        
                                        <!-- User Info Header -->
                                        <div class="px-4 py-3 border-b border-gray-100">
                                            <div class="flex items-center space-x-3">
                                                <?php if(auth()->user()->profile_photo_path && file_exists(storage_path('app/public/' . auth()->user()->profile_photo_path))): ?>
                                                    <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo_path)); ?>" 
                                                         alt="<?php echo e(auth()->user()->name); ?>" 
                                                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                                <?php else: ?>
                                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                        <?php echo e(auth()->user()->initials); ?>

                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="font-semibold text-gray-800"><?php echo e(auth()->user()->name); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo e(auth()->user()->email); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Menu Items -->
                                        <div class="py-1">
                                            <a href="<?php echo e(route('profile.show')); ?>" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-primary/5 hover:to-accent/5 hover:text-primary group transition-all duration-200">
                                                <i class="fas fa-user-circle w-5 text-gray-400 group-hover:text-primary transition-colors mr-3"></i>
                                                <span>Profile</span>
                                            </a>
                                            
                                            <a href="<?php echo e(route('orders.index')); ?>" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-primary/5 hover:to-accent/5 hover:text-primary group transition-all duration-200">
                                                <i class="fas fa-box w-5 text-gray-400 group-hover:text-primary transition-colors mr-3"></i>
                                                <span>Orders</span>
                                            </a>
                                        </div>
                                        
                                        <!-- Separator -->
                                        <div class="border-t border-gray-100 my-1"></div>
                                        
                                        <!-- Logout -->
                                        <div class="py-1">
                                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 group transition-all duration-200">
                                                    <i class="fas fa-sign-out-alt w-5 text-rose-500 group-hover:text-rose-600 transition-colors mr-3"></i>
                                                    <span>Sign Out</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Login & Signup Buttons for Guest Users -->
                            <div class="flex items-center space-x-2">
                                <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center px-4 py-2.5 border-2 border-gray-300 rounded-full text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:translate-y-[-1px] group">
                                    <i class="fas fa-sign-in-alt mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                                    <span>Login</span>
                                </a>
                                
                                <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-full text-sm font-medium shadow-lg hover:shadow-xl transform hover:translate-y-[-1px] transition-all duration-300 group whitespace-nowrap">
                                    <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                                    <span>Sign Up</span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Mobile User/Menu Section -->
                        <div class="md:hidden flex items-center space-x-3">
                            <?php if(auth()->guard()->check()): ?>
                                <!-- Mobile Cart -->
                                <a href="<?php echo e(route('cart')); ?>" class="relative p-2 text-gray-700 hover:text-primary transition-colors duration-300">
                                    <i class="fas fa-shopping-cart text-lg"></i>
                                    <!-- Visible mobile cart count -->
                                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('cart-count');

$__html = app('livewire')->mount($__name, $__params, 'lw-2402888153-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                </a>
                                
                                <!-- User Icon -->
                                <div class="p-2 text-gray-700">
                                    <i class="fas fa-user text-lg"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Mobile Menu Button -->
                            <div x-data="{ mobileMenuOpen: false }">
                            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="relative w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 hover:bg-gray-100 transition-colors">
                                <span class="sr-only">Open main menu</span>
                                <i class="fas" :class="{'fa-times text-primary': mobileMenuOpen, 'fa-bars text-gray-700': !mobileMenuOpen}"></i>
                            </button>
                            
                            <div x-show="mobileMenuOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-10"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-10"
                                 @click.away="mobileMenuOpen = false" class="fixed inset-0 z-40 transform" style="display: none;">
                                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
                                <nav class="relative bg-white border-b border-gray-200 h-screen overflow-y-auto pt-5 pb-20">
                                    <div class="px-4">
                                        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-6">
                                            <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-3">
                                                <img src="<?php echo e(asset('elandra-logo.webp')); ?>" alt="Elandra Logo" class="w-10 h-10 object-contain rounded-full border-2 border-gray-100 shadow-sm">
                                                <span class="text-xl font-bold text-gray-900">Elandra</span>
                                            </a>
                                            <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-primary">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="space-y-1 py-4">
                                            <a href="<?php echo e(route('home')); ?>" class="block py-3 px-4 text-base font-medium rounded-lg mx-2 <?php echo e(request()->routeIs('home') ? 'text-primary bg-gradient-to-r from-primary/10 to-accent/10' : 'text-gray-700 hover:bg-gray-50'); ?> transition-all duration-200">
                                                <i class="fas fa-home w-5 mr-3"></i>Home
                                            </a>
                                            <a href="<?php echo e(route('products.index')); ?>" class="block py-3 px-4 text-base font-medium rounded-lg mx-2 <?php echo e(request()->routeIs('products.index') ? 'text-primary bg-gradient-to-r from-primary/10 to-accent/10' : 'text-gray-700 hover:bg-gray-50'); ?> transition-all duration-200">
                                                <i class="fas fa-shopping-bag w-5 mr-3"></i>Products
                                            </a>
                                            <a href="#" class="block py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg mx-2 transition-all duration-200">
                                                <i class="fas fa-tags w-5 mr-3"></i>Categories
                                            </a>
                                            <a href="#" class="block py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg mx-2 transition-all duration-200">
                                                <i class="fas fa-info-circle w-5 mr-3"></i>About
                                            </a>
                                            <a href="#" class="block py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg mx-2 transition-all duration-200">
                                                <i class="fas fa-envelope w-5 mr-3"></i>Contact
                                            </a>
                                        </div>
                                        
                                        <div class="mt-6 pt-6 border-t border-gray-100">
                                            <?php if(auth()->guard()->check()): ?>
                                                <!-- User Greeting -->
                                                <div class="px-4 py-3 mb-4 bg-gradient-to-r from-primary/5 to-accent/5 rounded-xl">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-accent rounded-full flex items-center justify-center text-white font-bold text-lg">
                                                            <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-600">Hi there,</p>
                                                            <p class="text-base font-semibold text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Mobile Menu Items -->
                                                <div class="space-y-1">
                                                    <a href="<?php echo e(route('cart')); ?>" class="flex items-center justify-between py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200 group">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-shopping-cart w-5 text-primary mr-3"></i>
                                                            <span>Shopping Cart</span>
                                                        </div>
                                                        <!-- Visible mobile cart count -->
                                                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('cart-count', ['mobile' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2402888153-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                                    </a>
                                                    
                                                    <a href="<?php echo e(route('profile.show')); ?>" class="flex items-center py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                                        <i class="fas fa-user-circle w-5 text-primary mr-3"></i>
                                                        <span>Profile</span>
                                                    </a>
                                                    
                                                    <a href="<?php echo e(route('orders.index')); ?>" class="flex items-center py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                                        <i class="fas fa-box w-5 text-primary mr-3"></i>
                                                        <span>Orders</span>
                                                    </a>
                                                </div>
                                                
                                                <!-- Logout Button -->
                                                <div class="mt-4 pt-4 border-t border-gray-100">
                                                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="w-full flex items-center justify-center py-3 px-4 text-base font-medium text-white bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                                            <i class="fas fa-sign-out-alt mr-3"></i>
                                                            <span>Sign Out</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <!-- Guest User Buttons -->
                                                <div class="px-4 space-y-3">
                                                    <a href="<?php echo e(route('login')); ?>" class="flex items-center justify-center py-4 px-6 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:translate-y-[-1px] hover:scale-105 group">
                                                        <i class="fas fa-sign-in-alt mr-3 group-hover:scale-110 transition-transform duration-300 text-base"></i>
                                                        <span class="font-semibold">Login to Account</span>
                                                    </a>
                                                    
                                                    <a href="<?php echo e(route('register')); ?>" class="flex items-center justify-center py-4 px-6 bg-gradient-to-r from-blue-600 via-purple-600 to-blue-700 hover:from-blue-700 hover:via-purple-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-xl hover:shadow-2xl transform hover:translate-y-[-1px] hover:scale-105 transition-all duration-300 group">
                                                        <i class="fas fa-user-plus mr-3 group-hover:scale-110 transition-transform duration-300 text-base"></i>
                                                        <span class="font-semibold">Create Account</span>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Professional Footer -->
        <footer class="bg-gradient-to-br from-slate-900 via-gray-900 to-black text-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            
            <!-- Main Footer Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    
                    <!-- Company Info -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center space-x-4 mb-8">
                            <div class="relative">
                                <img src="<?php echo e(asset('elandra-logo.webp')); ?>" alt="Elandra Logo" class="w-16 h-16 object-contain rounded-full ring-4 ring-amber-500/20" onerror="this.style.display='none'">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-amber-500/20 to-orange-500/20 animate-pulse"></div>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-orange-500 to-red-500 bg-clip-text text-transparent">Elandra</h2>
                                <p class="text-amber-200 text-sm font-medium">Luxury Redefined</p>
                            </div>
                        </div>
                        <p class="text-gray-200 mb-8 max-w-md leading-relaxed text-lg">
                            Discover luxury handbags that blend timeless elegance with modern sophistication. 
                            Crafted for the discerning individual who values quality and style.
                        </p>
                        
                        <!-- Newsletter Signup -->
                        <div class="mb-8 p-6 bg-gradient-to-r from-gray-800/50 to-gray-700/50 rounded-xl border border-gray-700/50 backdrop-blur-sm">
                            <h4 class="font-bold mb-4 text-white text-lg flex items-center">
                                <i class="fas fa-envelope mr-3 text-amber-400"></i>Stay Updated
                            </h4>
                            <p class="text-gray-300 mb-4 text-sm">Get exclusive offers and latest collections delivered to your inbox</p>
                            <div class="flex max-w-md">
                                <input type="email" placeholder="Enter your email address" 
                                       class="flex-1 px-4 py-3 rounded-l-xl bg-white/10 text-white placeholder-gray-300 border border-gray-600 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-all">
                                <button class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-r-xl hover:from-amber-600 hover:to-orange-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-paper-plane mr-2"></i>Subscribe
                                </button>
                            </div>
                        </div>
                        
                        <!-- Social Media -->
                        <div>
                            <h4 class="font-bold mb-4 text-white text-lg flex items-center">
                                <i class="fas fa-share-alt mr-3 text-amber-400"></i>Follow Us
                            </h4>
                            <p class="text-gray-300 mb-4 text-sm">Join our community for style inspiration</p>
                            <div class="flex space-x-3">
                                <a href="#" class="group relative w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white flex items-center justify-center rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-3 shadow-lg">
                                    <i class="fab fa-facebook-f text-lg"></i>
                                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl blur opacity-0 group-hover:opacity-75 transition duration-300"></div>
                                </a>
                                <a href="#" class="group relative w-12 h-12 bg-gradient-to-br from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white flex items-center justify-center rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-3 shadow-lg">
                                    <i class="fab fa-twitter text-lg"></i>
                                    <div class="absolute -inset-1 bg-gradient-to-r from-sky-500 to-blue-600 rounded-xl blur opacity-0 group-hover:opacity-75 transition duration-300"></div>
                                </a>
                                <a href="#" class="group relative w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white flex items-center justify-center rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-3 shadow-lg">
                                    <i class="fab fa-instagram text-lg"></i>
                                    <div class="absolute -inset-1 bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl blur opacity-0 group-hover:opacity-75 transition duration-300"></div>
                                </a>
                                <a href="#" class="group relative w-12 h-12 bg-gradient-to-br from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white flex items-center justify-center rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-3 shadow-lg">
                                    <i class="fab fa-linkedin-in text-lg"></i>
                                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-700 to-indigo-800 rounded-xl blur opacity-0 group-hover:opacity-75 transition duration-300"></div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-xl font-bold mb-6 text-white flex items-center">
                            <i class="fas fa-shopping-bag mr-3 text-amber-400"></i>Shop
                        </h3>
                        <ul class="space-y-4">
                            <li><a href="<?php echo e(route('home')); ?>" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>Home</a></li>
                            <li><a href="<?php echo e(route('products.index')); ?>" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>All Products</a></li>
                            <li><a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>New Arrivals</a></li>
                            <li><a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>Sale Items</a></li>
                            <li><a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>Gift Cards</a></li>
                        </ul>
                    </div>
                    
                    <!-- Customer Care -->
                    <div>
                        <h3 class="text-xl font-bold mb-6 text-white flex items-center">
                            <i class="fas fa-headset mr-3 text-amber-400"></i>Customer Care
                        </h3>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>Contact Us</a></li>
                            <li><a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>Shipping Info</a></li>
                            <li><a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>Returns & Exchanges</a></li>
                            <li><a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>Size Guide</a></li>
                            <li><a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 flex items-center group text-base hover:translate-x-2">
                                <i class="fas fa-chevron-right text-xs mr-3 text-amber-400 group-hover:mr-4 transition-all duration-300"></i>FAQ</a></li>
                        </ul>
                    </div>
                    
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-600/50 bg-black/30 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
                    <div class="flex flex-col lg:flex-row justify-between items-center space-y-6 lg:space-y-0">
                        
                        <!-- Copyright -->
                        <div class="text-gray-200 text-base font-medium flex items-center">
                            <i class="fas fa-copyright mr-2 text-amber-400"></i>
                            <?php echo e(date('Y')); ?> Elandra. All rights reserved. | <span class="text-amber-400 ml-2">Luxury redefined for the modern world.</span>
                        </div>
                        
                        <!-- Legal Links -->
                        <div class="flex space-x-8 text-base">
                            <a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 hover:scale-105 font-medium">Privacy Policy</a>
                            <a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 hover:scale-105 font-medium">Terms of Service</a>
                            <a href="#" class="text-gray-200 hover:text-amber-400 transition-all duration-300 hover:scale-105 font-medium">Cookies Policy</a>
                        </div>
                        
                        <!-- Payment Methods -->
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-200 text-base font-medium">Secure Payments:</span>
                            <div class="flex space-x-3">
                                <div class="w-12 h-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg text-white text-sm flex items-center justify-center font-bold shadow-lg hover:scale-105 transition-transform">VISA</div>
                                <div class="w-12 h-8 bg-gradient-to-r from-red-600 to-red-700 rounded-lg text-white text-sm flex items-center justify-center font-bold shadow-lg hover:scale-105 transition-transform">MC</div>
                                <div class="w-12 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white text-sm flex items-center justify-center font-bold shadow-lg hover:scale-105 transition-transform">AMEX</div>
                                <div class="w-12 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg text-white text-xs flex items-center justify-center font-bold shadow-lg hover:scale-105 transition-transform">PAY</div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- AOS - Animate On Scroll Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: false,
            mirror: true,
            offset: 120,
            delay: 100
        });
        
        // Simple scroll effect for navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('nav');
            if (window.scrollY > 0) {
                navbar.classList.add('shadow-lg');
                navbar.classList.add('bg-white/95');
                navbar.classList.add('backdrop-blur-sm');
            } else {
                navbar.classList.remove('shadow-lg');
                navbar.classList.remove('bg-white/95');
                navbar.classList.remove('backdrop-blur-sm');
            }
        });
    </script>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/layouts/frontend.blade.php ENDPATH**/ ?>