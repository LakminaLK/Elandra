<div>
    <!--[if BLOCK]><![endif]--><?php if($featuredProducts->count() > 0): ?>
    <!-- Featured Products Section -->
    <section class="py-20 bg-gradient-to-b from-slate-50 to-white" id="featured" wire:ignore.self>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Static Header - Won't re-render -->
            <div class="text-center mb-16" data-aos="fade-up" wire:ignore>
                <div class="inline-flex items-center bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-star mr-2"></i>
                    Featured
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Featured Products</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Discover our handpicked selection of the finest luxury handbags, crafted with exceptional attention to detail.
                </p>
            </div>
            
            <!-- Success/Error Messages -->
            <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative animate-fade-in" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <?php if(session()->has('error')): ?>
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate-fade-in" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <!-- Products Grid - Preserve AOS animations -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" wire:loading.class="opacity-75" wire:target="addToCart">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="product-card group bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-200 overflow-hidden transition-all duration-300 flex flex-col h-full" 
                     data-aos="fade-up" 
                     data-aos-delay="<?php echo e($loop->index * 100); ?>"
                     wire:key="product-<?php echo e($product->_id); ?>"
                     wire:ignore.self>
                    
                    
                    <div class="relative aspect-square overflow-hidden bg-gray-50 flex-shrink-0" wire:ignore>
                        <a href="<?php echo e(route('products.show', $product->slug ?? $product->_id)); ?>" 
                           class="block w-full h-full">
                            <!--[if BLOCK]><![endif]--><?php if(isset($product->images) && is_array($product->images) && count($product->images) > 0): ?>
                                <img src="<?php echo e(Storage::url($product->images[0])); ?>" 
                                     alt="<?php echo e($product->name); ?>" 
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-5xl text-gray-300"></i>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </a>
                        
                        
                        <div class="absolute top-3 left-3 flex flex-col space-y-1">
                            <!--[if BLOCK]><![endif]--><?php if(isset($product->is_featured) && $product->is_featured): ?>
                                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded font-medium shadow-sm">
                                    Featured
                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if(isset($product->stock_quantity) && $product->stock_quantity <= 5 && $product->stock_quantity > 0): ?>
                                <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded font-medium shadow-sm">
                                    Only <?php echo e($product->stock_quantity); ?> left
                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php if(isset($product->stock_quantity) && $product->stock_quantity <= 0): ?>
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-medium shadow-sm">
                                    Sold Out
                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <button class="absolute top-3 right-3 w-8 h-8 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full flex items-center justify-center text-gray-500 hover:text-red-500 transition-all duration-200 shadow-sm">
                            <i class="fas fa-heart text-sm"></i>
                        </button>
                    </div>

                    
                    <div class="p-5 flex flex-col h-full">
                        
                        <div class="flex items-center space-x-2 mb-3 min-h-[24px]" wire:ignore>
                            <!--[if BLOCK]><![endif]--><?php if($product->category): ?>
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium">
                                    <?php echo e(ucfirst($product->category)); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($product->brand): ?>
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium">
                                    <?php echo e(ucfirst($product->brand)); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div class="mb-4 flex-grow" wire:ignore>
                            <h3 class="font-medium text-lg text-gray-900 line-clamp-2 leading-tight h-[3.5rem] overflow-hidden">
                                <a href="<?php echo e(route('products.show', $product->slug ?? $product->_id)); ?>" 
                                   class="hover:text-blue-600 transition-colors duration-200">
                                    <?php echo e($product->name); ?>

                                </a>
                            </h3>
                        </div>

                        
                        <div class="mb-4" wire:ignore>
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-2xl font-semibold text-gray-900">
                                    $<?php echo e(number_format($product->price, 2)); ?>

                                </div>
                            </div>
                            <div class="min-h-[20px]">
                                <?php if(isset($product->stock_quantity)): ?>
                                    <!--[if BLOCK]><![endif]--><?php if($product->stock_quantity > 0): ?>
                                        <div class="text-sm text-green-600 font-medium">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            In Stock
                                        </div>
                                    <?php else: ?>
                                        <div class="text-sm text-red-600 font-medium">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Out of Stock
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        
                        <div class="mt-auto" wire:key="button-<?php echo e($product->_id); ?>">
                            <!--[if BLOCK]><![endif]--><?php if(!isset($product->stock_quantity) || $product->stock_quantity > 0): ?>
                                <!--[if BLOCK]><![endif]--><?php if($this->canAddToCart($product)): ?>
                                    <button wire:click="addToCart('<?php echo e($product->_id); ?>')" 
                                            wire:loading.attr="disabled"
                                            wire:target="addToCart('<?php echo e($product->_id); ?>')"
                                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white py-3 rounded-lg font-medium transition-all duration-200 flex items-center justify-center space-x-2 group">
                                        
                                        <!-- Default State -->
                                        <div wire:loading.remove wire:target="addToCart('<?php echo e($product->_id); ?>')">
                                            <i class="fas fa-shopping-bag text-sm"></i>
                                            <span>Add to Cart</span>
                                            <!--[if BLOCK]><![endif]--><?php if($this->getCartQuantity($product->_id) > 0): ?>
                                                <span class="ml-2 px-2 py-1 bg-blue-800 rounded-full text-xs"><?php echo e($this->getCartQuantity($product->_id)); ?></span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                        
                                        <!-- Loading State -->
                                        <div wire:loading wire:target="addToCart('<?php echo e($product->_id); ?>')" class="flex items-center space-x-2">
                                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></div>
                                            <span>Adding...</span>
                                        </div>
                                    </button>
                                <?php else: ?>
                                    <?php
                                        $cartQty = $this->getCartQuantity($product->_id);
                                        $maxQty = min(3, $product->stock_quantity ?? 3);
                                    ?>
                                    <button disabled 
                                            class="w-full bg-gray-200 text-gray-500 py-3 rounded-lg font-medium cursor-not-allowed flex items-center justify-center space-x-2">
                                        <i class="fas fa-check-circle text-sm"></i>
                                        <span>Max Qty (<?php echo e($cartQty); ?>/<?php echo e($maxQty); ?>)</span>
                                    </button>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                                <button disabled 
                                        class="w-full bg-gray-200 text-gray-500 py-3 rounded-lg font-medium cursor-not-allowed flex items-center justify-center space-x-2">
                                    <i class="fas fa-times-circle text-sm"></i>
                                    <span>Out of Stock</span>
                                </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Static Footer - Won't re-render -->
            <div class="text-center mt-12" data-aos="fade-up" wire:ignore>
                <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-store mr-3"></i>
                    View All Products
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\Elandra\resources\views/livewire/featured-products.blade.php ENDPATH**/ ?>