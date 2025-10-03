@extends('layouts.frontend')

@section('title', 'Order Details')

@section('content')
<div class="bg-white">
    <!-- Page Header -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                    <p class="mt-2 text-gray-600">Order #{{ $order->order_number }}</p>
                </div>
                
                <!-- Order Status -->
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
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

    <!-- Order Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @foreach($order->orderItems as $item)
                        <div class="p-6 flex items-center space-x-4">
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
                                    <img src="{{ asset('storage/' . $mongoProduct->images[0]) }}" alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->product_name }}</h3>
                                @if($mongoProduct && $mongoProduct->category)
                                    <p class="text-sm text-gray-500">{{ $mongoProduct->category }}</p>
                                @endif
                                @if($item->product_sku)
                                    <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                                @endif
                                <div class="mt-2 text-sm text-gray-600">
                                    Quantity: {{ $item->quantity }}
                                </div>
                            </div>
                            
                            <!-- Price -->
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-900">
                                    ${{ number_format($item->unit_price, 2) }} each
                                </div>
                                <div class="text-sm text-gray-600">
                                    Total: ${{ number_format($item->total_price, 2) }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Order Information -->
                <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Order Details</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div>Order Number: {{ $order->order_number }}</div>
                                    <div>Order Date: {{ $order->created_at->format('M d, Y \a\t g:i A') }}</div>
                                    <div>Payment Method: {{ $order->payment_method }}</div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Customer</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div>{{ $order->user->name }}</div>
                                    <div>{{ $order->user->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Address -->
                @if($order->shipping_address)
                <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Shipping Address</h2>
                    </div>
                    
                    <div class="p-6">
                        @php
                            $shipping = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address;
                        @endphp
                        <div class="text-sm text-gray-600">
                            @if($shipping)
                                <div class="font-medium">{{ $shipping['name'] ?? '' }}</div>
                                <div>{{ $shipping['email'] ?? '' }}</div>
                                <div>{{ $shipping['phone'] ?? '' }}</div>
                                <div>{{ $shipping['address'] ?? '' }}</div>
                                <div>{{ $shipping['city'] ?? '' }}, {{ $shipping['state'] ?? '' }} {{ $shipping['zip'] ?? '' }}</div>
                            @else
                                <div class="text-gray-500">No address information available</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Subtotal -->
                        @php
                            $subtotal = $order->total_amount - $order->tax_amount - $order->shipping_amount;
                        @endphp
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <!-- Shipping -->
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium">
                                @if($order->shipping_amount == 0)
                                    <span class="text-green-600">FREE</span>
                                @else
                                    ${{ number_format($order->shipping_amount, 2) }}
                                @endif
                            </span>
                        </div>
                        
                        <!-- Tax -->
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium">${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        
                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-lg font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="space-y-3">
                            <a href="{{ route('orders.index') }}" class="block w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors text-center">
                                View All Orders
                            </a>
                            @if($order->status === 'delivered')
                            <button class="block w-full bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors">
                                Reorder Items
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