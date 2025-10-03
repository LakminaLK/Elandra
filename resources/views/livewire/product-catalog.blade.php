<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    {{-- Loading Overlay --}}
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 flex items-center space-x-4 shadow-2xl animate-scale-in">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
            <div class="text-gray-700">
                <p class="font-semibold text-lg">Loading products...</p>
                <p class="text-sm text-gray-500">Please wait while we fetch the latest products</p>
            </div>
        </div>
    </div>

    {{-- Success Messages --}}
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center shadow-sm animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-emerald-600 text-lg"></i>
            {{ session('message') }}
        </div>
    @endif

    {{-- Professional Page Header --}}
    <div class="relative mb-12 py-16 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white to-transparent opacity-5"></div>
        
        {{-- Content --}}
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
        
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-x-16 -translate-y-16"></div>
        <div class="absolute bottom-0 right-0 w-48 h-48 bg-white bg-opacity-5 rounded-full translate-x-24 translate-y-24"></div>
    </div>

    {{-- Enhanced Filters Section --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 mb-8 animate-fade-in delay-300">
        {{-- Filter Header --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-medium text-gray-800 flex items-center">
                <i class="fas fa-sliders-h mr-2 text-blue-600"></i>
                Filter Products
            </h2>
            <div class="text-sm text-gray-500">
                <span class="font-medium text-gray-700">{{ $products->count() }}</span> products
            </div>
        </div>

        {{-- Filter Controls --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Search --}}
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
                    {{-- Search Loading Indicator --}}
                    <div wire:loading.delay wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                    </div>
                </div>
            </div>

            {{-- Category Filter --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-600">
                    Category
                </label>
                <div class="relative">
                    <select wire:model.live="filterCategory" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white transition-colors duration-200">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <div wire:loading.delay wire:target="filterCategory" class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                        <i wire:loading.delay.remove wire:target="filterCategory" class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>

            {{-- Brand Filter --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-600">
                    Brand
                </label>
                <div class="relative">
                    <select wire:model.live="filterBrand" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white transition-colors duration-200">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}">{{ ucfirst($brand) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <div wire:loading.delay wire:target="filterBrand" class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                        <i wire:loading.delay.remove wire:target="filterBrand" class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Section --}}
    <div wire:loading.class="opacity-60" wire:target="filterCategory,filterBrand,search" 
         class="transition-all duration-500">
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12" id="products-grid">
                @foreach($products as $index => $product)
                    <div class="product-card group bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-200 overflow-hidden transition-shadow duration-300 flex flex-col h-full"
                         data-index="{{ $index }}">
                        
                        {{-- Product Image - Fixed aspect ratio --}}
                        <div class="relative aspect-square overflow-hidden bg-gray-50 flex-shrink-0">
                            <a href="{{ route('products.show', $product->slug ?? $product->_id) }}" 
                               class="block w-full h-full">
                                @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                                    <img src="{{ Storage::url($product->images[0]) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-5xl text-gray-300"></i>
                                    </div>
                                @endif
                            </a>
                            
                            {{-- Product Badges --}}
                            <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                @if(isset($product->is_featured) && $product->is_featured)
                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded font-medium shadow-sm">
                                        Featured
                                    </span>
                                @endif
                                @if(isset($product->stock_quantity) && $product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                    <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded font-medium shadow-sm">
                                        Only {{ $product->stock_quantity }} left
                                    </span>
                                @endif
                                @if(isset($product->stock_quantity) && $product->stock_quantity <= 0)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-medium shadow-sm">
                                        Sold Out
                                    </span>
                                @endif
                            </div>

                            {{-- Wishlist Button --}}
                            <button class="absolute top-3 right-3 w-8 h-8 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full flex items-center justify-center text-gray-500 hover:text-red-500 transition-all duration-200 shadow-sm">
                                <i class="fas fa-heart text-sm"></i>
                            </button>
                        </div>

                        {{-- Product Info --}}
                        <div class="p-5 flex flex-col h-full">
                            {{-- Category & Brand Tags --}}
                            <div class="flex items-center space-x-2 mb-3 min-h-[24px]">
                                @if($product->category)
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium">
                                        {{ ucfirst($product->category) }}
                                    </span>
                                @endif
                                @if($product->brand)
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium">
                                        {{ ucfirst($product->brand) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Product Name - Fixed 2 lines --}}
                            <div class="mb-4 flex-grow">
                                <h3 class="font-medium text-lg text-gray-900 line-clamp-2 leading-tight h-[3.5rem] overflow-hidden">
                                    <a href="{{ route('products.show', $product->slug ?? $product->_id) }}" 
                                       class="hover:text-blue-600 transition-colors duration-200">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                            </div>

                            {{-- Price & Stock Info --}}
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        ${{ number_format($product->price, 2) }}
                                    </div>
                                </div>
                                <div class="min-h-[20px]">
                                    @if(isset($product->stock_quantity))
                                        @if($product->stock_quantity > 0)
                                            <div class="text-sm text-green-600 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                In Stock
                                            </div>
                                        @else
                                            <div class="text-sm text-red-600 font-medium">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Out of Stock
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- Action Buttons - Fixed position at bottom --}}
                            <div class="mt-auto">
                                @if(!isset($product->stock_quantity) || $product->stock_quantity > 0)
                                    @if($this->canAddToCart($product))
                                        <button wire:click="addToCart('{{ $product->_id }}')" 
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 group">
                                            <i class="fas fa-shopping-bag text-sm"></i>
                                            <span>Add to Cart</span>
                                            @if($this->getCartQuantity($product->_id) > 0)
                                                <span class="ml-2 px-2 py-1 bg-blue-800 rounded-full text-xs">{{ $this->getCartQuantity($product->_id) }}</span>
                                            @endif
                                            <div wire:loading.delay wire:target="addToCart('{{ $product->_id }}')" class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent ml-2"></div>
                                        </button>
                                    @else
                                        @php
                                            $cartQty = $this->getCartQuantity($product->_id);
                                            $maxQty = min(3, $product->stock_quantity ?? 3);
                                        @endphp
                                        <button disabled 
                                                class="w-full bg-gray-200 text-gray-500 py-3 rounded-lg font-medium cursor-not-allowed flex items-center justify-center space-x-2">
                                            <i class="fas fa-check-circle text-sm"></i>
                                            <span>Max Qty ({{ $cartQty }}/{{ $maxQty }})</span>
                                        </button>
                                    @endif
                                @else
                                    <button disabled 
                                            class="w-full bg-gray-200 text-gray-500 py-3 rounded-lg font-medium cursor-not-allowed flex items-center justify-center space-x-2">
                                        <i class="fas fa-times-circle text-sm"></i>
                                        <span>Out of Stock</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            <div class="flex justify-center animate-fade-in delay-500">
                {{ $products->links() }}
            </div>
        @else
            {{-- Empty State --}}
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
        @endif
    </div>

    {{-- Custom Animations --}}
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

        /* Simplified Product Card Styles */
        .product-card {
            min-height: 480px;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .product-card.animate-in:nth-child(8) { animation-delay: 0.8s; }

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
            // Simple hover effects for product cards - no complex animations
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                // Cards are visible by default, no animation needed
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            });

            // Icons and badges are now static - no auto-dancing animations

            // Handle pagination clicks to ensure products remain visible
            document.addEventListener('click', (e) => {
                if (e.target.matches('.pagination a, .pagination a *')) {
                    // After pagination, ensure products are visible
                    setTimeout(() => {
                        const productCards = document.querySelectorAll('.product-card');
                        productCards.forEach(card => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        });
                    }, 100);
                }
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

            // Ensure products remain visible after Livewire updates
            document.addEventListener('livewire:load', function () {
                Livewire.hook('message.processed', () => {
                    // Ensure all product cards are visible after any Livewire update
                    const productCards = document.querySelectorAll('.product-card');
                    productCards.forEach(card => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                    
                    // Ensure grid is always visible
                    const grid = document.getElementById('products-grid');
                    if (grid) {
                        grid.style.opacity = '1';
                    }
                });
            });
        });
    </script>
</div>