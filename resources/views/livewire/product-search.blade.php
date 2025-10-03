<div class="relative">
    <div class="relative">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search" 
            placeholder="Search luxury handbags..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
        >
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        
        @if($search)
        <button 
            wire:click="clearSearch" 
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        @endif
    </div>

    <!-- Search Results Dropdown -->
    @if($search && $results->count() > 0)
    <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
        <div class="p-2">
            <div class="text-xs text-gray-500 px-3 py-2 border-b border-gray-100">
                {{ $products->count() }} result{{ $products->count() !== 1 ? 's' : '' }} found
            </div>
            
            @foreach($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex-shrink-0 mr-3">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg">
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-400 text-xs">No Image</span>
                        </div>
                    @endif
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h4>
                    @if($product->category)
                        <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                    @endif
                    
                    <div class="flex items-center mt-1">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="text-sm font-bold text-red-600">${{ $product->formatted_sale_price }}</span>
                            <span class="text-xs text-gray-500 line-through ml-2">${{ $product->formatted_price }}</span>
                        @else
                            <span class="text-sm font-bold text-gray-900">${{ $product->formatted_price }}</span>
                        @endif
                    </div>
                </div>
                
                @if($product->stock_quantity <= 0)
                    <div class="text-xs text-red-600 font-medium">Out of Stock</div>
                @elseif($product->stock_quantity <= 5)
                    <div class="text-xs text-orange-600 font-medium">{{ $product->stock_quantity }} left</div>
                @endif
            </a>
            @endforeach
            
            @if($products->count() >= 5)
            <div class="border-t border-gray-100 pt-2 mt-2">
                <a href="{{ route('products.index', ['search' => $search]) }}" class="block text-center py-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    View all results for "{{ $search }}"
                </a>
            </div>
            @endif
        </div>
    </div>
    @elseif($search && $results->count() === 0)
    <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
        <div class="p-4 text-center">
            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-sm text-gray-600">No products found for "{{ $search }}"</p>
            <button wire:click="clearSearch" class="text-xs text-indigo-600 hover:text-indigo-800 mt-1">
                Clear search
            </button>
        </div>
    </div>
    @endif

    <!-- Loading indicator -->
    <div wire:loading wire:target="search" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
        <div class="p-4 text-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-gray-600 mt-2">Searching...</p>
        </div>
    </div>
</div>
