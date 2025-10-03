<div class="min-h-screen bg-gray-50">
    {{-- Loading Overlay --}}
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 flex items-center space-x-4 shadow-2xl">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
            <div class="text-gray-700">
                <p class="font-semibold text-lg">Processing...</p>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center shadow-sm animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-emerald-600 text-lg"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center shadow-sm animate-fade-in">
            <i class="fas fa-exclamation-circle mr-3 text-red-600 text-lg"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Home</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Products</a>
                    </li>
                    @if($product->category)
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                            <span class="text-gray-500">{{ ucfirst($product->category) }}</span>
                        </li>
                    @endif
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <span class="text-gray-900 font-medium">{{ \Str::limit($product->name, 30) }}</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Product Details --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 p-8">
                {{-- Product Images --}}
                <div class="space-y-6">
                    {{-- Main Image --}}
                    <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden">
                        @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                            <img src="{{ Storage::url($product->images[$selectedImageIndex]) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-8xl text-gray-300"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Image Thumbnails --}}
                    @if(isset($product->images) && is_array($product->images) && count($product->images) > 1)
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($product->images as $index => $image)
                                <button wire:click="selectImage({{ $index }})" 
                                        class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 transition-all duration-200 {{ $selectedImageIndex == $index ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200 hover:border-gray-300' }}">
                                    <img src="{{ Storage::url($image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Product Information --}}
                <div class="space-y-8">
                    {{-- Header --}}
                    <div class="space-y-4">
                        {{-- Category & Brand --}}
                        <div class="flex items-center space-x-3">
                            @if($product->category)
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($product->category) }}
                                </span>
                            @endif
                            @if($product->brand)
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($product->brand) }}
                                </span>
                            @endif
                            @if(isset($product->is_featured) && $product->is_featured)
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-star mr-1"></i>
                                    Featured
                                </span>
                            @endif
                        </div>

                        {{-- Product Name --}}
                        <h1 class="text-3xl font-semibold text-gray-900 leading-tight">{{ $product->name }}</h1>

                        {{-- SKU --}}
                        @if(isset($product->sku))
                            <p class="text-gray-500">SKU: <span class="font-medium text-gray-700">{{ $product->sku }}</span></p>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <span class="text-4xl font-semibold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @if(isset($product->compare_price) && $product->compare_price > $product->price)
                                <span class="text-xl text-gray-500 line-through">${{ number_format($product->compare_price, 2) }}</span>
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm font-medium">
                                    Save ${{ number_format($product->compare_price - $product->price, 2) }}
                                </span>
                            @endif
                        </div>

                        {{-- Stock Status --}}
                        <div class="flex items-center space-x-2">
                            @if(isset($product->stock_quantity))
                                @if($product->stock_quantity > 0)
                                    @if($product->stock_quantity <= 5)
                                        <div class="flex items-center text-orange-600">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <span class="font-medium">Only {{ $product->stock_quantity }} left in stock!</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-green-600">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            <span class="font-medium">In Stock ({{ $product->stock_quantity }} available)</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="flex items-center text-red-600">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        <span class="font-medium">Out of Stock</span>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center text-green-600">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="font-medium">In Stock</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Add to Cart Section --}}
                    @if(!isset($product->stock_quantity) || $product->stock_quantity > 0)
                        <div class="border-t border-gray-200 pt-6 space-y-6">
                            {{-- Quantity Selector --}}
                            <div class="flex items-center space-x-4">
                                <label class="text-sm font-medium text-gray-700">Quantity:</label>
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button wire:click="decrementQuantity" 
                                            class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 {{ $quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $quantity <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus text-sm"></i>
                                    </button>
                                    <span class="px-4 py-2 font-medium text-gray-900 min-w-[3rem] text-center">{{ $quantity }}</span>
                                    <button wire:click="incrementQuantity" 
                                            class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 {{ $quantity >= $maxQuantity ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $quantity >= $maxQuantity ? 'disabled' : '' }}>
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                </div>
                                <span class="text-sm text-gray-500">Max: {{ $maxQuantity }}</span>
                            </div>

                            {{-- Add to Cart Button --}}
                            @auth
                                <button wire:click="addToCart" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-xl font-medium text-lg transition-colors duration-200 flex items-center justify-center space-x-3">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>Add to Cart - ${{ number_format($product->price * $quantity, 2) }}</span>
                                    <div wire:loading.delay wire:target="addToCart" class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent ml-2"></div>
                                </button>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block w-full bg-gray-700 hover:bg-gray-800 text-white py-4 px-6 rounded-xl font-medium text-lg transition-colors duration-200 text-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Login to Purchase
                                </a>
                            @endauth
                        </div>
                    @endif

                    {{-- Product Description --}}
                    @if(isset($product->description) && $product->description)
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <div class="prose prose-sm text-gray-600 max-w-none">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Product Details --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            @if(isset($product->sku))
                                <div>
                                    <span class="text-gray-500">SKU:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ $product->sku }}</span>
                                </div>
                            @endif
                            @if($product->category)
                                <div>
                                    <span class="text-gray-500">Category:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ ucfirst($product->category) }}</span>
                                </div>
                            @endif
                            @if($product->brand)
                                <div>
                                    <span class="text-gray-500">Brand:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ ucfirst($product->brand) }}</span>
                                </div>
                            @endif
                            @if(isset($product->stock_quantity))
                                <div>
                                    <span class="text-gray-500">Availability:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ $product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Out of stock' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if($this->relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-semibold text-gray-900 mb-8">You might also like</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($this->relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="aspect-square bg-gray-100">
                                @if(isset($relatedProduct->images) && is_array($relatedProduct->images) && count($relatedProduct->images) > 0)
                                    <img src="{{ Storage::url($relatedProduct->images[0]) }}" 
                                         alt="{{ $relatedProduct->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-gray-300"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                                <p class="text-lg font-semibold text-gray-900">${{ number_format($relatedProduct->price, 2) }}</p>
                                <a href="{{ route('products.show', $relatedProduct->slug ?? $relatedProduct->_id) }}" 
                                   class="mt-3 block w-full bg-gray-100 hover:bg-gray-200 text-gray-900 py-2 px-4 rounded-lg text-center text-sm font-medium transition-colors duration-200">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Custom Animations --}}
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</div>
