<!-- Mobile Sidebar -->
<div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
    <div class="flex items-center justify-between h-16 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <div class="flex items-center">
            <i class="fas fa-store text-2xl mr-3"></i>
            <h1 class="text-xl font-bold">Elandra Admin</h1>
        </div>
        <button id="close-mobile-menu" class="text-white hover:text-gray-200">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <nav class="mt-8 px-4">
        <div class="space-y-2">
            <!-- Dashboard -->
            <a href="<?php echo e(route('admin.dashboard')); ?>" 
               class="mobile-nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : ''); ?>">
                <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Products -->
            <a href="<?php echo e(route('admin.products.index')); ?>" 
               class="mobile-nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors <?php echo e(request()->routeIs('admin.products.*') ? 'bg-green-50 text-green-700' : ''); ?>">
                <i class="fas fa-box w-5 h-5 mr-3"></i>
                <span class="font-medium">Products</span>
            </a>

            <!-- Categories -->
            <a href="<?php echo e(route('admin.categories.index')); ?>" 
               class="mobile-nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-700 transition-colors <?php echo e(request()->routeIs('admin.categories.*') ? 'bg-purple-50 text-purple-700' : ''); ?>">
                <i class="fas fa-tags w-5 h-5 mr-3"></i>
                <span class="font-medium">Categories</span>
            </a>

            <!-- Orders -->
            <a href="<?php echo e(route('admin.orders.index')); ?>" 
               class="mobile-nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-yellow-50 hover:text-yellow-700 transition-colors <?php echo e(request()->routeIs('admin.orders.*') ? 'bg-yellow-50 text-yellow-700' : ''); ?>">
                <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                <span class="font-medium">Orders</span>
            </a>

            <!-- Customers -->
            <a href="<?php echo e(route('admin.customers.index')); ?>" 
               class="mobile-nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-700 transition-colors <?php echo e(request()->routeIs('admin.customers.*') ? 'bg-red-50 text-red-700' : ''); ?>">
                <i class="fas fa-users w-5 h-5 mr-3"></i>
                <span class="font-medium">Customers</span>
            </a>

            <!-- Separator -->
            <hr class="my-4 border-gray-200">

            <!-- Settings -->
            <a href="#" 
               class="mobile-nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors">
                <i class="fas fa-cog w-5 h-5 mr-3"></i>
                <span class="font-medium">Settings</span>
            </a>

            <!-- Profile -->
            <a href="#" 
               class="mobile-nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors">
                <i class="fas fa-user w-5 h-5 mr-3"></i>
                <span class="font-medium">Profile</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="mt-4">
                <?php echo csrf_field(); ?>
                <button type="submit" class="mobile-nav-link w-full flex items-center px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</div>

<!-- Mobile sidebar overlay -->
<div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/admin/components/mobile-sidebar.blade.php ENDPATH**/ ?>