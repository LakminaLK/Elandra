

<?php $__env->startSection('title', $product->name . ' - Product Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="mb-8 animate-slide-down" style="animation-delay: 0.1s;">
            <nav class="text-sm">
                <a href="<?php echo e(route('home')); ?>" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Home</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="<?php echo e(route('products.index')); ?>" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Products</a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900 font-medium"><?php echo e($product->name); ?></span>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4 animate-slide-right" style="animation-delay: 0.2s;">
                <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden shadow-lg relative group">
                    <?php if(isset($product->images) && is_array($product->images) && count($product->images) > 0): ?>
                        <img src="<?php echo e(Storage::url($product->images[0])); ?>" 
                             alt="<?php echo e($product->name); ?>"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             id="mainImage">
                    <?php elseif($product->image_url): ?>
                        <img src="<?php echo e($product->image_url); ?>" 
                             alt="<?php echo e($product->name); ?>"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             id="mainImage">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-6xl text-gray-300 animate-pulse"></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Image Overlay Effects -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <!-- Zoom Icon -->
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full p-3 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 cursor-pointer hover:bg-white">
                        <i class="fas fa-search-plus text-gray-700"></i>
                    </div>
                </div>

                <!-- Thumbnail Images -->
                <?php if(isset($product->images) && is_array($product->images) && count($product->images) > 1): ?>
                    <div class="grid grid-cols-4 gap-4 animate-fade-in" style="animation-delay: 0.4s;">
                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer border-2 transition-all duration-300 transform hover:scale-105 <?php echo e($index === 0 ? 'border-blue-500 shadow-lg' : 'border-transparent hover:border-blue-300 hover:shadow-md'); ?>"
                                 onclick="changeMainImage('<?php echo e(Storage::url($image)); ?>', this)"
                                 style="animation-delay: <?php echo e(0.5 + ($index * 0.1)); ?>s;">
                                <img src="<?php echo e(Storage::url($image)); ?>" 
                                     alt="<?php echo e($product->name); ?>"
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Details -->
            <div class="space-y-6 animate-slide-left" style="animation-delay: 0.3s;">
                <div class="animate-fade-in" style="animation-delay: 0.4s;">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text"><?php echo e($product->name); ?></h1>
                    
                    <!-- Category and Brand -->
                    <div class="flex items-center gap-4 mb-6 animate-slide-up" style="animation-delay: 0.5s;">
                        <?php if($product->category): ?>
                            <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium shadow-md transform hover:scale-105 transition-transform duration-200"><?php echo e(ucfirst($product->category)); ?></span>
                        <?php endif; ?>
                        <?php if($product->brand): ?>
                            <span class="text-gray-600 text-sm bg-gray-50 px-3 py-1 rounded-lg"><strong>Brand:</strong> <?php echo e(ucfirst($product->brand)); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- SKU -->
                    <?php if($product->sku): ?>
                        <p class="text-gray-600 text-sm mb-4 animate-fade-in" style="animation-delay: 0.6s;"><strong>SKU:</strong> <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?php echo e($product->sku); ?></span></p>
                    <?php endif; ?>
                </div>

                <!-- Price -->
                <div class="border-t border-b border-gray-200 py-6">
                    <div class="flex items-center gap-4">
                        <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                            <span class="text-3xl font-bold text-green-600">$<?php echo e(number_format($product->sale_price, 2)); ?></span>
                            <span class="text-xl text-gray-500 line-through">$<?php echo e(number_format($product->price, 2)); ?></span>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">
                                <?php echo e(round((($product->price - $product->sale_price) / $product->price) * 100)); ?>% OFF
                            </span>
                        <?php else: ?>
                            <span class="text-3xl font-bold text-gray-900">$<?php echo e(number_format($product->price, 2)); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="flex items-center gap-2">
                    <?php if(isset($product->stock_quantity) && $product->stock_quantity > 0): ?>
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="text-green-600 font-medium">In Stock</span>
                        <?php if($product->stock_quantity <= 5): ?>
                            <span class="text-orange-600 text-sm">(Only <?php echo e($product->stock_quantity); ?> left)</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <i class="fas fa-times-circle text-red-600"></i>
                        <span class="text-red-600 font-medium">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <?php if($product->description): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <div class="text-gray-700 prose max-w-none">
                            <?php echo nl2br(e($product->description)); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <!-- Specifications -->
                <?php if($product->weight || (isset($product->dimensions) && $product->dimensions)): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Specifications</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <?php if($product->weight): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Weight:</span>
                                    <span class="font-medium"><?php echo e($product->weight); ?> kg</span>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($product->dimensions) && is_array($product->dimensions)): ?>
                                <?php if(isset($product->dimensions['length'])): ?>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Length:</span>
                                        <span class="font-medium"><?php echo e($product->dimensions['length']); ?> cm</span>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($product->dimensions['width'])): ?>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Width:</span>
                                        <span class="font-medium"><?php echo e($product->dimensions['width']); ?> cm</span>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($product->dimensions['height'])): ?>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Height:</span>
                                        <span class="font-medium"><?php echo e($product->dimensions['height']); ?> cm</span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Add to Cart -->
                <div class="space-y-4">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(!isset($product->stock_quantity) || $product->stock_quantity > 0): ?>
                            <button onclick="addToCart('<?php echo e($product->_id); ?>')"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg font-medium text-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Add to Cart</span>
                            </button>
                        <?php else: ?>
                            <button disabled 
                                    class="w-full bg-gray-300 text-gray-500 py-4 px-6 rounded-lg font-medium text-lg cursor-not-allowed">
                                <i class="fas fa-times-circle mr-2"></i>
                                Out of Stock
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" 
                           class="block w-full bg-gray-600 hover:bg-gray-700 text-white py-4 px-6 rounded-lg font-medium text-lg text-center transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login to Purchase
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('products.index')); ?>" 
                       class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-medium text-center transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Product Page Animations */
@keyframes slide-down {
    from { 
        opacity: 0; 
        transform: translateY(-30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes slide-up {
    from { 
        opacity: 0; 
        transform: translateY(30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes slide-right {
    from { 
        opacity: 0; 
        transform: translateX(-50px); 
    }
    to { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

@keyframes slide-left {
    from { 
        opacity: 0; 
        transform: translateX(50px); 
    }
    to { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

@keyframes fade-in {
    from { 
        opacity: 0; 
    }
    to { 
        opacity: 1; 
    }
}

@keyframes scale-in {
    from { 
        opacity: 0; 
        transform: scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: scale(1); 
    }
}

.animate-slide-down {
    animation: slide-down 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    opacity: 0;
}

.animate-slide-up {
    animation: slide-up 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    opacity: 0;
}

.animate-slide-right {
    animation: slide-right 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    opacity: 0;
}

.animate-slide-left {
    animation: slide-left 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    opacity: 0;
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out forwards;
    opacity: 0;
}

.animate-scale-in {
    animation: scale-in 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    opacity: 0;
}

/* Enhanced Button Animations */
.btn-animated {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.btn-animated::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-animated:hover::before {
    left: 100%;
}

.btn-animated:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Image Zoom Effect */
.zoom-container {
    position: relative;
    overflow: hidden;
    cursor: zoom-in;
}

.zoom-image {
    transition: transform 0.3s ease;
}

.zoom-container:hover .zoom-image {
    transform: scale(1.1);
}

/* Floating Labels */
.floating-label {
    transform: translateY(0);
    transition: all 0.3s ease;
}

.floating-label:hover {
    transform: translateY(-2px);
}

/* Staggered Animations */
.stagger-animation > * {
    opacity: 0;
    transform: translateY(20px);
    animation: slide-up 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

.stagger-animation > *:nth-child(1) { animation-delay: 0.1s; }
.stagger-animation > *:nth-child(2) { animation-delay: 0.2s; }
.stagger-animation > *:nth-child(3) { animation-delay: 0.3s; }
.stagger-animation > *:nth-child(4) { animation-delay: 0.4s; }
.stagger-animation > *:nth-child(5) { animation-delay: 0.5s; }
.stagger-animation > *:nth-child(6) { animation-delay: 0.6s; }

/* Pulse Effect */
.pulse-glow {
    animation: pulse-glow 2s ease-in-out infinite alternate;
}

@keyframes pulse-glow {
    from {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
    }
    to {
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.6);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced image change function with smooth transition
    function changeMainImageEnhanced(imageSrc, thumbnail) {
        const mainImg = document.getElementById('mainImage');
        
        // Fade out current image
        mainImg.style.opacity = '0';
        mainImg.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            mainImg.src = imageSrc;
            
            // Fade in new image
            mainImg.style.opacity = '1';
            mainImg.style.transform = 'scale(1)';
            
            // Update thumbnail borders with animation
            document.querySelectorAll('.aspect-square.cursor-pointer').forEach(el => {
                el.style.transform = 'scale(1)';
                el.classList.remove('border-blue-500', 'shadow-lg');
                el.classList.add('border-transparent');
            });
            
            // Add active state to clicked thumbnail
            thumbnail.classList.remove('border-transparent');
            thumbnail.classList.add('border-blue-500', 'shadow-lg');
            thumbnail.style.transform = 'scale(1.05)';
        }, 150);
    }

    // Replace the old function
    window.changeMainImage = changeMainImageEnhanced;

    // Enhanced add to cart with loading animation
    window.addToCart = function(productId) {
        const button = event.target;
        const originalText = button.innerHTML;
        
        // Add loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
        button.disabled = true;
        button.style.transform = 'scale(0.98)';
        
        // Simulate API call
        setTimeout(() => {
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Added to Cart!';
            button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            
            // Reset button after delay
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.style.transform = 'scale(1)';
                button.style.background = '';
            }, 2000);
        }, 1000);
    };

    // Add staggered animation to elements
    function addStaggeredAnimation() {
        const elements = document.querySelectorAll('.space-y-6 > div');
        elements.forEach((el, index) => {
            el.style.animationDelay = `${0.2 + (index * 0.1)}s`;
            el.classList.add('animate-fade-in');
        });
    }

    // Parallax effect for breadcrumbs
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const breadcrumbs = document.querySelector('nav');
        if (breadcrumbs) {
            breadcrumbs.style.transform = `translateY(${scrolled * 0.1}px)`;
        }
    });

    // Add hover effects to buttons
    const buttons = document.querySelectorAll('button, a[class*="bg-"]');
    buttons.forEach(btn => {
        btn.classList.add('btn-animated');
        
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Add floating animation to price
    const priceElement = document.querySelector('.text-3xl.font-bold');
    if (priceElement) {
        priceElement.style.transition = 'all 0.3s ease';
        priceElement.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.textShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
        });
        priceElement.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.textShadow = 'none';
        });
    }

    // Add intersection observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements that need scroll animation
    const elementsToObserve = document.querySelectorAll('.space-y-6 > div');
    elementsToObserve.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        observer.observe(el);
    });

    // Add page entrance animation
    document.body.style.opacity = '0';
    document.body.style.transform = 'translateY(20px)';
    document.body.style.transition = 'all 0.8s ease-out';
    
    setTimeout(() => {
        document.body.style.opacity = '1';
        document.body.style.transform = 'translateY(0)';
    }, 100);

    // Add loading shimmer effect to images while they load
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        if (!img.complete) {
            img.style.background = 'linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%)';
            img.style.backgroundSize = '200% 100%';
            img.style.animation = 'shimmer 1.5s infinite';
            
            img.onload = function() {
                this.style.background = 'none';
                this.style.animation = 'none';
            };
        }
    });

    // Add smooth transitions to all interactive elements
    const interactiveElements = document.querySelectorAll('button, a, input, select, .cursor-pointer');
    interactiveElements.forEach(el => {
        el.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    });
});

// Shimmer animation for loading states
const shimmerKeyframes = `
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}`;

// Inject shimmer keyframes
const style = document.createElement('style');
style.textContent = shimmerKeyframes;
document.head.appendChild(style);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/products/show.blade.php ENDPATH**/ ?>