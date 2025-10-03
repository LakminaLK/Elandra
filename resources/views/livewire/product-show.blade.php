<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="mb-8 animate-slide-down" style="animation-delay: 0.1s;">
            <nav class="text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Home</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Products</a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900 font-medium">{{ $product->name }}</span>
            </nav>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4 animate-slide-right" style="animation-delay: 0.2s;">
                <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden shadow-lg relative group">
                    @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                        <img src="{{ Storage::url($product->images[$selectedImage]) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @elseif($product->image_url)
                        <img src="{{ $product->image_url }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-6xl text-gray-300"></i>
                        </div>
                    @endif
                    
                    <!-- Image Overlay Effects -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <!-- Zoom Icon -->
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer hover:bg-white">
                        <i class="fas fa-search-plus text-gray-700"></i>
                    </div>
                </div>

                <!-- Thumbnail Images -->
                @if(isset($product->images) && is_array($product->images) && count($product->images) > 1)
                    <div class="grid grid-cols-4 gap-4 animate-fade-in" style="animation-delay: 0.4s;">
                        @foreach($product->images as $index => $image)
                            <div wire:click="selectImage({{ $index }})"
                                 class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer border-2 transition-colors duration-300 {{ $index === $selectedImage ? 'border-blue-500 shadow-lg' : 'border-transparent hover:border-blue-300 hover:shadow-md' }}"
                                 style="animation-delay: {{ 0.5 + ($index * 0.1) }}s;">
                                <img src="{{ Storage::url($image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="space-y-6 animate-slide-left" style="animation-delay: 0.3s;">
                <div class="animate-fade-in" style="animation-delay: 0.4s;">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text">{{ $product->name }}</h1>
                    
                    <!-- Category and Brand -->
                    <div class="flex items-center gap-4 mb-6 animate-slide-up" style="animation-delay: 0.5s;">
                        @if($product->category)
                            <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium shadow-md transition-colors duration-200">{{ ucfirst($product->category) }}</span>
                        @endif
                        @if($product->brand)
                            <span class="text-gray-600 text-sm bg-gray-50 px-3 py-1 rounded-lg"><strong>Brand:</strong> {{ ucfirst($product->brand) }}</span>
                        @endif
                    </div>

                    <!-- SKU -->
                    @if($product->sku)
                        <p class="text-gray-600 text-sm mb-4 animate-fade-in" style="animation-delay: 0.6s;"><strong>SKU:</strong> <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $product->sku }}</span></p>
                    @endif
                </div>

                <!-- Price -->
                <div class="border-t border-b border-gray-200 py-6">
                    <div class="flex items-center gap-4">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="text-3xl font-bold text-green-600">${{ number_format($product->sale_price, 2) }}</span>
                            <span class="text-xl text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">
                                {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                            </span>
                        @else
                            <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="flex items-center gap-2">
                    @if(isset($product->stock_quantity) && $product->stock_quantity > 0)
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="text-green-600 font-medium">In Stock</span>
                        @if($product->stock_quantity <= 5)
                            <span class="text-orange-600 text-sm">(Only {{ $product->stock_quantity }} left)</span>
                        @endif
                    @else
                        <i class="fas fa-times-circle text-red-600"></i>
                        <span class="text-red-600 font-medium">Out of Stock</span>
                    @endif
                </div>



                <!-- Description -->
                @if($product->description)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <div class="text-gray-700 prose max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif

                <!-- Specifications -->
                @if($product->weight || (isset($product->dimensions) && $product->dimensions))
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Specifications</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            @if($product->weight)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Weight:</span>
                                    <span class="font-medium">{{ $product->weight }} kg</span>
                                </div>
                            @endif
                            @if(isset($product->dimensions) && is_array($product->dimensions))
                                @if(isset($product->dimensions['length']))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Length:</span>
                                        <span class="font-medium">{{ $product->dimensions['length'] }} cm</span>
                                    </div>
                                @endif
                                @if(isset($product->dimensions['width']))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Width:</span>
                                        <span class="font-medium">{{ $product->dimensions['width'] }} cm</span>
                                    </div>
                                @endif
                                @if(isset($product->dimensions['height']))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Height:</span>
                                        <span class="font-medium">{{ $product->dimensions['height'] }} cm</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Add to Cart -->
                <div class="space-y-4">
                    @if(!isset($product->stock_quantity) || $product->stock_quantity > 0)
                        @if($this->canAddToCart)
                            <button wire:click="addToCart"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg font-medium text-lg transition-colors duration-200 flex items-center justify-center space-x-2 disabled:opacity-50">
                                <div wire:loading.remove wire:target="addToCart">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Add to Cart
                                    @if($this->cartQuantity > 0)
                                        <span class="ml-2 px-2 py-1 bg-blue-800 rounded-full text-sm">{{ $this->cartQuantity }} in cart</span>
                                    @endif
                                </div>
                                <div wire:loading wire:target="addToCart">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Adding to Cart...
                                </div>
                            </button>
                        @else
                            <button disabled 
                                    class="w-full bg-gray-300 text-gray-500 py-4 px-6 rounded-lg font-medium text-lg cursor-not-allowed">
                                <i class="fas fa-check-circle mr-2"></i>
                                Maximum Quantity Reached ({{ $this->cartQuantity }}/{{ $this->maxQuantity }})
                            </button>
                        @endif
                    @else
                        <button disabled 
                                class="w-full bg-gray-300 text-gray-500 py-4 px-6 rounded-lg font-medium text-lg cursor-not-allowed">
                            <i class="fas fa-times-circle mr-2"></i>
                            Out of Stock
                        </button>
                    @endif

                    
                </div>
            </div>
        </div>
    </div>

    <!-- CSS Animations -->
    <style>
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
    </style>
</div>
