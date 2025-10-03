<!DOCTYPE html>
<html lang="en" class="bg-gradient-to-br from-blue-50 to-indigo-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - {{ config('app.name', 'Elandra') }}</title>
    
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
            max-width: 600px;
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
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-key text-indigo-600 text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 font-['Playfair_Display']">Reset Password</h2>
                    <p class="text-gray-500 text-sm">Enter your email address and we'll send you a reset link</p>
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
                <form method="POST" action="{{ route('password.email.send') }}" class="space-y-6" x-data="emailValidator()">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email"
                            x-model="email"
                            @input="debounceEmailCheck()"
                            @blur="checkEmail()"
                            value="{{ old('email') }}"
                            placeholder="Enter your registered email" 
                            :class="emailFieldClass"
                            class="w-full px-4 py-3 rounded-lg text-gray-700 placeholder-gray-400" 
                            autocomplete="email" 
                            required
                            autofocus
                        >
                        <div x-show="emailError" x-text="emailError" class="text-red-500 text-sm mt-1"></div>
                        <div x-show="isChecking" class="text-blue-500 text-sm mt-1">Verifying email...</div>
                        <div x-show="emailFound && !emailError" class="text-green-500 text-sm mt-1">
                            <i class="fas fa-check-circle mr-1"></i>Account found
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <button 
                        type="submit"
                        class="w-full py-3 px-6 reset-btn text-white rounded-lg font-semibold text-base"
                        :disabled="!email || emailError || isChecking"
                        :class="!email || emailError || isChecking ? 'opacity-50 cursor-not-allowed' : ''"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Reset Link
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center">
                        <p class="text-gray-600 text-sm">
                            Remember your password? 
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
        // Email validation for forgot password
        function emailValidator() {
            return {
                email: '{{ old("email") }}',
                emailError: '',
                emailTimeout: null,
                isChecking: false,
                emailFound: false,

                init() {
                    if (this.email) {
                        this.debounceEmailCheck();
                    }
                },

                get emailFieldClass() {
                    if (!this.email) return 'form-input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                    if (this.isChecking) return 'form-input border-blue-300 focus:border-blue-500 focus:ring-blue-500';
                    if (this.emailError) return 'form-input border-red-500 focus:border-red-500 focus:ring-red-500';
                    if (this.emailFound && !this.emailError) return 'form-input border-green-500 focus:border-green-500 focus:ring-green-500';
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
                        this.emailFound = false;
                        return;
                    }

                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(this.email)) {
                        this.emailError = 'Please enter a valid email address';
                        this.isChecking = false;
                        this.emailFound = false;
                        return;
                    }

                    this.isChecking = true;
                    this.emailError = '';
                    this.emailFound = false;

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
                            this.emailFound = true;
                            this.emailError = '';
                        } else {
                            this.emailError = 'This email address is not registered with us';
                            this.emailFound = false;
                        }
                    } catch (error) {
                        console.error('Email check error:', error);
                        this.emailError = '';
                        this.emailFound = false;
                    } finally {
                        this.isChecking = false;
                    }
                }
            }
        }
    </script>
        </div>
    </div>
</body>
</html>