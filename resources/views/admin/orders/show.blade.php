@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600 mt-1">Order details and management</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>Back to Orders
            </a>
            
            <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}')" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Update Status
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Current Status</label>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'shipped' => 'bg-purple-100 text-purple-800',
                                'delivered' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Payment Status</label>
                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                            <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                        @elseif($order->status === 'cancelled')
                            <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">Refunded</span>
                        @else
                            <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Order Date</label>
                        <p class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    
                    @if($order->shipped_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Shipped Date</label>
                        <p class="text-sm text-gray-900">{{ $order->shipped_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @endif
                    
                    @if($order->delivered_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Delivered Date</label>
                        <p class="text-sm text-gray-900">{{ $order->delivered_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        @php
                            // Try to get the MongoDB product for admin view
                            $mongoProduct = null;
                            try {
                                if ($item->product_id) {
                                    $mongoProduct = \App\Models\MongoProduct::where('_id', $item->product_id)->first();
                                }
                            } catch (\Exception $e) {
                                // Ignore if product not found
                            }
                        @endphp
                        
                        <!-- Product Image -->
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                            @if($mongoProduct && isset($mongoProduct->images[0]))
                                <img src="{{ asset('storage/' . $mongoProduct->images[0]) }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h4>
                            @if($item->product_sku)
                                <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                            @endif
                            @if($mongoProduct && $mongoProduct->category)
                                <p class="text-sm text-gray-600">Category: {{ $mongoProduct->category }}</p>
                            @endif
                            @if($mongoProduct && $mongoProduct->brand)
                                <p class="text-sm text-gray-600">Brand: {{ $mongoProduct->brand }}</p>
                            @endif
                        </div>
                        
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">Qty: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">${{ number_format($item->unit_price, 2) }} each</p>
                            <p class="text-sm font-semibold text-gray-900">${{ number_format($item->total_price, 2) }} total</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Notes</h3>
                <p class="text-gray-700">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                
                @if($order->user)
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Name</label>
                        <p class="text-sm text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-sm text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    
                    @if($order->user->phone)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Phone</label>
                        <p class="text-sm text-gray-900">{{ $order->user->phone }}</p>
                    </div>
                    @endif
                </div>
                @else
                <p class="text-sm text-gray-600">Guest customer</p>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Subtotal</span>
                        <span class="text-sm text-gray-900">${{ number_format($order->orderItems->sum('total_price'), 2) }}</span>
                    </div>
                    
                    @if($order->shipping_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Shipping</span>
                        <span class="text-sm text-gray-900">${{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($order->tax_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tax</span>
                        <span class="text-sm text-gray-900">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    <hr class="my-3">
                    
                    <div class="flex justify-between">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-base font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            @if($order->shipping_address)
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                
                <div class="text-sm text-gray-700">
                    @if(is_array($order->shipping_address))
                        <p>{{ $order->shipping_address['name'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['address_line_1'] ?? '' }}</p>
                        @if(!empty($order->shipping_address['address_line_2']))
                            <p>{{ $order->shipping_address['address_line_2'] }}</p>
                        @endif
                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                    @else
                        <p>{{ $order->shipping_address }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Billing Address -->
            @if($order->billing_address && $order->billing_address !== $order->shipping_address)
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h3>
                
                <div class="text-sm text-gray-700">
                    @if(is_array($order->billing_address))
                        <p>{{ $order->billing_address['name'] ?? '' }}</p>
                        <p>{{ $order->billing_address['address_line_1'] ?? '' }}</p>
                        @if(!empty($order->billing_address['address_line_2']))
                            <p>{{ $order->billing_address['address_line_2'] }}</p>
                        @endif
                        <p>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['postal_code'] ?? '' }}</p>
                        <p>{{ $order->billing_address['country'] ?? '' }}</p>
                    @else
                        <p>{{ $order->billing_address }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form method="POST" id="statusForm">
                @csrf
                @method('PATCH')
                
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Update Order Status</h3>
                </div>
                
                <div class="px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                    <select name="status" id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Add any notes about this status change..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(orderId, currentStatus) {
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusForm').action = `/admin/orders/${orderId}/update-status`;
    document.getElementById('statusSelect').value = currentStatus;
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});
</script>
@endsection