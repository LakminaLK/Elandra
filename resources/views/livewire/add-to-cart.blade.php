<div>
    @if($showQuantity)
    <!-- Quantity Selector with Add to Cart -->
    <div class="flex items-center space-x-4">
        <div class="flex items-center border border-gray-300 rounded-lg">
            <button 
                wire:click="decrementQuantity"
                @if($quantity <= 1) disabled @endif
                class="px-3 py-2 text-gray-600 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                </svg>
            </button>
            
            <span class="px-4 py-2 border-l border-r border-gray-300 font-medium">{{ $quantity }}</span>
            
            <button 
                wire:click="incrementQuantity"
                @if($quantity >= $product->stock_quantity) disabled @endif
                class="px-3 py-2 text-gray-600 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                </svg>
            </button>
        </div>
        
        <button 
            wire:click="addToCart"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50"
            @if($product->stock_quantity <= 0) disabled @endif
            class="flex-1 bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed"
        >
            <span wire:loading.remove wire:target="addToCart">
                @if($product->stock_quantity <= 0)
                    Out of Stock
                @else
                    Add to Cart
                @endif
            </span>
            <span wire:loading wire:target="addToCart" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Adding...
            </span>
        </button>
    </div>
    @else
    <!-- Simple Add to Cart Button -->
    <button 
        wire:click="addToCart"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-50"
        @if($product->stock_quantity <= 0) disabled @endif
        class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed"
    >
        <span wire:loading.remove wire:target="addToCart">
            @if($product->stock_quantity <= 0)
                Out of Stock
            @else
                Add to Cart
            @endif
        </span>
        <span wire:loading wire:target="addToCart" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Adding...
        </span>
    </button>
    @endif

    <!-- Success Message -->
    @if($showSuccess)
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => { show = false; $wire.hideSuccessMessage() }, 3000)" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Added to cart successfully!
            <button @click="show = false; $wire.hideSuccessMessage()" class="ml-4 text-white hover:text-gray-200">✕</button>
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if($errorMessage)
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => { show = false; $wire.hideErrorMessage() }, 5000)" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $errorMessage }}
            <button @click="show = false; $wire.hideErrorMessage()" class="ml-4 text-white hover:text-gray-200">✕</button>
        </div>
    </div>
    @endif
</div>
