@extends('admin.layouts.app')

@section('content')
<!-- Page Header -->
@include('admin.components.page-header', [
    'title' => 'Product Details',
    'subtitle' => 'View detailed information for ' . $product->name
])

<div class="px-4 lg:px-6 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Action Bar -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('admin.products.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Products
            </a>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.products.edit', $product->_id) }}" 
                   class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Product
                </a>
                <form method="POST" action="{{ route('admin.products.destroy', $product->_id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Overview -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span><strong>SKU:</strong> {{ $product->sku }}</span>
                                @if($product->category)
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full">{{ $product->category }}</span>
                                @endif
                                @if($product->brand)
                                    <span><strong>Brand:</strong> {{ $product->brand }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end gap-2">
                            <span class="px-3 py-1 text-sm rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($product->is_featured)
                                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($product->description)
                        <div class="prose max-w-none text-gray-700">
                            {{ $product->description }}
                        </div>
                    @endif
                </div>

                <!-- Product Images -->
                @if($product->main_image || $product->images)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Images</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @if($product->main_image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover rounded-lg border-2 border-emerald-200">
                                <div class="absolute top-2 left-2">
                                    <span class="bg-emerald-600 text-white text-xs px-2 py-1 rounded">Main Image</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($product->images)
                            @foreach($product->images as $image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                <!-- Product Document Raw Data -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Document Data</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg overflow-x-auto">
                        <pre class="text-sm text-gray-800 whitespace-pre-wrap"><code>{{ json_encode($product->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Pricing Information -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Regular Price:</span>
                            <span class="text-lg font-semibold">{{ $product->formatted_price }}</span>
                        </div>
                        
                        @if($product->is_on_sale)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Sale Price:</span>
                                <span class="text-lg font-semibold text-green-600">{{ $product->formatted_sale_price }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Discount:</span>
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">{{ $product->discount_percentage }}% OFF</span>
                            </div>
                        @endif
                        
                        @if($product->cost)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Cost:</span>
                                <span class="text-sm">${{ number_format($product->cost, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Profit Margin:</span>
                                <span class="text-sm">
                                    @php
                                        $effectivePrice = $product->is_on_sale ? $product->sale_price : $product->price;
                                        $margin = $product->cost > 0 ? (($effectivePrice - $product->cost) / $effectivePrice) * 100 : 0;
                                    @endphp
                                    {{ number_format($margin, 1) }}%
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Inventory Information -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Inventory</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Stock Quantity:</span>
                            <span class="font-semibold">{{ $product->stock_quantity }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Stock Status:</span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $product->stock_status == 'in-stock' ? 'bg-green-100 text-green-800' : 
                                   ($product->stock_status == 'low-stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->stock_status_label }}
                            </span>
                        </div>
                        
                        @if($product->low_stock_threshold)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Low Stock Alert:</span>
                                <span class="text-sm">{{ $product->low_stock_threshold }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Track Quantity:</span>
                            <span class="text-sm {{ $product->track_quantity ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $product->track_quantity ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Product Specifications -->
                @if($product->weight || $product->length || $product->width || $product->height)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                    
                    <div class="space-y-3">
                        @if($product->weight)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Weight:</span>
                                <span class="text-sm">{{ $product->weight }} kg</span>
                            </div>
                        @endif
                        
                        @if($product->length || $product->width || $product->height)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Dimensions:</span>
                                <span class="text-sm">
                                    {{ $product->length ?? '0' }} × {{ $product->width ?? '0' }} × {{ $product->height ?? '0' }} cm
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Database Information -->
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">
                        <i class="fas fa-database mr-2"></i>Database Info
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-start">
                            <span class="text-blue-700">Document ID:</span>
                            <code class="text-blue-900 bg-blue-100 px-2 py-1 rounded text-xs break-all">{{ $product->_id }}</code>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Collection:</span>
                            <span class="text-blue-900 font-medium">products</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Database:</span>
                            <span class="text-blue-900 font-medium">elandra_mongodb</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Created:</span>
                            <span class="text-blue-900">{{ $product->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">Updated:</span>
                            <span class="text-blue-900">{{ $product->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    
                    <div class="space-y-2">
                        <form method="POST" action="{{ route('admin.products.update', $product->_id) }}" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_active" value="{{ $product->is_active ? '0' : '1' }}">
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }} text-gray-400 mr-2"></i>
                                {{ $product->is_active ? 'Make Inactive' : 'Make Active' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.products.update', $product->_id) }}" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_featured" value="{{ $product->is_featured ? '0' : '1' }}">
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-{{ $product->is_featured ? 'star-half-alt' : 'star' }} text-gray-400 mr-2"></i>
                                {{ $product->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                            </button>
                        </form>
                        
                        <button onclick="window.print()" class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-print text-gray-400 mr-2"></i>
                            Print Details
                        </button>
                        
                        <button onclick="exportToJson()" class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-download text-gray-400 mr-2"></i>
                            Export JSON
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToJson() {
    const productData = @json($product->toArray());
    const dataStr = JSON.stringify(productData, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    
    const exportFileDefaultName = 'product-{{ $product->_id }}.json';
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}
</script>
@endsection