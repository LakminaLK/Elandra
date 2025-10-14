@extends('layouts.frontend')

@section('title', 'My Orders')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    {{-- Loading Overlay --}}
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center backdrop-blur-sm hidden">
        <div class="bg-white rounded-2xl p-8 flex items-center space-x-4 shadow-2xl animate-scale-in">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
            <div class="text-gray-700">
                <p class="font-semibold text-lg">Loading orders...</p>
                <p class="text-sm text-gray-500">Please wait while we fetch your order history</p>
            </div>
        </div>
    </div>

    {{-- Professional Page Header --}}
    <div class="relative mb-12 py-16 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white to-transparent opacity-5"></div>
        
        {{-- Content --}}
        <div class="relative max-w-4xl mx-auto text-center px-4">
            <div class="inline-flex items-center bg-white bg-opacity-20 rounded-full px-4 py-2 mb-6 animate-fade-in">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="text-sm font-medium">Order Management</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-slide-down">
                My <span class="text-yellow-300">Orders</span>
            </h1>
            
            <p class="text-xl text-blue-100 leading-relaxed max-w-2xl mx-auto mb-8 animate-slide-up delay-200">
                Track and manage all your orders in one place. 
                Stay updated with real-time order status and delivery information.
            </p>
            
            <div class="flex flex-wrap justify-center items-center gap-8 text-blue-200 animate-fade-in delay-500">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm">Real-time Tracking</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="text-sm">Easy Reordering</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm">24/7 Support</span>
                </div>
            </div>
        </div>
        
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-x-16 -translate-y-16"></div>
        <div class="absolute bottom-0 right-0 w-48 h-48 bg-white bg-opacity-5 rounded-full translate-x-24 translate-y-24"></div>
    </div>

    <!-- Orders Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @if($orders->count() > 0)
        
        {{-- Orders List --}}
        <div id="orders-container" class="space-y-6">
            @foreach($orders as $index => $order)
            <div class="order-card bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200" 
                 style="animation-delay: {{ $index * 100 }}ms"
                 data-order-number="{{ strtolower($order->order_number) }}"
                 data-status="{{ $order->status }}"
                 data-date="{{ $order->created_at->format('Y-m-d') }}"
                 data-amount="{{ $order->total_amount }}">
                
                <!-- Simplified Order Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }} • {{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xl font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Simplified Order Items -->
                <div class="px-6 py-4">
                    @if($order->orderItems->count() > 0)
                        <!-- Individual Items with Clean Design -->
                        <div class="space-y-3">
                            @foreach($order->orderItems->take(3) as $item)
                            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                @php
                                    // Try to get the MongoDB product for image
                                    $mongoProduct = null;
                                    try {
                                        if ($item->product_id && $item->product_id !== 'test-product-id') {
                                            $mongoProduct = \App\Models\MongoProduct::where('_id', $item->product_id)->first();
                                        }
                                    } catch (\Exception $e) {
                                        // Ignore if product not found
                                    }
                                @endphp
                                
                                @if($mongoProduct && isset($mongoProduct->images[0]))
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('storage/' . $mongoProduct->images[0]) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="w-16 h-16 object-cover rounded-lg">
                                    </div>
                                @else
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 truncate">{{ $item->product_name }}</h4>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                                        <span>Qty: {{ $item->quantity }}</span>
                                        <span>Unit: ${{ number_format($item->unit_price, 2) }}</span>
                                        @if($item->product_sku)
                                            <span>SKU: {{ $item->product_sku }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-right flex-shrink-0">
                                    <div class="text-lg font-medium text-gray-900">
                                        ${{ number_format($item->total_price, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($order->orderItems->count() > 3)
                            <div class="text-center py-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-600">
                                    And {{ $order->orderItems->count() - 3 }} more items... 
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 font-medium ml-1">
                                        View All
                                    </a>
                                </span>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                            </svg>
                            <p class="text-gray-600">No items found for this order</p>
                            <p class="text-sm text-gray-500 mt-1">This might be a legacy order</p>
                        </div>
                    @endif
                </div>
                
                <!-- Simplified Order Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span>Items: {{ $order->orderItems->sum('quantity') }}</span>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('orders.show', $order) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                View Details
                            </a>
                            @if($order->status === 'delivered')
                            <button onclick="reorderItems('{{ $order->id }}')"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                Reorder
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Simple Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
        
        @else
        <!-- Enhanced No Orders State -->
        <div class="text-center py-20 animate-fade-in">
            <div class="relative">
                <!-- Animated Background Elements -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-64 h-64 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full opacity-50 animate-pulse"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-40 h-40 bg-gradient-to-br from-emerald-200 to-teal-200 rounded-full opacity-30 animate-ping" style="animation-delay: 1s;"></div>
                </div>
                
                <!-- Main Content -->
                <div class="relative z-10">
                    <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center shadow-2xl animate-bounce">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">No orders yet</h2>
                    <p class="text-xl text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                        Your order history is empty. Start exploring our amazing products and create your first order!
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-8 py-4 rounded-xl font-semibold hover:from-emerald-700 hover:to-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 shadow-lg transform hover:scale-105 group">
                            <svg class="w-6 h-6 mr-3 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7H6L5 9z"></path>
                            </svg>
                            Start Shopping
                        </a>
                        
                        <a href="{{ route('categories.index') }}" 
                           class="inline-flex items-center bg-white text-emerald-600 px-8 py-4 rounded-xl font-semibold border-2 border-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-4 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 shadow-lg transform hover:scale-105 group">
                            <svg class="w-6 h-6 mr-3 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Browse Categories
                        </a>
                    </div>
                    
                    <!-- Benefits Section -->
                    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                        <div class="text-center p-6 bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-200">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Quality Guaranteed</h3>
                            <p class="text-gray-600">Premium products with 100% authenticity guarantee</p>
                        </div>
                        
                        <div class="text-center p-6 bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-200">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Fast Shipping</h3>
                            <p class="text-gray-600">Quick delivery to your doorstep worldwide</p>
                        </div>
                        
                        <div class="text-center p-6 bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-200">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">24/7 Support</h3>
                            <p class="text-gray-600">Round-the-clock customer service support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
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
    animation: fade-in 0.6s ease-out forwards;
}

.animate-slide-down {
    animation: slide-down 0.8s ease-out forwards;
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out forwards;
}

.animate-scale-in {
    animation: scale-in 0.4s ease-out forwards;
}

.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.3s; }
.delay-500 { animation-delay: 0.5s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show loading overlay function
    function showLoading() {
        document.getElementById('loading-overlay').classList.remove('hidden');
        document.getElementById('loading-overlay').classList.add('flex');
    }
    
    function hideLoading() {
        document.getElementById('loading-overlay').classList.add('hidden');
        document.getElementById('loading-overlay').classList.remove('flex');
    }
    
    // Reorder function
    window.reorderItems = function(orderId) {
        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            
            // Show success message
            const successMessage = document.createElement('div');
            successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50';
            successMessage.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Items added to cart successfully!
                </div>
            `;
            document.body.appendChild(successMessage);
            
            setTimeout(() => {
                successMessage.remove();
            }, 3000);
        }, 1000);
    };
    
    // Simple scroll animation for order cards
    const orderCards = document.querySelectorAll('.order-card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe all order cards
    orderCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        observer.observe(card);
    });
});
</script>
@endsection