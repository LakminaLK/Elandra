<div>
    <!--[if BLOCK]><![endif]--><?php if($cartItems->count() > 0): ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" 
                               wire:model.live="selectAll" 
                               wire:click="toggleSelectAll"
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <h2 class="text-lg font-semibold text-gray-900">Cart Items (<?php echo e($cartItems->sum('quantity')); ?>)</h2>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php if(count($selectedItems) > 0): ?>
                        <div class="text-sm text-indigo-600">
                            <?php echo e(count($selectedItems)); ?> item(s) selected
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <div class="divide-y divide-gray-200">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-6 flex items-center space-x-4 transition-all duration-300 <?php echo e(in_array($item->id, $selectedItems) ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50'); ?>">
                        <!-- Checkbox -->
                        <div class="flex-shrink-0">
                            <input type="checkbox" 
                                   wire:click="toggleItemSelection(<?php echo e($item->id); ?>)"
                                   <?php if(in_array($item->id, $selectedItems)): ?> checked <?php endif; ?>
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        </div>
                        
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            <!--[if BLOCK]><![endif]--><?php if($item->product_image): ?>
                                <img src="<?php echo e(Storage::url($item->product_image)); ?>" alt="<?php echo e($item->product_name); ?>" class="w-20 h-20 object-cover rounded-lg">
                            <?php else: ?>
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <?php echo e($item->product_name); ?>

                            </h3>
                            
                            <!--[if BLOCK]><![endif]--><?php if($item->product_sku): ?>
                                <p class="text-sm text-gray-500">SKU: <?php echo e($item->product_sku); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            
                            <!-- Price -->
                            <div class="mt-2 flex items-center space-x-2">
                                <!--[if BLOCK]><![endif]--><?php if($item->is_sale && $item->original_price > $item->price): ?>
                                    <span class="text-lg font-bold text-red-600"><?php echo e($item->formatted_price); ?></span>
                                    <span class="text-sm text-gray-500 line-through">$<?php echo e(number_format($item->original_price, 2)); ?></span>
                                <?php else: ?>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($item->formatted_price); ?></span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            
                            <!-- Stock Status Note -->
                            <?php
                                $product = $item->product; // This will use the getProductAttribute() method
                            ?>
                            <!--[if BLOCK]><![endif]--><?php if($product && $product->stock_quantity < $item->quantity): ?>
                                <div class="mt-2 text-sm text-red-600">
                                    Only <?php echo e($product->stock_quantity); ?> available
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <!-- Quantity Controls -->
                        <div class="flex items-center space-x-3">
                            <button 
                                wire:click="updateQuantity(<?php echo e($item->id); ?>, <?php echo e($item->quantity - 1); ?>)"
                                <?php if($item->quantity <= 1): ?> disabled <?php endif; ?>
                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            
                            <span class="w-8 text-center font-medium"><?php echo e($item->quantity); ?></span>
                            
                            <button 
                                wire:click="updateQuantity(<?php echo e($item->id); ?>, <?php echo e($item->quantity + 1); ?>)"
                                <?php if($product && $item->quantity >= $product->stock_quantity): ?> disabled <?php endif; ?>
                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Line Total -->
                        <div class="flex flex-col items-end">
                            <span class="text-lg font-bold text-gray-900">
                                <?php echo e($item->formatted_total_price); ?>

                            </span>
                            
                            <!-- Remove Button -->
                            <button 
                                wire:click="removeItem(<?php echo e($item->id); ?>)"
                                wire:confirm="Are you sure you want to remove this item from your cart?"
                                class="mt-2 text-sm text-red-600 hover:text-red-800"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            
            <!-- Continue Shopping -->
            <div class="mt-6">
                <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Continue Shopping
                </a>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!--[if BLOCK]><![endif]--><?php if(count($selectedItems) > 0): ?>
                        <!-- Selected Items Summary -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-blue-900">Selected Items</span>
                                <span class="text-blue-700"><?php echo e(count($selectedItems)); ?> item(s)</span>
                            </div>
                            
                            <!-- Selected Subtotal -->
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-blue-600">Subtotal:</span>
                                <span class="font-medium text-blue-700">$<?php echo e(number_format($this->selectedTotal, 2)); ?></span>
                            </div>
                            
                            <!-- Selected Shipping -->
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-blue-600">Shipping:</span>
                                <span class="font-medium text-blue-700">
                                    <!--[if BLOCK]><![endif]--><?php if($this->selectedShipping == 0): ?>
                                        <span class="text-green-600">FREE</span>
                                    <?php else: ?>
                                        $<?php echo e(number_format($this->selectedShipping, 2)); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </span>
                            </div>
                            
                            <!-- Selected Tax -->
                            <div class="flex justify-between text-sm mb-3">
                                <span class="text-blue-600">Tax:</span>
                                <span class="font-medium text-blue-700">$<?php echo e(number_format($this->selectedTax, 2)); ?></span>
                            </div>
                            
                            <!-- Selected Total -->
                            <div class="border-t border-blue-300 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-blue-900 text-lg">Total:</span>
                                    <span class="font-bold text-blue-900 text-lg">$<?php echo e(number_format($this->selectedFinalTotal, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- All Cart Items Info (when nothing selected) -->
                        <div class="text-center py-4 text-gray-500">
                            <div class="mb-2">
                                <span class="text-sm">Total in Cart: <?php echo e($cartItems->sum('quantity')); ?> items</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-400">
                                $<?php echo e(number_format($this->subtotal, 2)); ?>

                            </div>
                            <div class="text-xs mt-1">
                                Select items above to see checkout total
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <!-- Checkout Button -->
                    <!--[if BLOCK]><![endif]--><?php if(count($selectedItems) > 0): ?>
                        <button 
                            wire:click="proceedToCheckout"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl"
                        >
                            <span wire:loading.remove>
                                Proceed to Checkout
                            </span>
                            <span wire:loading class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                        <div class="text-xs text-center text-gray-500 mt-2">
                            Only selected items will be processed
                        </div>
                    <?php else: ?>
                        <div class="w-full bg-gray-100 border-2 border-dashed border-gray-300 text-gray-500 px-6 py-3 rounded-lg font-medium text-center cursor-not-allowed">
                            Select items above to proceed
                        </div>
                        <div class="text-xs text-center text-gray-400 mt-2">
                            Use checkboxes to select items for checkout
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <!-- Clear Selected Button -->
                    <!--[if BLOCK]><![endif]--><?php if(count($selectedItems) > 0): ?>
                        <button 
                            wire:click="$set('selectedItems', [])"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors mt-3"
                        >
                            Clear Selection
                        </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <!-- Security Notice -->
                    <div class="flex items-center justify-center text-sm text-gray-500 mt-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Secure Checkout
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Empty Cart -->
    <div class="text-center py-12">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.8 9.2M7 13l2.8-2.8m0 0L17 3m0 0l-2.8 2.8M17 3l2.8 2.8"></path>
        </svg>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
        <p class="text-gray-600 mb-8">Looks like you haven't added any items to your cart yet.</p>
        
        <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7H6L5 9z"></path>
            </svg>
            Start Shopping
        </a>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <!-- Loading Overlay -->
    <div wire:loading wire:target="updateQuantity,removeItem,proceedToCheckout" class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-900 font-medium">Updating cart...</span>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Elandra\resources\views/livewire/shopping-cart.blade.php ENDPATH**/ ?>