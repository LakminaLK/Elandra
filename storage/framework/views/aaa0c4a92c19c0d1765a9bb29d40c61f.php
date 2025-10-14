<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 flex items-center space-x-4 shadow-2xl animate-scale-in">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
            <div class="text-gray-700">
                <p class="font-semibold text-lg">Loading products...</p>
                <p class="text-sm text-gray-500">Please wait while we fetch the latest products</p>
            </div>
        </div>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center shadow-sm animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-emerald-600 text-lg"></i>
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <div class="relative mb-12 py-16 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden">
        
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white to-transparent opacity-5"></div>
        
        
        <div class="relative max-w-4xl mx-auto text-center px-4">
            <div class="inline-flex items-center bg-white bg-opacity-20 rounded-full px-4 py-2 mb-6 animate-fade-in">
                <i class="fas fa-shopping-bag mr-2 text-sm"></i>
                <span class="text-sm font-medium">Premium Collection</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-slide-down">
                Luxury <span class="text-yellow-300">Handbags</span>
            </h1>
            
            <p class="text-xl text-blue-100 leading-relaxed max-w-2xl mx-auto mb-8 animate-slide-up delay-200">
                Discover our exclusive collection of premium handbags and accessories. 
                Crafted with passion, designed for elegance, made for you.
            </p>
            
            <div class="flex flex-wrap justify-center items-center gap-8 text-blue-200 animate-fade-in delay-500">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-yellow-400"></i>
                    <span class="text-sm">Authentic Products</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shipping-fast text-yellow-400"></i>
                    <span class="text-sm">Fast Delivery</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-award text-yellow-400"></i>
                    <span class="text-sm">Premium Quality</span>
                </div>
            </div>
        </div>
        
        
        <div class="absolute top-0 left-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-x-16 -translate-y-16"></div>
        <div class="absolute bottom-0 right-0 w-48 h-48 bg-white bg-opacity-5 rounded-full translate-x-24 translate-y-24"></div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 mb-8 animate-fade-in delay-300">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-medium text-gray-800 flex items-center">
                <i class="fas fa-sliders-h mr-2 text-blue-600"></i>
                Filter Products
            </h2>
            <div class="text-sm text-gray-500">
                <span class="font-medium text-gray-700"><?php echo e($products->count()); ?></span> products
            </div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-600">
                    Search Products
                </label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Search by name or brand..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    
                    <div wire:loading.delay wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-600">
                    Category
                </label>
                <div class="relative">
                    <select wire:model.live="filterCategory" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white transition-colors duration-200">
                        <option value="">All Categories</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category); ?>"><?php echo e(ucfirst($category)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <div wire:loading.delay wire:target="filterCategory" class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                        <i wire:loading.delay.remove wire:target="filterCategory" class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>

            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-600">
                    Brand
                </label>
                <div class="relative">
                    <select wire:model.live="filterBrand" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white transition-colors duration-200">
                        <option value="">All Brands</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand); ?>"><?php echo e(ucfirst($brand)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <div wire:loading.delay wire:target="filterBrand" class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                        <i wire:loading.delay.remove wire:target="filterBrand" class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div wire:loading.class="opacity-60" wire:target="filterCategory,filterBrand,search" 
         class="transition-all duration-500">
        <!--[if BLOCK]><![endif]--><?php if($products->count() > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12" id="products-grid">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product-card group bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-200 overflow-hidden transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 flex flex-col h-full opacity-0 translate-y-8"
                         data-index="<?php echo e($index); ?>"
                         style="transition-delay: <?php echo e($index * 0.1); ?>s">
                        
                        
                        <div class="relative aspect-square overflow-hidden bg-gray-50 flex-shrink-0">
                            <a href="<?php echo e(route('products.show', $product->slug ?? $product->_id)); ?>" 
                               class="block w-full h-full">
                                <!--[if BLOCK]><![endif]--><?php if(isset($product->images) && is_array($product->images) && count($product->images) > 0): ?>
                                    <img src="<?php echo e(Storage::url($product->images[0])); ?>" 
                                         alt="<?php echo e($product->name); ?>" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
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
                                    <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded font-medium shadow-sm animate-pulse">
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
                            
                            <div class="flex items-center space-x-2 mb-3 min-h-[24px]">
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

                            
                            <div class="mb-4 flex-grow">
                                <h3 class="font-medium text-lg text-gray-900 line-clamp-2 leading-tight h-[3.5rem] overflow-hidden">
                                    <a href="<?php echo e(route('products.show', $product->slug ?? $product->_id)); ?>" 
                                       class="hover:text-blue-600 transition-colors duration-200">
                                        <?php echo e($product->name); ?>

                                    </a>
                                </h3>
                            </div>

                            
                            <div class="mb-4">
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

                            
                            <div class="mt-auto">
                                <?php if(auth()->guard()->check()): ?>
                                    <!--[if BLOCK]><![endif]--><?php if(!isset($product->stock_quantity) || $product->stock_quantity > 0): ?>
                                        <button wire:click="addToCart('<?php echo e($product->_id); ?>')" 
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 group">
                                            <i class="fas fa-shopping-bag text-sm"></i>
                                            <span>Add to Cart</span>
                                            <div wire:loading.delay wire:target="addToCart('<?php echo e($product->_id); ?>')" class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent ml-2"></div>
                                        </button>
                                    <?php else: ?>
                                        <button disabled 
                                                class="w-full bg-gray-200 text-gray-500 py-3 rounded-lg font-medium cursor-not-allowed flex items-center justify-center space-x-2">
                                            <i class="fas fa-times-circle text-sm"></i>
                                            <span>Out of Stock</span>
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php else: ?>
                                    <a href="<?php echo e(route('login')); ?>" 
                                       class="block w-full bg-gray-700 hover:bg-gray-800 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
                                        <i class="fas fa-sign-in-alt mr-2 text-sm"></i>
                                        Login to Purchase
                                    </a>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            
            <div class="flex justify-center animate-fade-in delay-500">
                <?php echo e($products->links()); ?>

            </div>
        <?php else: ?>
            
            <div class="text-center py-16 animate-fade-in">
                <div class="mb-6">
                    <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 mb-3">No products found</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    We couldn't find any products matching your search. Try different keywords or clear your filters.
                </p>
                <button wire:click="$refresh" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-refresh mr-2"></i>
                    Refresh
                </button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }

        .animate-slide-down {
            animation: slide-down 0.8s ease-out forwards;
        }

        .animate-slide-up {
            animation: slide-up 0.8s ease-out forwards;
        }

        .animate-scale-in {
            animation: scale-in 0.5s ease-out forwards;
        }

        .delay-200 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .delay-300 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .delay-500 {
            animation-delay: 0.5s;
            opacity: 0;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Ensure consistent card heights */
        .product-card {
            min-height: 480px;
            display: flex;
            flex-direction: column;
        }
        
        /* Fixed height for product images */
        .product-image {
            height: 240px;
            width: 100%;
            object-fit: cover;
        }
        
        /* Fixed height for product name area */
        .product-name-area {
            height: 3.5rem;
            display: flex;
            align-items: flex-start;
        }

        /* Enhanced Scroll Animations */
        .product-card {
            min-height: 480px;
            display: flex;
            flex-direction: column;
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .product-card.animate-in {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Staggered Animation */
        .product-card.animate-in:nth-child(1) { animation-delay: 0.1s; }
        .product-card.animate-in:nth-child(2) { animation-delay: 0.2s; }
        .product-card.animate-in:nth-child(3) { animation-delay: 0.3s; }
        .product-card.animate-in:nth-child(4) { animation-delay: 0.4s; }
        .product-card.animate-in:nth-child(5) { animation-delay: 0.5s; }
        .product-card.animate-in:nth-child(6) { animation-delay: 0.6s; }
        .product-card.animate-in:nth-child(7) { animation-delay: 0.7s; }
        .product-card.animate-in:nth-child(8) { animation-delay: 0.8s; }

        /* Floating Animation for Icons */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Pulse Animation for Badges */
        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .pulse-soft {
            animation: pulse-soft 2s ease-in-out infinite;
        }

        /* Smooth Page Transitions */
        .page-transition {
            animation: pageEnter 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        @keyframes pageEnter {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading Shimmer Effect */
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const card = entry.target;
                        const index = parseInt(card.dataset.index) || 0;
                        
                        // Stagger the animation based on index
                        setTimeout(() => {
                            card.classList.add('animate-in');
                        }, index * 100);
                        
                        // Unobserve once animated
                        observer.unobserve(card);
                    }
                });
            }, observerOptions);

            // Observe all product cards
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                observer.observe(card);
            });

            // Add floating animation to icons with delay
            setTimeout(() => {
                const icons = document.querySelectorAll('.fas.fa-heart, .fas.fa-shopping-bag, .fas.fa-star');
                icons.forEach((icon, index) => {
                    setTimeout(() => {
                        icon.classList.add('floating');
                    }, index * 200);
                });
            }, 1000);

            // Add pulse animation to badges
            const badges = document.querySelectorAll('.bg-orange-500, .bg-red-500, .bg-blue-600');
            badges.forEach((badge, index) => {
                setTimeout(() => {
                    badge.classList.add('pulse-soft');
                }, index * 300);
            });

            // Smooth scroll behavior for pagination
            const paginationLinks = document.querySelectorAll('.pagination a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    // Add loading effect
                    document.getElementById('products-grid').classList.add('shimmer');
                });
            });

            // Add page transition class
            document.body.classList.add('page-transition');

            // Enhanced hover effects for product cards
            productCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-12px) scale(1.03)';
                    this.style.boxShadow = '0 25px 50px rgba(0, 0, 0, 0.15)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    this.style.boxShadow = '';
                });
            });

            // Parallax effect for header elements (if any)
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const header = document.querySelector('.bg-gradient-to-r');
                
                if (header) {
                    const rate = scrolled * -0.5;
                    header.style.transform = `translateY(${rate}px)`;
                }
            });

            // Livewire loading states enhancement
            document.addEventListener('livewire:load', function () {
                Livewire.hook('message.sent', () => {
                    // Add loading shimmer to product grid
                    const grid = document.getElementById('products-grid');
                    if (grid) {
                        grid.style.opacity = '0.7';
                        grid.classList.add('shimmer');
                    }
                });

                Livewire.hook('message.processed', () => {
                    // Remove loading shimmer and re-animate products
                    const grid = document.getElementById('products-grid');
                    if (grid) {
                        grid.style.opacity = '1';
                        grid.classList.remove('shimmer');
                        
                        // Re-observe new product cards
                        const newCards = grid.querySelectorAll('.product-card:not(.animate-in)');
                        newCards.forEach((card, index) => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(30px)';
                            
                            setTimeout(() => {
                                card.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                                card.classList.add('animate-in');
                            }, index * 100);
                        });
                    }
                });
            });
        });
    </script>
</div><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/livewire/product-catalog.blade.php ENDPATH**/ ?>