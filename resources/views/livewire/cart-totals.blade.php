<div class="bg-white rounded-lg shadow-sm border p-6 sticky top-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
    
    <div class="space-y-3 mb-6">
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Subtotal ({{ $itemCount }} items)</span>
            <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
        </div>
        
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Shipping</span>
            <span class="font-medium">
                @if($shipping > 0)
                    ${{ number_format($shipping, 2) }}
                @else
                    <span class="text-green-600">Free</span>
                @endif
            </span>
        </div>
        
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Tax</span>
            <span class="font-medium">${{ number_format($tax, 2) }}</span>
        </div>
        
        <div class="border-t border-gray-200 pt-3">
            <div class="flex justify-between text-lg font-semibold">
                <span class="text-gray-900">Total</span>
                <span class="text-gray-900">${{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>
    
    <div class="space-y-3">
        @auth
            <button 
                wire:click="proceedToCheckout"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 disabled:cursor-not-allowed">
                <i class="fas fa-credit-card" wire:loading.remove wire:target="proceedToCheckout"></i>
                <i class="fas fa-spinner fa-spin" wire:loading wire:target="proceedToCheckout"></i>
                <span wire:loading.remove wire:target="proceedToCheckout">Proceed to Checkout</span>
                <span wire:loading wire:target="proceedToCheckout">Processing...</span>
            </button>
        @else
            <a href="{{ route('login') }}" 
               class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium text-center transition-colors duration-200">
                Login to Checkout
            </a>
        @endauth
        
        <a href="{{ route('products.index') }}" 
           class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium text-center transition-colors duration-200">
            Continue Shopping
        </a>
    </div>
    
    @if($subtotal > 0 && $subtotal < 50 && $shipping > 0)
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">
                <i class="fas fa-truck mr-1"></i>
                Add ${{ number_format(50 - $subtotal, 2) }} more for free shipping!
            </p>
        </div>
    @endif
</div>