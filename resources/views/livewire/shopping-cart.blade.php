<div>
    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" 
                               wire:click="toggleSelectAll"
                               wire:key="select-all-checkbox"
                               {{ $selectAll ? 'checked' : '' }}
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <h2 class="text-lg font-semibold text-gray-900">Cart Items ({{ $cartItems->sum('quantity') }})</h2>
                    </div>
                    @if(count($selectedItems ?? []) > 0)
                        <div class="text-sm text-indigo-600">
                            {{ count($selectedItems ?? []) }} item(s) selected
                        </div>
                    @endif
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                    <div class="p-6 flex items-center space-x-4 transition-all duration-300 {{ in_array((string)$item->id, $selectedItems ?? []) ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50' }}" 
                         wire:key="cart-item-{{ $item->id }}">
                        <!-- Checkbox -->
                        <div class="flex-shrink-0">
                            <input type="checkbox" 
                                   wire:model.live="selectedItems"
                                   wire:key="checkbox-{{ $item->id }}-{{ $selectionVersion }}"
                                   value="{{ $item->id }}"
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        </div>
                        
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            @if($item->product && $item->product->slug)
                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                   class="block hover:opacity-75 transition-opacity duration-200"
                                   target="_blank">
                                    @if($item->product_image)
                                        <img src="{{ Storage::url($item->product_image) }}" alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    @endif
                                </a>
                            @else
                                @if($item->product_image)
                                    <img src="{{ Storage::url($item->product_image) }}" alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold">
                                @if($item->product && $item->product->slug)
                                    <a href="{{ route('products.show', $item->product->slug) }}" 
                                       class="text-gray-900 hover:text-blue-600 transition-colors duration-200 cursor-pointer"
                                       target="_blank">
                                        {{ $item->product_name }}
                                    </a>
                                @else
                                    <span class="text-gray-900">{{ $item->product_name }}</span>
                                @endif
                            </h3>
                            
                            @if($item->product_sku)
                                <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                            @endif
                            
                            <!-- Price -->
                            <div class="mt-2 flex items-center space-x-2">
                                @if($item->is_sale && $item->original_price > $item->price)
                                    <span class="text-lg font-bold text-red-600">{{ $item->formatted_price }}</span>
                                    <span class="text-sm text-gray-500 line-through">${{ number_format($item->original_price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">{{ $item->formatted_price }}</span>
                                @endif
                            </div>
                            
                            <!-- Stock Status Note -->
                            @php
                                $product = $item->product; // This will use the getProductAttribute() method
                                $maxQuantity = 3;
                                if ($product && isset($product->stock_quantity)) {
                                    $maxQuantity = min(3, $product->stock_quantity);
                                }
                            @endphp
                            @if($product && isset($product->stock_quantity))
                                @if($product->stock_quantity < $item->quantity)
                                    <div class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Only {{ $product->stock_quantity }} available
                                    </div>
                                @elseif($item->quantity >= $maxQuantity)
                                    <div class="mt-2 text-sm text-orange-600 flex items-center">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Maximum {{ $maxQuantity }} items per product
                                    </div>
                                @elseif($product->stock_quantity <= 5)
                                    <div class="mt-2 text-sm text-orange-600 flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        Only {{ $product->stock_quantity }} left in stock
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        <!-- Quantity Controls -->
                        <div class="flex items-center space-x-3" wire:key="quantity-controls-{{ $item->id }}">
                            @php
                                $product = $item->product; // Get product info
                                $maxQuantity = 3;
                                if ($product && isset($product->stock_quantity)) {
                                    $maxQuantity = min(3, $product->stock_quantity);
                                }
                            @endphp
                            
                            <button 
                                wire:click="decrementQuantity({{ $item->id }})"
                                wire:loading.attr="disabled"
                                wire:key="decrease-{{ $item->id }}"
                                @if($item->quantity <= 1) disabled @endif
                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                            >
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            
                            <div class="flex items-center justify-center w-12" wire:key="quantity-display-{{ $item->id }}">
                                <span class="font-medium text-gray-900">{{ $item->quantity }}</span>
                            </div>
                            
                            <button 
                                wire:click="incrementQuantity({{ $item->id }})"
                                wire:loading.attr="disabled"
                                wire:key="increase-{{ $item->id }}"
                                @if($item->quantity >= $maxQuantity) disabled @endif
                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                                @if($item->quantity >= $maxQuantity) title="Maximum {{ $maxQuantity }} items allowed" @endif
                            >
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Line Total -->
                        <div class="flex flex-col items-end">
                            <span class="text-lg font-bold text-gray-900">
                                {{ $item->formatted_total_price }}
                            </span>
                            
                            <!-- Remove Button -->
                            <button 
                                wire:click="removeItem({{ $item->id }})"
                                wire:loading.attr="disabled"
                                wire:target="removeItem"
                                wire:confirm="Are you sure you want to remove this item from your cart?"
                                class="mt-2 text-sm text-red-600 hover:text-red-800 transition-colors duration-200 disabled:opacity-50 flex items-center space-x-1"
                            >
                                <span wire:loading.remove wire:target="removeItem">Remove</span>
                                <div wire:loading wire:target="removeItem" class="flex items-center space-x-1">
                                    <div class="animate-spin rounded-full h-3 w-3 border border-red-600 border-t-transparent"></div>
                                    <span>Removing...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Continue Shopping -->
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Continue Shopping
                </a>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                </div>
                
                <div class="p-6 space-y-4" wire:key="order-summary-content">
                    @if(count($selectedItems ?? []) > 0)
                        <!-- Selected Items Summary -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4" wire:key="selected-summary-{{ count($selectedItems ?? []) }}">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-blue-900">Selected Items</span>
                                <span class="text-blue-700">{{ $this->selectedCount }} item(s)</span>
                            </div>
                            
                            <!-- Selected Subtotal -->
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-blue-600">Subtotal:</span>
                                <span class="font-medium text-blue-700">${{ number_format($this->selectedTotal, 2) }}</span>
                            </div>
                            
                            <!-- Selected Shipping -->
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-blue-600">Shipping:</span>
                                <span class="font-medium text-blue-700">
                                    @if($this->selectedShipping == 0)
                                        <span class="text-green-600">FREE</span>
                                    @else
                                        ${{ number_format($this->selectedShipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            
                            <!-- Selected Tax -->
                            <div class="flex justify-between text-sm mb-3">
                                <span class="text-blue-600">Tax (10%):</span>
                                <span class="font-medium text-blue-700">${{ number_format($this->selectedTax, 2) }}</span>
                            </div>
                            
                            <!-- Selected Total -->
                            <div class="border-t border-blue-300 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-blue-900 text-lg">Total:</span>
                                    <span class="font-bold text-blue-900 text-lg">${{ number_format($this->selectedFinalTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty Selection State -->
                        <div class="text-center py-8 text-gray-500" wire:transition wire:key="empty-summary">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div class="mb-2">
                                <span class="text-lg font-medium text-gray-400">No items selected</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Checkout Button -->
                    @if(count($selectedItems ?? []) > 0)
                        <button 
                            wire:click="proceedToCheckout"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            wire:key="checkout-button-{{ $selectionVersion }}"
                            id="checkout-button-visible"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl"
                        >
                            <span wire:loading.remove>
                                Proceed to Checkout
                            </span>
                            <span wire:loading class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    @else
                        <!-- Hidden checkout button for JavaScript fallback -->
                        <button 
                            wire:click="proceedToCheckout"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            id="checkout-button-hidden"
                            style="display: none;"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl"
                        >
                            <span wire:loading.remove>
                                Proceed to Checkout
                            </span>
                            <span wire:loading class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                        

                    @endif

                    
                    <!-- Security Notice -->
                    <div class="flex items-center justify-center text-sm text-gray-500 mt-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Secure Checkout
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @else
    <!-- Empty Cart -->
    <div class="text-center py-12">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.8 9.2M7 13l2.8-2.8m0 0L17 3m0 0l-2.8 2.8M17 3l2.8 2.8"></path>
        </svg>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
        <p class="text-gray-600 mb-8">Looks like you haven't added any items to your cart yet.</p>
        
        <a href="{{ route('products.index') }}" class="inline-flex items-center bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7H6L5 9z"></path>
            </svg>
            Start Shopping
        </a>
    </div>
    @endif
    
    <!-- Loading Overlay -->
    <div wire:loading wire:target="updateQuantity,removeItem,proceedToCheckout" class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-900 font-medium">Updating cart...</span>
        </div>
    </div>
</div>


