<a href="{{ route('cart') }}" class="relative inline-flex items-center px-4 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">
    <i class="fas fa-shopping-cart text-lg"></i>
    <span class="ml-2 hidden sm:inline">Cart</span>
    @if($cartCount > 0)
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold animate-pulse">
            {{ $cartCount > 99 ? '99+' : $cartCount }}
        </span>
    @endif
</a>
