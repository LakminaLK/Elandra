<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Admin Login - <?php echo e(config('app.name', 'Elandra')); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="<?php echo e(asset('elandra-logo.webp')); ?>">
    <link rel="shortcut icon" type="image/webp" href="<?php echo e(asset('elandra-logo.webp')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('elandra-logo.webp')); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite CSS Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        body { 
            background: linear-gradient(135deg, #f1f5f9 0%, #dbeafe 50%, #e0e7ff 100%);
            font-family: 'Inter', sans-serif;
        }
        
        .floating-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape-1 {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            background: linear-gradient(45deg, #10b981, #3b82f6);
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #f59e0b, #ef4444);
            bottom: 20%;
            left: 60%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-animation {
            animation: slideInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-border {
            animation: pulseBorder 2s infinite;
        }

        @keyframes pulseBorder {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }

        .btn-hover {
            transition: all 0.3s ease;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            transform: scale(1.02);
        }

        .input-focus-glow:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 0 20px rgba(59, 130, 246, 0.2);
        }

        .checkbox-bounce:checked {
            animation: checkboxBounce 0.3s ease;
        }

        @keyframes checkboxBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .loading-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
        
        
</head>
<body style="background: linear-gradient(135deg, #f1f5f9 0%, #dbeafe 50%, #e0e7ff 100%); min-height: 100vh; margin: 0; padding: 0; font-family: 'Inter', sans-serif;">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="login-animation text-center">
                <div class="mx-auto h-16 w-16 bg-white rounded-2xl flex items-center justify-center pulse-border mb-6 shadow-lg">
                    <img src="<?php echo e(asset('elandra-logo.webp')); ?>" alt="Elandra Logo" class="h-12 w-12 object-contain">
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-600">Sign in to your admin account</p>
            </div>

            <!-- Login Form -->
            <div class="login-animation bg-white rounded-2xl shadow-xl p-8" style="animation-delay: 0.2s;">
                <?php if(session('success')): ?>
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 fade-in">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-green-700"><?php echo e(session('success')); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if($errors->any()): ?>
                    <?php
                        $isLockout = false;
                        $lockoutMessage = '';
                        foreach ($errors->all() as $error) {
                            if (str_contains($error, 'locked') || str_contains($error, 'attempt')) {
                                $isLockout = true;
                                $lockoutMessage = $error;
                                break;
                            }
                        }
                    ?>
                    
                    <div class="mb-6 <?php echo e($isLockout ? 'bg-orange-50 border-orange-200' : 'bg-red-50 border-red-200'); ?> border rounded-lg p-4 fade-in">
                        <div class="flex">
                            <?php if($isLockout): ?>
                                <svg class="w-5 h-5 text-orange-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php endif; ?>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium <?php echo e($isLockout ? 'text-orange-800' : 'text-red-800'); ?>">
                                    <?php echo e($isLockout ? 'Account Locked' : 'Authentication Failed'); ?>

                                </h3>
                                <div class="mt-1 text-sm <?php echo e($isLockout ? 'text-orange-700' : 'text-red-700'); ?>">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p><?php echo e($error); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php if($isLockout): ?>
                                    <div class="mt-2 text-xs text-orange-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        This is a security measure to protect admin accounts from brute-force attacks.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('admin.login.post')); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="<?php echo e(old('email')); ?>"
                            class="form-input input-focus-glow appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-300"
                            placeholder="Enter your admin email address"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="current-password" 
                                required 
                                class="form-input input-focus-glow appearance-none rounded-lg relative block w-full px-4 py-3 pr-12 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-300"
                                placeholder="Enter your password"
                            >
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember" 
                                name="remember" 
                                type="checkbox" 
                                class="checkbox-bounce h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all duration-200"
                            >
                            <label for="remember" class="ml-2 block text-sm text-gray-700 transition-colors duration-200 hover:text-gray-900">
                                Remember me
                            </label>
                        </div>
                        <a href="<?php echo e(route('admin.password.request')); ?>" class="text-sm text-blue-600 hover:text-blue-700 transition-all duration-200 font-medium hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="btn-hover w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <span class="flex items-center">
                                <svg class="hidden loading-spinner -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                <span class="btn-text">Sign In</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500 login-animation" style="animation-delay: 0.4s;">
                © <?php echo e(date('Y')); ?> Elandra. All rights reserved.
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const spinner = this.querySelector('.loading-spinner');
            const btnText = this.querySelector('.btn-text');
            const button = this.querySelector('button[type="submit"]');
            
            if (spinner && btnText && button) {
                spinner.classList.remove('hidden');
                btnText.textContent = 'Signing in...';
                button.disabled = true;
                button.classList.add('opacity-75', 'cursor-not-allowed');
            }
        });

        // Enhanced input focus animations
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.add('ring-4', 'ring-blue-100');
            });
            
            input.addEventListener('blur', function() {
                this.classList.remove('ring-4', 'ring-blue-100');
            });
        });

        // Auto-focus email field
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.focus();
            }
            
            // Add staggered animation to form elements
            const formElements = document.querySelectorAll('.form-input, .checkbox-bounce, button[type="submit"]');
            formElements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 + index * 0.1}s`;
                element.classList.add('fade-in');
            });
        });

        // Checkbox bounce animation
        document.getElementById('remember')?.addEventListener('change', function() {
            if (this.checked) {
                this.classList.add('checkbox-bounce');
                setTimeout(() => {
                    this.classList.remove('checkbox-bounce');
                }, 300);
            }
        });
    </script>

    <?php if($errors->any()): ?>
        <?php
            $lockoutError = null;
            $generalErrors = [];
            foreach ($errors->all() as $error) {
                if (str_contains($error, 'locked') || str_contains($error, 'temporarily locked')) {
                    $lockoutError = $error;
                } else {
                    $generalErrors[] = $error;
                }
            }
        ?>
        
        <!-- Keep the inline error display but remove toast -->
        <?php if($lockoutError): ?>
            <div class="mb-6 bg-orange-50 border border-orange-200 rounded-lg p-4 fade-in">
                <div class="flex">
                    <svg class="w-5 h-5 text-orange-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-orange-800">Account Locked</h3>
                        <div class="mt-1 text-sm text-orange-700">
                            <p><?php echo e($lockoutError); ?></p>
                        </div>
                        <div class="mt-2 text-xs text-orange-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            This is a security measure to protect admin accounts from brute-force attacks.
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(!empty($generalErrors)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 fade-in">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Authentication Failed</h3>
                        <div class="mt-1 text-sm text-red-700">
                            <?php $__currentLoopData = $generalErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($error); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>