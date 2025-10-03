<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Elandra') }} - Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('elandra-logo.webp') }}">
    <link rel="shortcut icon" type="image/webp" href="{{ asset('elandra-logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('elandra-logo.webp') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome CSS (safer than JS kit) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Vite CSS Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Desktop Sidebar -->
        @include('admin.components.sidebar')
        
        <!-- Mobile Sidebar -->
        @include('admin.components.mobile-sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Top Navigation -->
            @include('admin.components.navbar')

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50" style="margin-top: 80px; padding-top: 20px;">
                @if(session('success'))
                    <div class="success-message mx-4 lg:mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-4 lg:mx-6 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts and Styles -->
    @include('admin.components.scripts')
    
    @livewireScripts
</body>
</html>