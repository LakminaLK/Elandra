<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name', 'Elandra') }}</title>
    
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
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Smooth entrance animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeInUp {
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
        
        @keyframes pulseGlow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(79, 70, 229, 0.3);
            }
            50% {
                box-shadow: 0 0 30px rgba(79, 70, 229, 0.5);
            }
        }
        
        .animate-slide-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        .animate-slide-right {
            animation: slideInRight 0.8s ease-out;
        }
        
        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.5s ease-out;
        }
        
        .animate-delay-200 {
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }
        
        .animate-delay-400 {
            animation-delay: 0.4s;
            animation-fill-mode: both;
        }
        
        .animate-delay-600 {
            animation-delay: 0.6s;
            animation-fill-mode: both;
        }
        
        .fashion-overlay {
            background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 50%, rgba(255,255,255,0.1) 100%);
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        
        .register-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.15);
        }
        
        .form-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(229, 231, 235, 0.6);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
        }
        
        .form-input:focus {
            background: rgba(255, 255, 255, 1);
            border: 2px solid #4F46E5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1), 0 8px 25px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
            outline: none;
        }
        
        .form-input:hover {
            border-color: #A855F7;
            transform: translateY(-1px);
        }
        
        .register-btn {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            transform: translateY(0);
            position: relative;
            overflow: hidden;
        }
        
        .register-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        
        .register-btn:hover::before {
            left: 100%;
        }
        
        .register-btn:hover {
            background: linear-gradient(135deg, #4338CA 0%, #6D28D9 100%);
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.4);
            animation: pulseGlow 2s infinite;
        }
        
        .register-btn:active {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
        }
        
        .brand-text {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        /* Smooth link hover effects */
        a {
            transition: all 0.3s ease;
        }
        
        /* International telephone input styles */
        .iti { 
            width: 100%; 
        }
        
        .iti__country-list { 
            max-height: 200px; 
            overflow-y: auto; 
            z-index: 9999; 
        }
        
        .iti__selected-flag {
            background: rgba(255, 255, 255, 0.9);
            border-right: 1px solid rgba(229, 231, 235, 0.6);
        }
    </style>
    
    <!-- International telephone input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
</head>
<body class="h-screen flex overflow-hidden">

    <!-- Left side - Fashion Image (Fixed) -->
    <div class="hidden lg:block lg:w-3/5 overflow-hidden fixed left-0 top-0 h-screen animate-slide-left">
        <!-- Elandra Handbag Model -->
        <img 
            src="{{ asset('images/ss.webp') }}" 
            alt="Elandra Model with Luxury Handbag" 
            class="w-full h-full object-cover object-center"
        >
        
        <!-- Elegant Overlay -->
        <div class="absolute inset-0 fashion-overlay"></div>
        
        <!-- Brand Content Overlay -->
        <div class="absolute inset-0 flex flex-col justify-between p-8">
            <!-- Logo Section -->
            <div class="flex items-center space-x-3 animate-fade-up animate-delay-200">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <img 
                        src="{{ asset('elandra-logo.webp') }}" 
                        alt="Elandra Logo" 
                        class="w-8 h-8 rounded-full"
                        onerror="this.style.display='none'"
                    >
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white font-['Playfair_Display'] brand-text">Elandra</h1>
                    <p class="text-white/90 text-xs font-light">Luxury Handbags</p>
                </div>
            </div>
            
            <!-- Bottom Quote -->
            <div class="text-white animate-fade-up animate-delay-400">
                <blockquote class="text-lg font-light leading-relaxed mb-3 italic brand-text">
                    "Every handbag tells a story. Let us help you write yours with elegance and style."
                </blockquote>
                <p class="text-white/80 text-sm font-medium">– Elandra Collection</p>
            </div>
        </div>
    </div>

    <!-- Right side - Register Form (Scrollable) -->
    <div class="w-full lg:w-2/5 lg:ml-auto bg-white overflow-y-auto min-h-screen px-8 py-12 relative animate-slide-right">
        <div class="w-full max-w-sm mx-auto">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-6 animate-scale-in">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center">
                        <img 
                            src="{{ asset('elandra-logo.webp') }}" 
                            alt="Elandra Logo" 
                            class="w-8 h-8 rounded-full"
                            onerror="this.style.display='none'"
                        >
                    </div>
                    <h1 class="text-xl font-bold text-gray-800 font-['Playfair_Display']">Elandra</h1>
                </div>
            </div>
            
            <!-- Register Card -->
            <div class="register-card rounded-2xl p-8 animate-fade-up animate-delay-200">
                <!-- Header -->
                <div class="text-center mb-8 animate-fade-up animate-delay-400">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 font-['Playfair_Display']">Join Elandra!</h2>
                    <p class="text-gray-500 text-sm">Create your account to explore luxury handbags</p>
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

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name" 
                            class="w-full px-4 py-3 form-input rounded-lg text-gray-700 placeholder-gray-400" 
                            autocomplete="name" 
                            required
                            autofocus
                        >
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2" x-data="emailValidator()">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email"
                            x-model="email"
                            @input="debounceEmailCheck()"
                            @blur="checkEmail()"
                            value="{{ old('email') }}"
                            placeholder="Enter your email" 
                            :class="emailFieldClass"
                            class="w-full px-4 py-3 rounded-lg text-gray-700 placeholder-gray-400" 
                            autocomplete="username" 
                            required
                        >
                        <div x-show="emailError" x-text="emailError" class="text-red-500 text-sm mt-1"></div>
                        <div x-show="isChecking" class="text-blue-500 text-sm mt-1">Checking email...</div>
                    </div>

                    <!-- Mobile Number Field -->
                    <div class="space-y-2" x-data="mobileValidator()">
                        <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                        <input 
                            type="tel" 
                            id="mobile"
                            name="mobile_display"
                            @input="debounceMobileCheck()"
                            @blur="validateMobile()"
                            placeholder="Enter your mobile number" 
                            :class="mobileFieldClass"
                            class="w-full px-4 py-3 rounded-lg text-gray-700 placeholder-gray-400" 
                            autocomplete="tel" 
                            required
                        >
                        <input type="hidden" name="mobile" id="full_mobile" required />
                        <div x-show="mobileError" x-text="mobileError" class="text-red-500 text-sm mt-1"></div>
                        <div x-show="isChecking" class="text-blue-500 text-sm mt-1">Checking mobile...</div>
                        @error('mobile')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2" x-data="passwordValidator()">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                id="password"
                                name="password"
                                x-model="password"
                                @input="validatePassword()"
                                placeholder="Create a password"
                                :class="passwordFieldClass"
                                class="w-full px-4 py-3 pr-12 rounded-lg text-gray-700 placeholder-gray-400" 
                                autocomplete="new-password" 
                                required
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute right-4 top-1/3 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
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
                    <div class="space-y-2" x-data="confirmPasswordValidator()">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                id="password_confirmation"
                                name="password_confirmation"
                                x-model="confirmPassword"
                                @input="validateConfirmPassword()"
                                placeholder="Confirm your password"
                                :class="confirmPasswordFieldClass"
                                class="w-full px-4 py-3 pr-12 rounded-lg text-gray-700 placeholder-gray-400" 
                                autocomplete="new-password" 
                                required
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute right-4 top-1/3 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i class="fas text-lg" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <div x-show="confirmPasswordError" x-text="confirmPasswordError" class="text-red-500 text-sm"></div>
                        <div x-show="confirmPassword && passwordsMatch && !confirmPasswordError" class="text-green-500 text-sm">
                            Passwords match
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <!-- Terms and Privacy -->
                    <div class="space-y-2">
                        <label class="flex items-start space-x-3">
                            <input type="checkbox" name="terms" id="terms" required class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1">
                            <span class="text-sm text-gray-600 leading-relaxed">
                                I agree to the 
                                <a target="_blank" href="{{ route('terms.show') }}" class="text-indigo-600 hover:text-indigo-800 underline">Terms of Service</a>
                                and 
                                <a target="_blank" href="{{ route('policy.show') }}" class="text-indigo-600 hover:text-indigo-800 underline">Privacy Policy</a>
                            </span>
                        </label>
                    </div>
                    @endif

                    <!-- Register Button -->
                    <button 
                        type="submit"
                        class="w-full py-3 px-6 register-btn text-white rounded-lg font-semibold text-base"
                    >
                        <i class="fas fa-user-plus mr-2"></i>
                        Create Account
                    </button>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-600 text-sm">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors font-medium">
                                Sign In
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Back to Home -->
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if (session('success') || session('error'))
    <div 
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:leave="transition ease-in duration-300"
        class="fixed top-6 right-6 z-50"
    >
        @if (session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center gap-3 max-w-md">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @elseif (session('error'))
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center gap-3 max-w-md">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
    @endif

    <!-- OTP Modal -->
    <div 
        x-data="{ showOtpModal: @json(session('showOtpModal', false)) }"
        x-show="showOtpModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Verify Your Email</h2>
                <p class="text-gray-600">We've sent a 6-digit verification code to</p>
                <p class="text-indigo-600 font-semibold">{{ session('pending_email') }}</p>
            </div>
            
            <form method="POST" action="{{ route('verify.otp') }}" x-data="otpValidator()">
                @csrf
                <input type="hidden" name="email" value="{{ session('pending_email') }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                    <input 
                        type="text" 
                        name="otp" 
                        x-model="otp"
                        @input="formatOTP()"
                        placeholder="Enter 6-digit code" 
                        maxlength="6"
                        class="w-full px-4 py-3 text-center text-2xl font-mono tracking-widest border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                        required
                        autocomplete="off"
                    >
                </div>
                
                @if(session('otp_error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center text-red-700">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ session('otp_error') }}
                        </div>
                    </div>
                @endif
                
                <button 
                    type="submit"
                    :disabled="otp.length !== 6"
                    :class="otp.length === 6 ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-400 cursor-not-allowed'"
                    class="w-full py-3 px-6 text-white rounded-lg font-semibold text-base transition-all duration-300"
                >
                    <i class="fas fa-check-circle mr-2"></i>
                    Verify Code
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm mb-2">Didn't receive the code?</p>
                <form method="POST" action="{{ route('resend.otp') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('pending_email') }}">
                    <button 
                        type="submit" 
                        class="text-indigo-600 hover:text-indigo-800 transition-colors font-medium text-sm"
                    >
                        Resend Code
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- International telephone input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
    
    <!-- Alpine.js -->
        <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // Ensure Alpine.js is ready
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized successfully');
        });
    </script>
</body>
    
    <script>
        // Global variables for validation
        window.currentPassword = '';
        window.itiInstance = null;
        
        // Alpine.js validation functions
        function emailValidator() {
            return {
                email: '{{ old("email") }}',
                emailError: '',
                emailTimeout: null,
                isChecking: false,

                init() {
                    // Check initial value if exists
                    if (this.email) {
                        this.debounceEmailCheck();
                    }
                },

                get emailFieldClass() {
                    if (!this.email) return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                    if (this.isChecking) return 'form-input border-blue-300 focus:border-blue-500 focus:ring-blue-500';
                    if (this.emailError) return 'form-input border-red-500 focus:border-red-500 focus:ring-red-500';
                    if (this.email && !this.emailError) return 'form-input border-green-500 focus:border-green-500 focus:ring-green-500';
                    return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                },

                debounceEmailCheck() {
                    clearTimeout(this.emailTimeout);
                    this.emailTimeout = setTimeout(() => {
                        this.checkEmail();
                    }, 800);
                },

                async checkEmail() {
                    if (!this.email || this.email.trim() === '') {
                        this.emailError = '';
                        this.isChecking = false;
                        return;
                    }

                    // Basic email format validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(this.email)) {
                        this.emailError = 'Please enter a valid email address';
                        this.isChecking = false;
                        return;
                    }

                    this.isChecking = true;
                    this.emailError = '';

                    try {
                        const response = await fetch('/api/check-email', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ email: this.email.trim() })
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        
                        if (data.exists) {
                            this.emailError = 'This email is already registered';
                        } else {
                            this.emailError = '';
                        }
                    } catch (error) {
                        console.error('Email check error:', error);
                        // Don't show error to user for network issues
                        this.emailError = '';
                    } finally {
                        this.isChecking = false;
                    }
                }
            }
        }

        function mobileValidator() {
            return {
                mobileError: '',
                mobileValid: false,
                mobileTimeout: null,
                isChecking: false,

                get mobileFieldClass() {
                    const mobileInput = document.getElementById('mobile');
                    if (!mobileInput || !mobileInput.value) return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                    if (this.isChecking) return 'form-input border-blue-300 focus:border-blue-500 focus:ring-blue-500';
                    if (this.mobileError) return 'form-input border-red-500 focus:border-red-500 focus:ring-red-500';
                    if (this.mobileValid && !this.mobileError) return 'form-input border-green-500 focus:border-green-500 focus:ring-green-500';
                    return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                },

                debounceMobileCheck() {
                    clearTimeout(this.mobileTimeout);
                    this.mobileTimeout = setTimeout(() => {
                        this.validateMobile();
                    }, 1000);
                },

                async validateMobile() {
                    const mobileInput = document.getElementById('mobile');
                    const fullMobileInput = document.getElementById('full_mobile');
                    
                    if (!mobileInput || !mobileInput.value || mobileInput.value.trim() === '') {
                        this.mobileError = '';
                        this.mobileValid = false;
                        this.isChecking = false;
                        if (fullMobileInput) fullMobileInput.value = '';
                        return;
                    }

                    // Clean up input - only allow numbers
                    const numbersOnly = mobileInput.value.replace(/[^0-9]/g, '');
                    if (mobileInput.value !== numbersOnly) {
                        mobileInput.value = numbersOnly;
                    }

                    // Wait for ITI to be ready
                    if (!window.itiInstance) {
                        this.mobileError = 'Phone input not ready';
                        this.mobileValid = false;
                        return;
                    }

                    // Update hidden field immediately
                    try {
                        const fullNumber = window.itiInstance.getNumber();
                        if (fullMobileInput) {
                            fullMobileInput.value = fullNumber;
                        }
                    } catch (error) {
                        console.warn('Could not get full number:', error);
                    }

                    // Check if number is valid format
                    if (!window.itiInstance.isValidNumber()) {
                        this.mobileError = 'Please enter a valid mobile number';
                        this.mobileValid = false;
                        this.isChecking = false;
                        return;
                    }

                    this.isChecking = true;
                    this.mobileError = '';

                    try {
                        const fullNumber = window.itiInstance.getNumber();
                        const response = await fetch('/api/check-mobile', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ mobile: fullNumber })
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        
                        if (data.exists) {
                            this.mobileError = 'This mobile number is already registered';
                            this.mobileValid = false;
                        } else {
                            this.mobileError = '';
                            this.mobileValid = true;
                        }
                    } catch (error) {
                        console.error('Mobile check error:', error);
                        // Don't show error to user for network issues
                        this.mobileError = '';
                        this.mobileValid = false;
                    } finally {
                        this.isChecking = false;
                    }
                }
            }
        }

        function passwordValidator() {
            return {
                password: '',
                checks: {
                    length: false,
                    uppercase: false,
                    lowercase: false,
                    number: false
                },

                init() {
                    // Initialize with any existing password
                    if (this.password) {
                        this.validatePassword();
                    }
                },

                get passwordFieldClass() {
                    if (!this.password) return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                    const allValid = this.checks.length && this.checks.uppercase && this.checks.lowercase && this.checks.number;
                    if (allValid) return 'form-input border-green-500 focus:border-green-500 focus:ring-green-500';
                    return 'form-input border-orange-300 focus:border-orange-500 focus:ring-orange-500';
                },

                validatePassword() {
                    // Real-time validation as user types
                    this.checks.length = this.password.length >= 8;
                    this.checks.uppercase = /[A-Z]/.test(this.password);
                    this.checks.lowercase = /[a-z]/.test(this.password);
                    this.checks.number = /[0-9]/.test(this.password);
                    
                    // Update global password for confirm password validation
                    window.currentPassword = this.password;
                    
                    // Force reactivity update
                    this.$nextTick(() => {
                        // Trigger confirm password validation if it exists
                        if (window.confirmPasswordValidator) {
                            window.confirmPasswordValidator.validateConfirmPassword();
                        }
                    });
                }
            }
        }

        function confirmPasswordValidator() {
            return {
                confirmPassword: '',
                confirmPasswordError: '',

                init() {
                    // Store reference globally for password validator
                    window.confirmPasswordValidator = this;
                    
                    // Initialize validation if there's already a value
                    if (this.confirmPassword) {
                        this.validateConfirmPassword();
                    }
                },
                
                get passwordsMatch() {
                    if (!this.confirmPassword || !window.currentPassword) return false;
                    return this.confirmPassword === window.currentPassword;
                },

                get confirmPasswordFieldClass() {
                    if (!this.confirmPassword) return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                    if (this.confirmPasswordError) return 'form-input border-red-500 focus:border-red-500 focus:ring-red-500';
                    if (this.confirmPassword && this.passwordsMatch && !this.confirmPasswordError) return 'form-input border-green-500 focus:border-green-500 focus:ring-green-500';
                    return 'form-input border-red-500 focus:border-red-500 focus:ring-red-500';
                },

                validateConfirmPassword() {
                    if (!this.confirmPassword || this.confirmPassword.trim() === '') {
                        this.confirmPasswordError = '';
                        return;
                    }

                    const mainPassword = window.currentPassword || '';
                    if (this.confirmPassword !== mainPassword) {
                        this.confirmPasswordError = 'Passwords do not match';
                    } else {
                        this.confirmPasswordError = '';
                    }
                }
            }
        }

        function otpValidator() {
            return {
                otp: '',
                
                formatOTP() {
                    // Only allow numbers
                    this.otp = this.otp.replace(/[^0-9]/g, '');
                    if (this.otp.length > 6) {
                        this.otp = this.otp.substring(0, 6);
                    }
                }
            }
        }

        // Initialize international telephone input
        document.addEventListener('DOMContentLoaded', function() {
            const mobileInput = document.querySelector("#mobile");
            const fullMobileInput = document.querySelector("#full_mobile");
            
            if (mobileInput) {
                // Initialize ITI with better settings
                const iti = window.intlTelInput(mobileInput, {
                    separateDialCode: true,
                    preferredCountries: ["us", "gb", "au", "in"],
                    initialCountry: "auto",
                    geoIpLookup: function(callback) {
                        fetch('https://ipapi.co/json')
                            .then(res => res.json())
                            .then(data => callback(data.country_code.toLowerCase()))
                            .catch(() => callback("us"));
                    },
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
                });

                // Store ITI instance globally for validation - do this immediately
                window.itiInstance = iti;

                // Clean input and update hidden field
                function updateMobile() {
                    if (iti && iti.getNumber) {
                        try {
                            const fullNumber = iti.getNumber();
                            fullMobileInput.value = fullNumber;
                            console.log('Updated hidden mobile field:', fullNumber);
                        } catch (error) {
                            console.warn('ITI getNumber error:', error);
                        }
                    }
                }

                // Prevent non-numeric input in mobile field
                mobileInput.addEventListener('input', function(e) {
                    // Allow only numbers
                    const cleaned = e.target.value.replace(/[^\d]/g, '');
                    if (e.target.value !== cleaned) {
                        e.target.value = cleaned;
                    }
                    // Update immediately after cleaning
                    setTimeout(() => updateMobile(), 100);
                });

                // Restrict keypress to numbers only
                mobileInput.addEventListener('keypress', function(e) {
                    // Allow: backspace, delete, tab, escape, enter
                    if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                        // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                        (e.keyCode === 65 && e.ctrlKey === true) ||
                        (e.keyCode === 67 && e.ctrlKey === true) ||
                        (e.keyCode === 86 && e.ctrlKey === true) ||
                        (e.keyCode === 88 && e.ctrlKey === true) ||
                        // Allow: home, end, left, right, down, up
                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                        return;
                    }
                    // Ensure that it is a number
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });

                // Update on country change
                mobileInput.addEventListener('countrychange', function() {
                    updateMobile();
                });

                // Update on blur
                mobileInput.addEventListener('blur', function() {
                    updateMobile();
                });

                // Update hidden field on form submit
                document.querySelector("form").addEventListener("submit", function (e) {
                    updateMobile();
                    
                    // Check if mobile field has value
                    if (!fullMobileInput.value || fullMobileInput.value.trim() === '') {
                        e.preventDefault();
                        alert('Please enter a valid mobile number');
                        return false;
                    }
                    
                    console.log('Form submitted with mobile:', fullMobileInput.value);
                });
            }
        });
    </script>

</body>
</html>