

<?php $__env->startSection('title', 'My Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white">
    <!-- Page Header -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="mt-2 text-gray-600">Track and manage your orders</p>
        </div>
    </div>

    <!-- Orders Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if($orders->count() > 0): ?>
        <div class="space-y-6">
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Order Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Order #<?php echo e($order->order_number); ?></h3>
                            <p class="text-sm text-gray-600">Placed on <?php echo e($order->created_at->format('M d, Y')); ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">$<?php echo e(number_format($order->total_amount, 2)); ?></div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php if($order->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                <?php elseif($order->status === 'processing'): ?> bg-blue-100 text-blue-800
                                <?php elseif($order->status === 'shipped'): ?> bg-purple-100 text-purple-800
                                <?php elseif($order->status === 'delivered'): ?> bg-green-100 text-green-800
                                <?php elseif($order->status === 'cancelled'): ?> bg-red-100 text-red-800
                                <?php endif; ?>">
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="px-6 py-4">
                    <?php if($order->orderItems->count() > 0): ?>
                        <!-- Items Summary Box -->
                        <div class="bg-blue-50 rounded-lg p-4 mb-4 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                        <?php echo e($order->orderItems->sum('quantity')); ?>

                                    </div>
                                    <span class="font-medium text-blue-900">
                                        <?php echo e($order->orderItems->count()); ?> item(s) • <?php echo e($order->orderItems->sum('quantity')); ?> total quantity
                                    </span>
                                </div>
                                <div class="text-blue-800 font-semibold">
                                    Items Total: $<?php echo e(number_format($order->orderItems->sum('total_price'), 2)); ?>

                                </div>
                            </div>
                        </div>
                        
                        <!-- Individual Items -->
                        <div class="space-y-4">
                            <?php $__currentLoopData = $order->orderItems->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                                <?php
                                    // Try to get the MongoDB product for image
                                    $mongoProduct = null;
                                    try {
                                        if ($item->product_id && $item->product_id !== 'test-product-id') {
                                            $mongoProduct = \App\Models\MongoProduct::where('_id', $item->product_id)->first();
                                        }
                                    } catch (\Exception $e) {
                                        // Ignore if product not found
                                    }
                                ?>
                                
                                <?php if($mongoProduct && isset($mongoProduct->images[0])): ?>
                                    <img src="<?php echo e(asset('storage/' . $mongoProduct->images[0])); ?>" alt="<?php echo e($item->product_name); ?>" class="w-16 h-16 object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900"><?php echo e($item->product_name); ?></h4>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <span>Quantity: <?php echo e($item->quantity); ?></span>
                                        <span>Unit Price: $<?php echo e(number_format($item->unit_price, 2)); ?></span>
                                        <?php if($item->product_sku): ?>
                                            <span>SKU: <?php echo e($item->product_sku); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">
                                        $<?php echo e(number_format($item->total_price, 2)); ?>

                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e($item->quantity); ?> × $<?php echo e(number_format($item->unit_price, 2)); ?>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php if($order->orderItems->count() > 3): ?>
                            <div class="text-sm text-gray-600 text-center py-2 bg-gray-100 rounded-lg">
                                And <?php echo e($order->orderItems->count() - 3); ?> more items... 
                                <a href="<?php echo e(route('orders.show', $order)); ?>" class="text-blue-600 hover:text-blue-800 font-medium">View All</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <div class="text-gray-500 text-sm">No items found for this order</div>
                            <div class="text-xs text-gray-400 mt-1">This might be a legacy order</div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Order Actions -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Items: <?php echo e($order->orderItems->sum('quantity')); ?>

                        </div>
                        <div class="flex space-x-3">
                            <a href="<?php echo e(route('orders.show', $order)); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                View Details
                            </a>
                            <?php if($order->status === 'delivered'): ?>
                            <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                Reorder
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            <?php echo e($orders->links()); ?>

        </div>
        
        <?php else: ?>
        <!-- No Orders -->
        <div class="text-center py-12">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-4">No orders yet</h2>
            <p class="text-gray-600 mb-8">When you place your first order, it will appear here.</p>
            
            <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7H6L5 9z"></path>
                </svg>
                Start Shopping
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/orders/index.blade.php ENDPATH**/ ?>