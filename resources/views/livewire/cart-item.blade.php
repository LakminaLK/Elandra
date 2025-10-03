<div class="flex flex-col space-y-3">
    <!-- Stock Info -->
    @if($cartItem->product)
        @php
            $stock = $cartItem->product->stock_quantity ?? 0;
            $maxAllowed = min(3, $stock);
        @endphp
        
        @if($stock > 0 && $stock <= 5)
            <div class="text-xs text-orange-600 font-medium">
                Only {{ $stock }} left in stock
            </div>
        @elseif($stock == 0)
            <div class="text-xs text-red-600 font-medium">
                Out of stock
            </div>
        @endif
    @endif

    <!-- Quantity Controls -->
    <div class="flex items-center border rounded-lg">
        <button wire:click="decrementQuantity" 
                @if($quantity <= 1) disabled @endif
                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-l-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-minus"></i>
        </button>
        <span class="w-16 px-3 py-2 text-center bg-white">
            {{ $quantity }}
        </span>
        <button wire:click="incrementQuantity" 
                @if($cartItem->product && $quantity >= min(3, $cartItem->product->stock_quantity ?? 0)) disabled @endif
                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-r-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <!-- Quantity Limit Info -->
    @if($cartItem->product)
        @php
            $stock = $cartItem->product->stock_quantity ?? 0;
            $maxAllowed = min(3, $stock);
        @endphp
        
        @if($quantity >= $maxAllowed)
            <div class="text-xs text-gray-600">
                @if($stock < 3)
                    Maximum available: {{ $stock }}
                @else
                    Maximum per order: 3
                @endif
            </div>
        @endif
    @endif
    
    <!-- Remove Button -->
    <button wire:click="removeItem" 
            wire:confirm="Are you sure you want to remove this item from your cart?"
            class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors duration-200 flex items-center space-x-1">
        <i class="fas fa-trash text-xs"></i>
        <span>Remove</span>
    </button>
</div>
