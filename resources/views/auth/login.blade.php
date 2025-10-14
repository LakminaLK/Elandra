<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Elandra') }}</title>
    
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
            overflow: hidden;
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
        
        .fashion-overlay {
            background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 50%, rgba(255,255,255,0.1) 100%);
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
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
        
        .login-btn {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            transform: translateY(0);
            position: relative;
            overflow: hidden;
        }
        
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        
        .login-btn:hover::before {
            left: 100%;
        }
        
        .login-btn:hover {
            background: linear-gradient(135deg, #4338CA 0%, #6D28D9 100%);
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.4);
            animation: pulseGlow 2s infinite;
        }
        
        .login-btn:active {
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
        
        /* Input label animation */
        .input-group {
            position: relative;
        }
        
        .floating-label {
            position: absolute;
            left: 12px;
            top: 12px;
            background: white;
            padding: 0 8px;
            color: #6B7280;
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .form-input:focus + .floating-label,
        .form-input:not(:placeholder-shown) + .floating-label {
            top: -8px;
            font-size: 12px;
            color: #4F46E5;
            font-weight: 600;
        }
    </style>
</head>
<body class="h-screen flex overflow-hidden">

    <!-- Left side - Fashion Image -->
    <div class="hidden lg:block lg:w-3/5 relative overflow-hidden animate-slide-left">
        <!-- Elandra Handbag Model -->
        <img 
            src="{{ asset('images/login-images.webp') }}" 
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
                    "A woman's handbag is more than an accessory. It's a statement, a confidence, a piece of her identity."
                </blockquote>
                <p class="text-white/80 text-sm font-medium">– Elandra Collection</p>
            </div>
        </div>
    </div>

    <!-- Right side - Login Form -->
    <div class="w-full lg:w-2/5 flex justify-center items-center bg-white px-8 py-12 relative animate-slide-right">
        <div class="w-full max-w-sm">
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
            
            <!-- Login Card -->
            <div class="login-card rounded-2xl p-8 animate-fade-up animate-delay-200">
                <!-- Header -->
                <div class="text-center mb-8 animate-fade-up animate-delay-400">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 font-['Playfair_Display']">Welcome Back!</h2>
                    <p class="text-gray-500 text-sm">Enter your credentials to access your account</p>
                </div>

                <!-- Login Form -->
                <form x-data="loginForm()" class="space-y-6">
                    @if(request('redirect'))
                        <input type="hidden" name="redirect" value="{{ request('redirect') }}" x-ref="redirect">
                    @endif

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email"
                            x-model="email" 
                            value="{{ session('registered_email') ?? old('email') }}"
                            placeholder="Enter your email" 
                            class="w-full px-4 py-3 form-input rounded-lg text-gray-700 placeholder-gray-400" 
                            autocomplete="email" 
                            required
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                id="password"
                                x-model="password" 
                                placeholder="Enter your password"
                                class="w-full px-4 py-3 pr-12 form-input rounded-lg text-gray-700 placeholder-gray-400" 
                                autocomplete="current-password" 
                                required
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute right-4 top-1/3 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-2 text-gray-600">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="button" 
                        @click="submitLogin"
                        class="w-full py-3 px-6 login-btn text-white rounded-lg font-semibold text-base"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In
                    </button>

                    <!-- Forgot Password Link -->
                    <div class="text-center mb-4">
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors font-medium">
                            Forgot your password?
                        </a>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center">
                        <p class="text-gray-600 text-sm">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors font-medium">
                                Create Account
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
    @if (session('success') || session('error') || $errors->any())
    <div 
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:leave="transition ease-in duration-300"
        class="fixed top-6 right-6 z-50"
    >
        @if (session('success'))
            <div class="bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg min-w-96 max-w-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fas fa-check-circle text-lg"></i>
                            <span class="font-semibold">{{ session('success') }}</span>
                        </div>
                        @if (session('email_sent'))
                            <div class="text-green-100 text-sm pl-6">
                                {{ session('email_sent') }}
                            </div>
                        @endif
                        @if (session('registered_email'))
                            <div class="text-green-100 text-sm pl-6 mt-1">
                                Please use <strong>{{ session('registered_email') }}</strong> to login below.
                            </div>
                        @endif
                    </div>
                    <button @click="show = false" class="text-white hover:bg-green-700 rounded-lg px-2 py-1 transition-colors flex-shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
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
        @elseif ($errors->any())
            @php
                $firstError = $errors->first();
                $isLockoutError = str_contains(strtolower($firstError), 'locked') || str_contains(strtolower($firstError), 'too many');
                $bgColor = $isLockoutError ? 'bg-orange-600' : 'bg-red-600';
                $hoverColor = $isLockoutError ? 'hover:bg-orange-700' : 'hover:bg-red-700';
                $icon = $isLockoutError ? 'fas fa-lock' : 'fas fa-exclamation-triangle';
            @endphp
            <div class="{{ $bgColor }} text-white px-6 py-4 rounded-xl shadow-lg flex items-start justify-between gap-4 min-w-80">
                <div class="flex items-start gap-3">
                    <i class="{{ $icon }} text-lg mt-1"></i>
                    <div>
                        @if($errors->count() == 1)
                            <span>{{ $errors->first() }}</span>
                        @else
                            <div>
                                <div class="font-semibold mb-2">Please fix the following:</div>
                                <ul class="text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <button @click="show = false" class="text-white {{ $hoverColor }} rounded-lg px-2 py-1 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
    @endif

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function loginForm() {
            return {
                email: '{{ session("registered_email") ?? old("email") }}',
                password: '',

                async submitLogin() {
                    const formData = new FormData();
                    formData.append('email', this.email);
                    formData.append('password', this.password);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    @if(request('redirect'))
                        formData.append('redirect', this.$refs.redirect.value);
                    @endif

                    try {
                        const response = await fetch('{{ route("login") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            const data = await response.json();
                            if (data.errors) {
                                this.showToast('error', data.errors.email ? data.errors.email[0] : 'Invalid credentials');
                            }
                        }
                    } catch (error) {
                        this.showToast('error', 'Network error. Please try again.');
                    }
                    
                    this.password = '';
                },

                showToast(type, message) {
                    const toast = document.createElement('div');
                    toast.className = `fixed top-6 right-6 z-50 ${type === 'error' ? 'bg-red-500' : 'bg-green-500'} text-white px-6 py-4 rounded-lg shadow-xl flex items-center gap-3 max-w-md`;
                    toast.innerHTML = `
                        <i class="fas ${type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle'}"></i>
                        <span>${message}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-white hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 4000);
                }
            }
        }
    </script>

</body>
</html>
