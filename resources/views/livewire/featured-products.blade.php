<div>
    @if($featuredProducts->count() > 0)
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
            @if (session()->has('message'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative animate-fade-in" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('message') }}
                </div>
            @endif
            
            @if (session()->has('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate-fade-in" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Products Grid - Preserve AOS animations -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" wire:loading.class="opacity-75" wire:target="addToCart">
                @foreach($featuredProducts as $product)
                <div class="product-card group bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-200 overflow-hidden transition-all duration-300 flex flex-col h-full" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ $loop->index * 100 }}"
                     wire:key="product-{{ $product->_id }}"
                     wire:ignore.self>
                    
                    {{-- Product Image - Fixed aspect ratio --}}
                    <div class="relative aspect-square overflow-hidden bg-gray-50 flex-shrink-0" wire:ignore>
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
                        <div class="flex items-center space-x-2 mb-3 min-h-[24px]" wire:ignore>
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
                        <div class="mb-4 flex-grow" wire:ignore>
                            <h3 class="font-medium text-lg text-gray-900 line-clamp-2 leading-tight h-[3.5rem] overflow-hidden">
                                <a href="{{ route('products.show', $product->slug ?? $product->_id) }}" 
                                   class="hover:text-blue-600 transition-colors duration-200">
                                    {{ $product->name }}
                                </a>
                            </h3>
                        </div>

                        {{-- Price & Stock Info --}}
                        <div class="mb-4" wire:ignore>
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

                        {{-- Action Buttons - Only this section will update --}}
                        <div class="mt-auto" wire:key="button-{{ $product->_id }}">
                            @if(!isset($product->stock_quantity) || $product->stock_quantity > 0)
                                @if($this->canAddToCart($product))
                                    <button wire:click="addToCart('{{ $product->_id }}')" 
                                            wire:loading.attr="disabled"
                                            wire:target="addToCart('{{ $product->_id }}')"
                                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white py-3 rounded-lg font-medium transition-all duration-200 flex items-center justify-center space-x-2 group">
                                        
                                        <!-- Default State -->
                                        <div wire:loading.remove wire:target="addToCart('{{ $product->_id }}')">
                                            <i class="fas fa-shopping-bag text-sm"></i>
                                            <span>Add to Cart</span>
                                            @if($this->getCartQuantity($product->_id) > 0)
                                                <span class="ml-2 px-2 py-1 bg-blue-800 rounded-full text-xs">{{ $this->getCartQuantity($product->_id) }}</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Loading State -->
                                        <div wire:loading wire:target="addToCart('{{ $product->_id }}')" class="flex items-center space-x-2">
                                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></div>
                                            <span>Adding...</span>
                                        </div>
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
            
            <!-- Static Footer - Won't re-render -->
            <div class="text-center mt-12" data-aos="fade-up" wire:ignore>
                <a href="{{ route('products.index') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-store mr-3"></i>
                    View All Products
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </section>
    @endif
</div>
