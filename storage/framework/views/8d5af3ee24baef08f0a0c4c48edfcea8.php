<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Elandra')); ?> - Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="<?php echo e(asset('elandra-logo.webp')); ?>">
    <link rel="shortcut icon" type="image/webp" href="<?php echo e(asset('elandra-logo.webp')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('elandra-logo.webp')); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome CSS (safer than JS kit) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Vite CSS Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Desktop Sidebar -->
        <?php echo $__env->make('admin.components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Mobile Sidebar -->
        <?php echo $__env->make('admin.components.mobile-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Top Navigation -->
            <?php echo $__env->make('admin.components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50" style="margin-top: 80px; padding-top: 20px;">
                <?php if(session('success')): ?>
                    <div class="success-message mx-4 lg:mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php echo e(session('success')); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mx-4 lg:mx-6 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?php echo e(session('error')); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- Scripts and Styles -->
    <?php echo $__env->make('admin.components.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>