<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password - Admin | {{ config('app.name', 'Elandra') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('elandra-logo.webp') }}">
    <link rel="shortcut icon" type="image/webp" href="{{ asset('elandra-logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('elandra-logo.webp') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite CSS Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        .password-strength {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
        }

        .strength-weak { background: #ef4444; width: 25%; }
        .strength-fair { background: #f59e0b; width: 50%; }
        .strength-good { background: #10b981; width: 75%; }
        .strength-strong { background: #059669; width: 100%; }
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
                <div class="mx-auto h-16 w-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center pulse-border mb-6">
                    <span class="text-white font-bold text-2xl">E</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Your Password</h2>
                <p class="text-gray-600">Enter your new password below</p>
            </div>

            <!-- Reset Password Form -->
            <div class="login-animation bg-white rounded-2xl shadow-xl p-8" style="animation-delay: 0.2s;">
                @if (session('status'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 animate-fade-in">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-medium">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 animate-fade-in">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                @foreach ($errors->all() as $error)
                                    <p class="text-sm text-red-700">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <!-- Email Field (read-only) -->
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
                            value="{{ request()->email ?? old('email') }}"
                            readonly
                            class="form-input appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 bg-gray-50 text-gray-700 sm:text-sm"
                        >
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-400"></i>New Password
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="new-password" 
                                required 
                                minlength="8"
                                class="form-input appearance-none rounded-lg relative block w-full px-4 py-3 pr-12 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-300"
                                placeholder="Enter your new password"
                            >
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors" id="eyeIcon"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Password must be at least 8 characters long and include letters, numbers, and special characters.
                        </p>
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-400"></i>Confirm New Password
                        </label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                autocomplete="new-password" 
                                required 
                                minlength="8"
                                class="form-input appearance-none rounded-lg relative block w-full px-4 py-3 pr-12 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-300"
                                placeholder="Confirm your new password"
                            >
                            <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors" id="confirmEyeIcon"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="mt-2 text-xs hidden">
                            <span id="matchText"></span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="btn-hover w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span class="flex items-center">
                                <svg class="hidden loading-spinner -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <i class="fas fa-key mr-2"></i>
                                <span class="btn-text">Reset Password</span>
                            </span>
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="text-center">
                        <a href="{{ route('admin.login') }}" class="text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500 login-animation" style="animation-delay: 0.4s;">
                © {{ date('Y') }} Elandra. All rights reserved.
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        document.getElementById('togglePassword').addEventListener('click', function() {
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

        // Confirm password visibility toggle
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const password = document.getElementById('password_confirmation');
            const eyeIcon = document.getElementById('confirmEyeIcon');
            
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

        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('strengthBar');
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            
            switch (strength) {
                case 1:
                    strengthBar.classList.add('strength-weak');
                    break;
                case 2:
                    strengthBar.classList.add('strength-fair');
                    break;
                case 3:
                    strengthBar.classList.add('strength-good');
                    break;
                case 4:
                    strengthBar.classList.add('strength-strong');
                    break;
                default:
                    strengthBar.style.width = '0%';
            }
            
            return strength;
        }

        // Password match checker
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('passwordMatch');
            const matchText = document.getElementById('matchText');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirmPassword.length > 0) {
                matchDiv.classList.remove('hidden');
                
                if (password === confirmPassword) {
                    matchText.textContent = '✓ Passwords match';
                    matchText.className = 'text-green-600';
                    submitBtn.disabled = false;
                } else {
                    matchText.textContent = '✗ Passwords do not match';
                    matchText.className = 'text-red-600';
                    submitBtn.disabled = true;
                }
            } else {
                matchDiv.classList.add('hidden');
                submitBtn.disabled = false;
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const spinner = this.querySelector('.loading-spinner');
            const btnText = this.querySelector('.btn-text');
            const button = this.querySelector('button[type="submit"]');
            
            if (spinner && btnText && button) {
                spinner.classList.remove('hidden');
                btnText.textContent = 'Resetting...';
                button.disabled = true;
                button.classList.add('opacity-75', 'cursor-not-allowed');
            }
        });

        // Auto-focus password field
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            if (passwordField) {
                passwordField.focus();
            }
        });
    </script>
</body>
</html>