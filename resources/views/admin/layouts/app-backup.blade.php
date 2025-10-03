<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Elandra') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts and Styles -->
    @include('admin.components.scripts')
    
    @livewireStyles
            line-height: 1.6;
        }
        
        /* Utility Classes (Tailwind Fallback) */
        .flex { display: flex !important; }
        .items-center { align-items: center !important; }
        .justify-between { justify-content: space-between !important; }
        .justify-center { justify-content: center !important; }
        .h-screen { height: 100vh !important; }
        .h-16 { height: 4rem !important; }
        .w-64 { width: 16rem !important; }
        .w-8 { width: 2rem !important; }
        .h-8 { height: 2rem !important; }
        .w-6 { width: 1.5rem !important; }
        .h-6 { height: 1.5rem !important; }
        .w-5 { width: 1.25rem !important; }
        .h-5 { height: 1.25rem !important; }
        
        .p-6 { padding: 1.5rem !important; }
        .px-6 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
        .py-4 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
        .px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
        .py-3 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
        .mt-8 { margin-top: 2rem !important; }
        .mb-2 { margin-bottom: 0.5rem !important; }
        .mr-3 { margin-right: 0.75rem !important; }
        .space-x-3 > * + * { margin-left: 0.75rem !important; }
        
        .text-xl { font-size: 1.25rem !important; }
        .text-2xl { font-size: 1.5rem !important; }
        .text-sm { font-size: 0.875rem !important; }
        .text-xs { font-size: 0.75rem !important; }
        .font-semibold { font-weight: 600 !important; }
        .font-medium { font-weight: 500 !important; }
        .font-bold { font-weight: 700 !important; }
        
        .text-gray-900 { color: #111827 !important; }
        .text-gray-700 { color: #374151 !important; }
        .text-gray-500 { color: #6b7280 !important; }
        .text-gray-400 { color: #9ca3af !important; }
        .text-white { color: #ffffff !important; }
        .text-blue-700 { color: #1d4ed8 !important; }
        
        .bg-white { background-color: #ffffff !important; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        .bg-gray-100 { background-color: #f3f4f6 !important; }
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-red-500 { background-color: #ef4444 !important; }
        
        .rounded-lg { border-radius: 0.5rem !important; }
        .rounded-2xl { border-radius: 1rem !important; }
        .rounded-full { border-radius: 9999px !important; }
        
        .border { border-width: 1px !important; }
        .border-gray-200 { border-color: #e5e7eb !important; }
        .border-r-4 { border-right-width: 4px !important; }
        .border-blue-500 { border-color: #3b82f6 !important; }
        
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important; }
        .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; }
        
        /* Gradients */
        .bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)) !important; }
        .from-blue-500 { --tw-gradient-from: #3b82f6; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0)); }
        .to-blue-700 { --tw-gradient-to: #1d4ed8; }
        
        /* Sidebar Styles */
        .sidebar-mobile { 
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            width: 16rem !important;
            background-color: #ffffff !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            transform: translateX(-100%) !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            z-index: 50 !important;
        }
        
        .sidebar-mobile.show {
            transform: translateX(0) !important;
        }
        
        @media (min-width: 1024px) {
            .sidebar-mobile {
                position: static !important;
                transform: translateX(0) !important;
            }
        }

        /* Navigation */
        .nav-item {
            display: flex !important;
            align-items: center !important;
            padding: 0.75rem 1rem !important;
            margin-bottom: 0.5rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: #374151 !important;
            border-radius: 0.5rem !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
        }
        
        .nav-item:hover {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
            transform: translateX(4px) !important;
        }
        
        .nav-item.active {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
            border-right: 4px solid #3b82f6 !important;
            transform: translateX(4px) !important;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        .animate-fade-in { animation: fadeIn 0.5s ease-in-out !important; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 3px; 
        }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Responsive utilities */
        .hidden { display: none !important; }
        .fixed { position: fixed !important; }
        .inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
        .z-40 { z-index: 40; }
        .z-50 { z-index: 50; }
        
        .overflow-hidden { overflow: hidden !important; }
        .overflow-y-auto { overflow-y: auto !important; }
        
        .focus\:outline-none:focus { outline: none !important; }
        .focus\:ring-2:focus { box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important; }
        
        .transition-colors { transition-property: color, background-color, border-color !important; }
        .transition-transform { transition-property: transform !important; }
        .duration-300 { transition-duration: 300ms !important; }
        .ease-in-out { transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important; }
        
        @media (min-width: 1024px) {
            .lg\:hidden { display: none !important; }
            .lg\:static { position: static !important; }
            .lg\:translate-x-0 { transform: translateX(0) !important; }
        }
        
        /* Button hover effects */
        button:hover { 
            background-color: #f3f4f6 !important; 
            transition: all 0.2s ease !important;
        }
        
        /* Form inputs */
        input { 
            width: 100% !important;
            padding: 0.5rem 1rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s ease !important;
        }
        
        input:focus {
            outline: none !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-mobile fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transition-transform duration-300 ease-in-out lg:translate-x-0">
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">E</span>
                    </div>
                    <h1 class="text-xl font-semibold text-gray-900">Elandra Admin</h1>
                </div>
                <button id="sidebar-close" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-8 px-4">
                <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-4 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.products.index') }}" class="nav-item flex items-center px-4 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Products
                </a>

                <a href="{{ route('admin.categories.index') }}" class="nav-item flex items-center px-4 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Categories
                </a>

                <a href="{{ route('admin.orders.index') }}" class="nav-item flex items-center px-4 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.orders.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Orders
                </a>

                <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center px-4 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Users
                </a>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <a href="#" class="nav-item flex items-center px-4 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center">
                        <span class="text-gray-700 font-medium text-sm">{{ substr(auth()->guard('admin')->user()->name ?? 'A', 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->guard('admin')->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->guard('admin')->user()->email ?? 'admin@example.com' }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            @hasSection('subtitle')
                                <p class="text-sm text-gray-500">@yield('subtitle')</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.5-3.5a50.002 50.002 0 00-1.5-1.5V6a6 6 0 10-12 0v6c0 .27-.01.54-.03.81L5 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>

                        <!-- Search -->
                        <div class="hidden md:block">
                            <input type="text" placeholder="Search..." class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden hidden"></div>

    @livewireScripts

    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const backdrop = document.getElementById('sidebar-backdrop');

            function openSidebar() {
                sidebar.classList.add('show');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            sidebarToggle?.addEventListener('click', openSidebar);
            sidebarClose?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);

            // Close sidebar on window resize to large screens
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                }
            });
        });

        // Add loading states to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
                    submitBtn.disabled = true;
                }
            });
        });
    </script>
</body>
</html>