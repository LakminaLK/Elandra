<!-- Admin Sidebar Component -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 border-r border-gray-200">
    <!-- Sidebar Header with Logo -->
    <div class="flex items-center h-20 px-6 border-b border-gray-200">
        <div class="flex items-center">
            <img src="{{ asset('elandra-logo.webp') }}" alt="Elandra Logo" class="w-10 h-10 mr-3 rounded-lg object-contain">
            <h1 class="text-xl font-bold text-gray-800">Elandra</h1>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 px-4">
        <div class="space-y-2">
            <!-- Main Menu Section -->
            <div class="mb-6">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Main Menu</p>
                
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Products -->
                    <a href="{{ route('admin.products.index') }}" 
                       class="sidebar-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-box w-5 h-5 mr-3 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span>Products</span>
                    </a>

                    <!-- Categories & Brands -->
                    <a href="{{ route('admin.categories-brands.index') }}" 
                       class="sidebar-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories-brands.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-tags w-5 h-5 mr-3 {{ request()->routeIs('admin.categories-brands.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span>Categories & Brands</span>
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('admin.orders.index') }}" 
                       class="sidebar-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span>Orders</span>
                    </a>

                    <!-- Customers -->
                    <a href="{{ route('admin.customers.index') }}" 
                       class="sidebar-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-users w-5 h-5 mr-3 {{ request()->routeIs('admin.customers.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span>Customers</span>
                    </a>
                </div>
            </div>

            <!-- System Section -->
            <div class="mb-6">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">System</p>
                
                <div class="space-y-2">
                    <!-- System Monitoring -->
                    <a href="{{ route('admin.monitoring') }}" 
                       class="sidebar-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.monitoring') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-heartbeat w-5 h-5 mr-3 {{ request()->routeIs('admin.monitoring') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span>System Monitoring</span>
                    </a>
                </div>
            </div>

            <!-- Account Section -->
            <div class="mb-6">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Account</p>

                <div class="space-y-2">
                    <!-- Profile -->
                    <a href="{{ route('admin.profile.index') }}" 
                       class="sidebar-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.profile.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-user w-5 h-5 mr-3 {{ request()->routeIs('admin.profile.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span>Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>


</div>

<!-- Sidebar overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>