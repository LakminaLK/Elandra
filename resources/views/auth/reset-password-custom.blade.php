<!DOCTYPE html>
<html lang="en" class="bg-gradient-to-br from-blue-50 to-indigo-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Set New Password - {{ config('app.name', 'Elandra') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('elandra-logo.webp') }}">
    <link rel="shortcut icon" href="{{ asset('elandra-logo.webp') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            font-family: 'Inter', sans-serif;
            height: 100%;
            background: linear-gradient(135deg, rgb(219 234 254) 0%, rgb(199 210 254) 100%);
        }
        
        .fashion-overlay {
            background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 50%, rgba(255,255,255,0.1) 100%);
        }
        
        .reset-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        
        .form-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(229, 231, 235, 0.6);
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            background: rgba(255, 255, 255, 1);
            border: 2px solid #4F46E5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }
        
        .reset-btn {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }
        
        .reset-btn:hover {
            background: linear-gradient(135deg, #4338CA 0%, #6D28D9 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4);
        }
        
        .reset-btn:active {
            transform: translateY(0);
        }
        
        .brand-text {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        body {
            overflow-y: auto;
        }
        
        .scrollable-container {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .form-wrapper {
            width: 100%;
            max-width: 650px;
            margin: 20px auto;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 60px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100">

    <!-- Scrollable Container -->
    <div class="scrollable-container">
        <div class="form-wrapper">
            <div class="form-card">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center">
                        <img 
                            src="{{ asset('elandra-logo.webp') }}" 
                            alt="Elandra Logo" 
                            class="w-10 h-10 rounded-full"
                            onerror="this.style.display='none'"
                        >
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 font-['Playfair_Display']">Elandra</h1>
                </div>
                <p class="text-gray-500 text-sm">Luxury Handbags</p>
            </div>
            
            <!-- Reset Card -->
            <div class="reset-card rounded-2xl p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 font-['Playfair_Display']">Set New Password</h2>
                    <p class="text-gray-500 text-sm">Create a strong password for {{ $email }}</p>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="text-red-600 text-sm">
                            <div class="font-medium mb-1">Please fix the following errors:</div>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.update.custom') }}" class="space-y-6" x-data="passwordResetValidator()">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- New Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                id="password"
                                name="password"
                                x-model="password"
                                @input="validatePassword()"
                                placeholder="Create a new password"
                                :class="passwordFieldClass"
                                class="w-full px-4 py-3 pr-12 rounded-lg text-gray-700 placeholder-gray-400" 
                                autocomplete="new-password" 
                                required
                                autofocus
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute right-4 top-1/3 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center"
                            >
                                <i class="fas text-lg" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicators -->
                        <div class="mt-3 space-y-2 text-sm">
                            <div class="flex items-center space-x-2">
                                <div :class="checks.length ? 'text-green-500' : 'text-gray-400'" class="w-5 h-5 flex items-center justify-center">
                                    <svg x-show="checks.length" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <svg x-show="!checks.length" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                </div>
                                <span :class="checks.length ? 'text-green-600' : 'text-gray-600'">At least 8 characters</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <div :class="checks.uppercase ? 'text-green-500' : 'text-gray-400'" class="w-5 h-5 flex items-center justify-center">
                                    <svg x-show="checks.uppercase" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <svg x-show="!checks.uppercase" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                </div>
                                <span :class="checks.uppercase ? 'text-green-600' : 'text-gray-600'">At least 1 uppercase letter</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <div :class="checks.lowercase ? 'text-green-500' : 'text-gray-400'" class="w-5 h-5 flex items-center justify-center">
                                    <svg x-show="checks.lowercase" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <svg x-show="!checks.lowercase" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                </div>
                                <span :class="checks.lowercase ? 'text-green-600' : 'text-gray-600'">At least 1 lowercase letter</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <div :class="checks.number ? 'text-green-500' : 'text-gray-400'" class="w-5 h-5 flex items-center justify-center">
                                    <svg x-show="checks.number" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <svg x-show="!checks.number" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                </div>
                                <span :class="checks.number ? 'text-green-600' : 'text-gray-600'">At least 1 number</span>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                id="password_confirmation"
                                name="password_confirmation"
                                x-model="confirmPassword"
                                @input="validateConfirmPassword()"
                                placeholder="Confirm your new password"
                                :class="confirmPasswordFieldClass"
                                class="w-full px-4 py-3 pr-12 rounded-lg text-gray-700 placeholder-gray-400" 
                                autocomplete="new-password" 
                                required
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute right-4 top-1/3 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center"
                            >
                                <i class="fas text-lg" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <div x-show="confirmPasswordError" x-text="confirmPasswordError" class="text-red-500 text-sm"></div>
                        <div x-show="confirmPassword && passwordsMatch && !confirmPasswordError" class="text-green-500 text-sm">
                            <i class="fas fa-check-circle mr-1"></i>Passwords match
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <button 
                        type="submit"
                        class="w-full py-3 px-6 reset-btn text-white rounded-lg font-semibold text-base"
                        :disabled="!formValid"
                        :class="!formValid ? 'opacity-50 cursor-not-allowed' : ''"
                    >
                        <i class="fas fa-shield-alt mr-2"></i>
                        Update Password
                    </button>

                    <!-- Security Notice -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center text-blue-800 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Your new password cannot be the same as your current password for security reasons.</span>
                        </div>
                    </div>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>

    <!-- Toast Notifications -->
    @if (session('success') || session('error'))
    <div 
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 6000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:leave="transition ease-in duration-300"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md px-4"
        style="z-index: 9999;"
    >
        @if (session('success'))
            <div class="bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg flex items-center justify-between gap-4 min-w-80">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-white hover:bg-green-700 rounded-lg px-2 py-1 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @elseif (session('error'))
            <div class="bg-red-600 text-white px-6 py-4 rounded-xl shadow-lg flex items-center justify-between gap-4 min-w-80">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-white hover:bg-red-700 rounded-lg px-2 py-1 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
    @endif

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function passwordResetValidator() {
            return {
                password: '',
                confirmPassword: '',
                confirmPasswordError: '',
                checks: {
                    length: false,
                    uppercase: false,
                    lowercase: false,
                    number: false
                },

                init() {
                    // Initialize validation
                },

                get passwordFieldClass() {
                    if (!this.password) return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                    const allValid = this.checks.length && this.checks.uppercase && this.checks.lowercase && this.checks.number;
                    if (allValid) return 'form-input border-green-500 focus:border-green-500 focus:ring-green-500';
                    return 'form-input border-orange-300 focus:border-orange-500 focus:ring-orange-500';
                },

                get confirmPasswordFieldClass() {
                    if (!this.confirmPassword) return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                    if (this.confirmPasswordError) return 'form-input border-red-500 focus:border-red-500 focus:ring-red-500';
                    if (this.confirmPassword && this.passwordsMatch && !this.confirmPasswordError) return 'form-input border-green-500 focus:border-green-500 focus:ring-green-500';
                    return 'form-input border-red-500 focus:border-red-500 focus:ring-red-500';
                },

                get passwordsMatch() {
                    if (!this.confirmPassword || !this.password) return false;
                    return this.confirmPassword === this.password;
                },

                get formValid() {
                    return this.checks.length && 
                           this.checks.uppercase && 
                           this.checks.lowercase && 
                           this.checks.number && 
                           this.passwordsMatch && 
                           !this.confirmPasswordError;
                },

                validatePassword() {
                    this.checks.length = this.password.length >= 8;
                    this.checks.uppercase = /[A-Z]/.test(this.password);
                    this.checks.lowercase = /[a-z]/.test(this.password);
                    this.checks.number = /[0-9]/.test(this.password);
                    
                    // Revalidate confirm password when main password changes
                    if (this.confirmPassword) {
                        this.validateConfirmPassword();
                    }
                },

                validateConfirmPassword() {
                    if (!this.confirmPassword || this.confirmPassword.trim() === '') {
                        this.confirmPasswordError = '';
                        return;
                    }

                    if (this.confirmPassword !== this.password) {
                        this.confirmPasswordError = 'Passwords do not match';
                    } else {
                        this.confirmPasswordError = '';
                    }
                }
            }
        }
    </script>
        </div>
    </div>
</body>
</html>