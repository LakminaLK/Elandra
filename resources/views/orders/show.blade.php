@extends('layouts.frontend')

@section('title', 'Order Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Page Header with Gradient -->
    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white drop-shadow-lg">Order Details</h1>
                    <p class="mt-3 text-blue-100 text-lg font-medium">Order #{{ $order->order_number }}</p>
                </div>
                
                <!-- Order Status -->
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-500/20 border-yellow-300/30',
                        'processing' => 'bg-blue-500/20 border-blue-300/30',
                        'shipped' => 'bg-purple-500/20 border-purple-300/30',
                        'delivered' => 'bg-green-500/20 border-green-300/30',
                        'cancelled' => 'bg-red-500/20 border-red-300/30'
                    ];
                    $statusClass = $statusClasses[$order->status] ?? 'bg-white/20 border-white/30';
                @endphp
                <span class="inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold backdrop-blur text-white border {{ $statusClass }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Order Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">Order Items</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @foreach($order->orderItems as $item)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center space-x-6">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @php
                                        // Try to get the MongoDB product for image
                                        $mongoProduct = null;
                                        try {
                                            if ($item->product_id) {
                                                $mongoProduct = \App\Models\MongoProduct::where('_id', $item->product_id)->first();
                                            }
                                        } catch (\Exception $e) {
                                            // Ignore if product not found
                                        }
                                    @endphp
                                    
                                    @if($mongoProduct && isset($mongoProduct->images[0]))
                                        <img src="{{ asset('storage/' . $mongoProduct->images[0]) }}" alt="{{ $item->product_name }}" class="w-24 h-24 object-cover rounded-xl shadow-md">
                                    @else
                                        <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center shadow-md">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->product_name }}</h3>
                                    @if($mongoProduct && $mongoProduct->category)
                                        <p class="text-sm text-blue-600 font-medium mb-1">{{ $mongoProduct->category }}</p>
                                    @endif
                                    @if($item->product_sku)
                                        <p class="text-sm text-gray-500 mb-2">SKU: {{ $item->product_sku }}</p>
                                    @endif
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                                        Quantity: {{ $item->quantity }}
                                    </div>
                                </div>
                                
                                <!-- Price -->
                                <div class="text-right">
                                    <div class="text-lg font-semibold text-gray-900 mb-1">
                                        ${{ number_format($item->unit_price, 2) }} each
                                    </div>
                                    <div class="text-xl font-bold text-blue-600">
                                        ${{ number_format($item->total_price, 2) }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Total</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Order Information -->
                <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">Order Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">Order Details</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600">Order Number:</span>
                                        <span class="font-semibold text-gray-900">{{ $order->order_number }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600">Order Date:</span>
                                        <span class="font-semibold text-gray-900">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600">Payment Method:</span>
                                        <span class="font-semibold text-gray-900">{{ $order->payment_method }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">Customer</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600">Name:</span>
                                        <span class="font-semibold text-gray-900">{{ $order->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="font-semibold text-gray-900">{{ $order->user->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Address -->
                @if($order->shipping_address)
                <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">Shipping Address</h2>
                    </div>
                    
                    <div class="p-6">
                        @php
                            $shipping = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address;
                        @endphp
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if($shipping)
                                <div class="space-y-2">
                                    <div class="font-semibold text-gray-900 text-lg">{{ $shipping['name'] ?? '' }}</div>
                                    <div class="text-gray-700">{{ $shipping['email'] ?? '' }}</div>
                                    <div class="text-gray-700">{{ $shipping['phone'] ?? '' }}</div>
                                    <div class="text-gray-700 mt-3">{{ $shipping['address'] ?? '' }}</div>
                                    <div class="text-gray-700">{{ $shipping['city'] ?? '' }}, {{ $shipping['state'] ?? '' }} {{ $shipping['zip'] ?? '' }}</div>
                                </div>
                            @else
                                <div class="text-gray-500 italic">No address information available</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 sticky top-6 overflow-hidden">
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                        <h2 class="text-xl font-semibold">Order Summary</h2>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Subtotal -->
                        @php
                            $subtotal = $order->total_amount - $order->tax_amount - $order->shipping_amount;
                        @endphp
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Subtotal</span>
                            <span class="font-semibold text-gray-900">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <!-- Shipping -->
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Shipping</span>
                            <span class="font-semibold">
                                @if($order->shipping_amount == 0)
                                    <span class="text-green-600 font-bold">FREE</span>
                                @else
                                    <span class="text-gray-900">${{ number_format($order->shipping_amount, 2) }}</span>
                                @endif
                            </span>
                        </div>
                        
                        <!-- Tax -->
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Tax</span>
                            <span class="font-semibold text-gray-900">${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        
                        <!-- Total -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-blue-600">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="px-6 py-5 bg-gray-50 border-t border-gray-100">
                        <div class="space-y-3">
                            <a href="{{ route('orders.index') }}" class="block w-full bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-semibold hover:bg-gray-700 transition-all duration-200 text-center transform hover:scale-105 shadow-md">
                                ← View All Orders
                            </a>
                            @if($order->status === 'delivered')
                            <button class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-md">
                                🔄 Reorder Items
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection