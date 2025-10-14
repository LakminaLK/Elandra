<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order Management</h1>
            <p class="text-gray-600 mt-1">Manage and track all customer orders</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($totalOrders); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-sync-alt text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Processing Orders</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($processingOrders); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-truck text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Shipped</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($shippedOrders); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-2 bg-emerald-100 rounded-lg">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Delivered</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($deliveredOrders); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Orders</label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Order number, customer name, email..." 
                           class="w-full px-4 py-2 pl-10 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <!-- Loading spinner for search -->
                    <div wire:loading.delay wire:target="search" class="absolute inset-y-0 right-10 pr-3 flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php if($search): ?>
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
                    </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                <select wire:model.live="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <div wire:loading.delay wire:target="filterStatus" class="absolute top-9 right-8 flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                </div>
            </div>

            <!-- Payment Status Filter -->
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select wire:model.live="filterPaymentStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Payment Status</option>
                    <option value="paid">Paid</option>
                    <option value="refunded">Refunded</option>
                </select>
                <div wire:loading.delay wire:target="filterPaymentStatus" class="absolute top-9 right-8 flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 flex items-center space-x-4 shadow-2xl animate-scale-in">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
            <div class="text-gray-700">
                <p class="font-semibold text-lg">Loading orders...</p>
                <p class="text-sm text-gray-500">Please wait while we fetch the latest orders</p>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden relative">
        <!-- Table Loading Overlay -->
        <div wire:loading.delay wire:target="search,filterStatus,filterPaymentStatus,sortBy" 
             class="absolute inset-0 bg-white bg-opacity-80 z-10 flex items-center justify-center">
            <div class="flex flex-col items-center space-y-2">
                <div class="animate-spin rounded-full h-8 w-8 border-3 border-blue-500 border-t-transparent"></div>
                <p class="text-sm text-gray-600">Refreshing data...</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('order_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            Order #
                            <!--[if BLOCK]><![endif]--><?php if($sortField === 'order_number'): ?>
                                <i class="fas fa-sort-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> ml-1"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th wire:click="sortBy('total_amount')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            Total
                            <!--[if BLOCK]><![endif]--><?php if($sortField === 'total_amount'): ?>
                                <i class="fas fa-sort-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> ml-1"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                        <th wire:click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            Status
                            <!--[if BLOCK]><![endif]--><?php if($sortField === 'status'): ?>
                                <i class="fas fa-sort-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> ml-1"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th wire:click="sortBy('created_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            Date
                            <!--[if BLOCK]><![endif]--><?php if($sortField === 'created_at'): ?>
                                <i class="fas fa-sort-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> ml-1"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#<?php echo e($order->order_number); ?></div>
                            <div class="text-xs text-gray-500">ID: <?php echo e($order->id); ?></div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($order->user->name ?? 'Guest'); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($order->user->email ?? $order->customer_email); ?></div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo e($order->orderItems->count()); ?> items</div>
                            <div class="text-xs text-gray-500"><?php echo e($order->orderItems->sum('quantity')); ?> pieces</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">$<?php echo e(number_format($order->total_amount, 2)); ?></div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                            ?>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo e($statusColors[$order->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!--[if BLOCK]><![endif]--><?php if(in_array($order->status, ['processing', 'shipped', 'delivered'])): ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                            <?php elseif($order->status === 'cancelled'): ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Refunded</span>
                            <?php else: ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo e($order->created_at->format('M j, Y')); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($order->created_at->format('g:i A')); ?></div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" 
                                   class="text-blue-600 hover:text-blue-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button wire:click="openStatusModal(<?php echo e($order->id); ?>, '<?php echo e($order->status); ?>')" 
                                        class="text-green-600 hover:text-green-900" title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No orders found</p>
                                <p class="text-sm">Try adjusting your search or filter criteria</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <!--[if BLOCK]><![endif]--><?php if($orders->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($orders->links()); ?>

        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Status Update Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showStatusModal): ?>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <form wire:submit.prevent="updateOrderStatus">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Update Order Status</h3>
                </div>
                
                <div class="px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                    <select wire:model="selectedOrderStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedOrderStatus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea wire:model="statusNotes" rows="3" placeholder="Add any notes about this status change..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['statusNotes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t flex justify-end space-x-3">
                    <button type="button" wire:click="closeStatusModal" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" 
                            wire:loading.attr="disabled" 
                            wire:target="updateOrderStatus"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-75 disabled:cursor-not-allowed flex items-center">
                        <span wire:loading.remove wire:target="updateOrderStatus">Update Status</span>
                        <span wire:loading wire:target="updateOrderStatus" class="flex items-center">
                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent mr-2"></div>
                            Updating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Success Message -->
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50" 
         x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <?php echo e(session('message')); ?>

        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <style>
        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-scale-in {
            animation: scale-in 0.5s ease-out forwards;
        }
    </style>
</div><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/livewire/admin/order-management.blade.php ENDPATH**/ ?>