@extends('admin.layouts.app')

@section('content')
<!-- Page Header -->
@include('admin.components.page-header', [
    'title' => 'Edit Product',
    'subtitle' => 'Update product information'
])

<div class="px-4 lg:px-6 py-6">
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.products.update', $product->_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('sku') border-red-300 @enderror">
                        @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <input type="text" id="category" name="category" value="{{ old('category', $product->category) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('category') border-red-300 @enderror">
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand', $product->brand) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('brand') border-red-300 @enderror">
                        @error('brand')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('description') border-red-300 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('price') border-red-300 @enderror">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sale Price -->
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">Sale Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('sale_price') border-red-300 @enderror">
                        </div>
                        @error('sale_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Leave empty if not on sale</p>
                    </div>

                    <!-- Cost -->
                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="cost" name="cost" value="{{ old('cost', $product->cost) }}" step="0.01" min="0"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('cost') border-red-300 @enderror">
                        </div>
                        @error('cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Inventory</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('stock_quantity') border-red-300 @enderror">
                        @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Low Stock Threshold -->
                    <div>
                        <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">Low Stock Alert</label>
                        <input type="number" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('low_stock_threshold') border-red-300 @enderror">
                        @error('low_stock_threshold')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Alert when stock falls below this number</p>
                    </div>

                    <!-- Track Quantity -->
                    <div class="flex items-center mt-6">
                        <input type="checkbox" id="track_quantity" name="track_quantity" value="1" 
                               {{ old('track_quantity', $product->track_quantity) ? 'checked' : '' }}
                               class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="track_quantity" class="ml-2 block text-sm text-gray-700">Track quantity</label>
                    </div>
                </div>
            </div>

            <!-- Current Images -->
            @if($product->main_image || $product->images)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Images</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @if($product->main_image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="Main Image" 
                                 class="w-full h-32 object-cover rounded-lg border-2 border-emerald-200">
                            <div class="absolute top-2 left-2">
                                <span class="bg-emerald-600 text-white text-xs px-2 py-1 rounded">Main</span>
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" onclick="removeImage('main_image')" 
                                        class="bg-red-600 text-white text-xs px-2 py-1 rounded hover:bg-red-700">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    @if($product->images)
                        @foreach($product->images as $index => $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image) }}" alt="Product Image" 
                                     class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" onclick="removeImage('images', {{ $index }})" 
                                            class="bg-red-600 text-white text-xs px-2 py-1 rounded hover:bg-red-700">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif

            <!-- New Images -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Images</h3>
                
                <div class="space-y-4">
                    <!-- New Main Image -->
                    <div>
                        <label for="main_image" class="block text-sm font-medium text-gray-700 mb-1">New Main Image</label>
                        <input type="file" id="main_image" name="main_image" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <p class="mt-1 text-xs text-gray-500">Leave empty to keep current main image</p>
                        @error('main_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Images -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Add More Images</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <p class="mt-1 text-xs text-gray-500">Select new images to add to the product</p>
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Checkboxes -->
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active (visible to customers)</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">Featured product</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="track_quantity" name="track_quantity" value="1" 
                                   {{ old('track_quantity', $product->track_quantity) ? 'checked' : '' }}
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="track_quantity" class="ml-2 block text-sm text-gray-700">Track inventory quantity</label>
                        </div>
                    </div>

                    <!-- Weight & Dimensions -->
                    <div class="space-y-3">
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                            <input type="number" id="weight" name="weight" value="{{ old('weight', $product->weight) }}" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label for="length" class="block text-sm font-medium text-gray-700 mb-1">L (cm)</label>
                                <input type="number" id="length" name="length" value="{{ old('length', $product->length) }}" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700 mb-1">W (cm)</label>
                                <input type="number" id="width" name="width" value="{{ old('width', $product->width) }}" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700 mb-1">H (cm)</label>
                                <input type="number" id="height" name="height" value="{{ old('height', $product->height) }}" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Info -->
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-900">Product Document Info</h4>
                        <div class="mt-2 text-sm text-blue-700">
                            <p><strong>Document ID:</strong> {{ $product->_id }}</p>
                            <p><strong>Created:</strong> {{ $product->created_at->format('M d, Y H:i:s') }}</p>
                            <p><strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6">
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Products
                </a>
                
                <div class="flex gap-3">
                    <a href="{{ route('admin.products.show', $product->_id) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i>View Product
                    </a>
                    <button type="submit" 
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Hidden inputs for image removal -->
<form id="removeImageForm" method="POST" action="{{ route('admin.products.remove-image', $product->_id) }}" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" id="imageType" name="type">
    <input type="hidden" id="imageIndex" name="index">
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sale price validation
    const priceInput = document.getElementById('price');
    const salePriceInput = document.getElementById('sale_price');
    
    function validateSalePrice() {
        const price = parseFloat(priceInput.value) || 0;
        const salePrice = parseFloat(salePriceInput.value) || 0;
        
        if (salePrice > 0 && salePrice >= price) {
            salePriceInput.setCustomValidity('Sale price must be less than regular price');
        } else {
            salePriceInput.setCustomValidity('');
        }
    }
    
    priceInput.addEventListener('input', validateSalePrice);
    salePriceInput.addEventListener('input', validateSalePrice);
});

function removeImage(type, index = null) {
    if (confirm('Are you sure you want to remove this image?')) {
        document.getElementById('imageType').value = type;
        if (index !== null) {
            document.getElementById('imageIndex').value = index;
        }
        document.getElementById('removeImageForm').submit();
    }
}
</script>
@endsection