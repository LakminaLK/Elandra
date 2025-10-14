

<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="relative overflow-hidden min-h-screen flex items-center bg-gradient-to-br from-slate-50 via-white to-blue-50/30">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="<?php echo e(asset('images/hero-elegant-handbag.jpg')); ?>" alt="Luxury Fashion Background" class="w-full h-full object-cover opacity-12">
        <div class="absolute inset-0 bg-gradient-to-br from-white/90 via-white/75 to-blue-50/60"></div>
    </div>
    
    <!-- Clean Decorative Background -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Subtle animated shapes -->
        <div class="absolute top-20 right-20 w-72 h-72 bg-gradient-to-br from-blue-100/40 to-indigo-100/40 rounded-full blur-3xl animate-float opacity-60"></div>
        <div class="absolute bottom-20 left-20 w-96 h-96 bg-gradient-to-br from-purple-50/50 to-pink-50/50 rounded-full blur-3xl animate-float opacity-50" style="animation-duration: 8s; animation-delay: 2s;"></div>
        
        <!-- Subtle floating dots -->
        <div class="absolute top-1/3 left-1/5 w-2 h-2 bg-blue-300/40 rounded-full animate-float opacity-60"></div>
        <div class="absolute top-2/3 right-1/5 w-3 h-3 bg-indigo-300/40 rounded-full animate-float opacity-50" style="animation-delay: 3s;"></div>
        <div class="absolute bottom-1/3 right-1/3 w-2 h-2 bg-purple-300/40 rounded-full animate-float opacity-40" style="animation-delay: 1.5s;"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Content -->
            <div class="text-left space-y-8">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight tracking-tight">
                        <span class="bg-gradient-to-r from-slate-800 via-blue-900 to-indigo-900 bg-clip-text text-transparent">Luxury</span>
                        <br>
                        <span class="text-gray-900 font-light">Redefined</span>
                    </h1>
                </div>
                
                <div data-aos="fade-right" data-aos-delay="200" data-aos-duration="1000">
                    <p class="text-xl md:text-2xl mb-8 text-gray-700 leading-relaxed font-normal max-w-2xl">
                        Discover our exquisite collection of 
                        <span class="font-semibold text-blue-800">handcrafted luxury handbags</span> 
                        that blend timeless elegance with modern sophistication.
                    </p>
                </div>
                
                <div data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000">
                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <a href="<?php echo e(route('products.index')); ?>" 
                           class="group bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <i class="fas fa-shopping-bag mr-3"></i>
                            Shop Collection
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#categories" 
                           class="group border-2 border-slate-300 text-slate-700 hover:bg-slate-100 px-8 py-4 rounded-lg font-semibold text-lg transition-all transform hover:scale-105 flex items-center justify-center hover:border-slate-400">
                            <i class="fas fa-eye mr-3"></i>
                            Explore Styles
                        </a>
                    </div>
                </div>
                
                <!-- Stats -->
                <div data-aos="fade-up" data-aos-delay="600" data-aos-duration="1000" 
                     class="grid grid-cols-3 gap-8 mt-12 pt-8 border-t border-gray-200">
                    
                    <div class="text-center group">
                        <div class="text-4xl font-bold text-blue-600 mb-2 group-hover:scale-110 transition-transform">500+</div>
                        <div class="text-gray-600 text-sm font-medium">Premium Products</div>
                    </div>
                    <div class="text-center group">
                        <div class="text-4xl font-bold text-blue-600 mb-2 group-hover:scale-110 transition-transform">50K+</div>
                        <div class="text-gray-600 text-sm font-medium">Happy Customers</div>
                    </div>
                    <div class="text-center group">
                        <div class="text-4xl font-bold text-blue-600 mb-2 group-hover:scale-110 transition-transform">25+</div>
                        <div class="text-gray-600 text-sm font-medium">Countries Served</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Content - Product Showcase -->
            <div class="relative" data-aos="fade-left" data-aos-duration="1200">
                <div class="relative">
                    <!-- Clean background decoration -->
                    <div class="absolute -inset-8 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 rounded-3xl blur-2xl"></div>
                    
                    <!-- Main Product Display Area -->
                    <div class="relative bg-white rounded-2xl p-8 shadow-2xl border border-gray-100 group hover:shadow-3xl transition-all duration-700">
                        <!-- Hero Image -->
                        <div class="rounded-xl h-80 overflow-hidden group-hover:scale-105 transition-transform duration-500">
                            <img src="<?php echo e(asset('images/hero-luxury-handbag.jpg')); ?>" 
                                 alt="Elandra Luxury Handbags Collection" 
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='<?php echo e(asset('images/hero-premium-bag.jpg')); ?>';">
                        </div>
                        
                        <!-- Clean badges -->
                        <div class="absolute -top-3 -right-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg animate-pulse">
                            <i class="fas fa-sparkles mr-1"></i>
                            New Arrival
                        </div>
                        
                        <div class="absolute -bottom-3 -left-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-3 rounded-full shadow-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-crown text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Featured Products -->
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('featured-products');

$__html = app('livewire')->mount($__name, $__params, 'lw-1008348124-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<!-- Testimonials Section -->
<section class="py-20 bg-gradient-to-b from-white to-gray-50" data-aos="fade-up">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-flex items-center bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <i class="fas fa-quote-left mr-2"></i>
                Testimonials
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                What Our Customers <span class="text-blue-600">Say</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Discover why thousands of fashion lovers trust Elandra for their luxury handbag needs.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center mb-6">
                    <img src="<?php echo e(asset('images/users/sarah-johnson.jpg')); ?>" alt="Sarah Johnson - Fashion Blogger" class="w-12 h-12 rounded-full object-cover mr-4 ring-2 ring-blue-100">
                    <div>
                        <h4 class="font-semibold text-gray-900">Sarah Johnson</h4>
                        <p class="text-gray-600 text-sm">Fashion Blogger</p>
                    </div>
                    <div class="ml-auto flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "Absolutely love my Elandra handbag! The quality is exceptional and it goes perfectly with any outfit. The craftsmanship is truly remarkable."
                </p>
                <div class="text-blue-600 font-medium text-sm">Verified Purchase</div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center mb-6">
                    <img src="<?php echo e(asset('images/users/emma-williams.jpg')); ?>" alt="Emma Williams - Executive" class="w-12 h-12 rounded-full object-cover mr-4 ring-2 ring-blue-100">
                    <div>
                        <h4 class="font-semibold text-gray-900">Emma Williams</h4>
                        <p class="text-gray-600 text-sm">Executive</p>
                    </div>
                    <div class="ml-auto flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "Fast shipping, beautiful packaging, and a stunning handbag that exceeded my expectations. Elandra has become my go-to for luxury accessories."
                </p>
                <div class="text-blue-600 font-medium text-sm">Verified Purchase</div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center mb-6">
                    <img src="<?php echo e(asset('images/users/lisa-chen.jpg')); ?>" alt="Lisa Chen - Designer" class="w-12 h-12 rounded-full object-cover mr-4 ring-2 ring-blue-100">
                    <div>
                        <h4 class="font-semibold text-gray-900">Lisa Chen</h4>
                        <p class="text-gray-600 text-sm">Designer</p>
                    </div>
                    <div class="ml-auto flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "The attention to detail is incredible. Every stitch, every finish is perfect. This is true luxury at its finest. Highly recommended!"
                </p>
                <div class="text-blue-600 font-medium text-sm">Verified Purchase</div>
            </div>
        </div>
        
        <div class="text-center mt-12" data-aos="fade-up">
            <div class="flex items-center justify-center space-x-8">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">4.9</div>
                    <div class="text-sm text-gray-600">Average Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">2,500+</div>
                    <div class="text-sm text-gray-600">Happy Customers</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">99%</div>
                    <div class="text-sm text-gray-600">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Newsletter Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700" data-aos="fade-up">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
            <i class="fas fa-envelope text-4xl text-white mb-4"></i>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Stay Updated</h2>
            <p class="text-white/90 mb-8 text-lg max-w-2xl mx-auto">
                Be the first to know about new arrivals, exclusive offers, and style tips from our luxury handbag collection.
            </p>
            
            <form class="max-w-lg mx-auto">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Enter your email address" 
                           class="flex-1 px-6 py-4 rounded-xl bg-white/95 backdrop-blur-sm border-0 focus:ring-2 focus:ring-white/50 focus:outline-none text-gray-900 placeholder-gray-500">
                    <button type="submit" class="bg-white text-indigo-600 px-8 py-4 rounded-xl hover:bg-gray-50 transition-colors font-semibold shadow-lg">
                        Subscribe Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Brand Story Section -->
<section class="py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden relative">
    <div class="absolute inset-0 opacity-20">
        <img src="<?php echo e(asset('images/fashion-stairs.jpg')); ?>" alt="Luxury Fashion" class="w-full h-full object-cover">
    </div>
    <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <div class="inline-flex items-center bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    <i class="fas fa-heart mr-2"></i>
                    Our Story
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-8 leading-tight">
                    Crafting <span class="text-yellow-400">Elegance</span><br>
                    Since 2015
                </h2>
                <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                    Born from a passion for timeless elegance and exceptional craftsmanship, Elandra represents the pinnacle of luxury handbag design. Each piece tells a story of dedication, artistry, and uncompromising quality.
                </p>
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-400 mb-2">8+</div>
                        <div class="text-gray-400 text-sm">Years of Excellence</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-400 mb-2">100%</div>
                        <div class="text-gray-400 text-sm">Handcrafted</div>
                    </div>
                </div>
                <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center bg-yellow-400 text-gray-900 px-8 py-4 rounded-xl font-semibold hover:bg-yellow-300 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-sparkles mr-3"></i>
                    Discover Our Craft
                </a>
            </div>
            
            <div class="relative" data-aos="fade-left">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <img src="<?php echo e(asset('images/story/luxury-craftsmanship.jpg')); ?>" alt="Luxury Craftsmanship" class="w-full h-48 object-cover rounded-2xl shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-500 hover:scale-105">
                        <img src="<?php echo e(asset('images/story/fashion-excellence.jpg')); ?>" alt="Fashion Excellence" class="w-full h-32 object-cover rounded-2xl shadow-2xl transform -rotate-2 hover:rotate-0 transition-transform duration-500 hover:scale-105">
                    </div>
                    <div class="space-y-4 mt-8">
                        <img src="<?php echo e(asset('images/story/elegant-style.jpg')); ?>" alt="Elegant Style" class="w-full h-32 object-cover rounded-2xl shadow-2xl transform rotate-1 hover:rotate-0 transition-transform duration-500 hover:scale-105">
                        <img src="<?php echo e(asset('images/story/premium-quality.jpg')); ?>" alt="Premium Quality" class="w-full h-48 object-cover rounded-2xl shadow-2xl transform -rotate-1 hover:rotate-0 transition-transform duration-500 hover:scale-105">
                    </div>
                </div>
                
                <!-- Floating badges for added elegance -->
                <div class="absolute -top-4 -right-4 bg-yellow-400 text-gray-900 px-4 py-2 rounded-full text-sm font-bold shadow-lg animate-pulse">
                    <i class="fas fa-crown mr-1"></i>
                    Premium
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                    <i class="fas fa-award mr-1"></i>
                    Handcrafted
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust & Guarantee Section -->
<section class="py-16 bg-gradient-to-r from-green-50 to-emerald-50" data-aos="fade-up">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-green-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Our Promise
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        100% Satisfaction<br>
                        <span class="text-green-600">Guaranteed</span>
                    </h2>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                        We stand behind every handbag we create. If you're not completely satisfied with your purchase, we'll make it right with our comprehensive warranty and return policy.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">Lifetime craftsmanship guarantee</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">30-day money-back guarantee</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">Free repair service for one year</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="<?php echo e(asset('images/story/luxury-craftsmanship.jpg')); ?>" alt="Quality Guarantee" class="w-full rounded-2xl shadow-2xl hover:scale-105 transition-transform duration-300">
                    <div class="absolute -bottom-6 -right-6 bg-green-500 text-white p-4 rounded-2xl shadow-xl">
                        <div class="text-center">
                            <div class="text-2xl font-bold">4.9★</div>
                            <div class="text-sm opacity-90">Rated</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gradient-to-b from-white to-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Why Choose <span class="text-blue-600">Elandra</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Experience luxury shopping with confidence and unmatched service excellence.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center group" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-gem text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Premium Quality</h3>
                <p class="text-gray-600 leading-relaxed">Handcrafted with the finest materials and attention to detail for lasting elegance.</p>
            </div>
            
            <div class="text-center group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-undo-alt text-2xl text-emerald-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Free Returns</h3>
                <p class="text-gray-600 leading-relaxed">30-day hassle-free return policy for your complete satisfaction and peace of mind.</p>
            </div>
            
            <div class="text-center group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-shipping-fast text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Fast Shipping</h3>
                <p class="text-gray-600 leading-relaxed">Free shipping on orders over $200 with express delivery options worldwide.</p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/home.blade.php ENDPATH**/ ?>