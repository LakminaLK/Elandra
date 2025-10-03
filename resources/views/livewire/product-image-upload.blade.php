<div class="bg-white shadow-sm rounded-lg border border-gray-200">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Images</h3>

        <!-- Success/Error Messages -->
        @if($showSuccess)
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ $successMessage }}</p>
                </div>
                <button @click="show = false" wire:click="hideMessages" class="text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        @if($showError)
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 8000)">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ $errorMessage }}</p>
                </div>
                <button @click="show = false" wire:click="hideMessages" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Upload Section -->
        <div class="mb-6">
            <div class="border-2 border-gray-300 border-dashed rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                
                <div>
                    <label for="image-upload" class="cursor-pointer">
                        <span class="mt-2 block text-sm font-medium text-gray-900">
                            Click to upload images or drag and drop
                        </span>
                        <span class="mt-1 block text-xs text-gray-500">
                            PNG, JPG, GIF, WEBP up to 5MB each (Max 5 images)
                        </span>
                        <input id="image-upload" 
                               type="file" 
                               multiple 
                               accept="image/*" 
                               wire:model="images" 
                               class="sr-only">
                    </label>
                </div>
            </div>

            @if(!empty($images))
            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Selected Images:</h4>
                <div class="space-y-2">
                    @foreach($images as $index => $image)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">{{ $image->getClientOriginalName() }}</span>
                        <span class="text-xs text-gray-500">{{ number_format($image->getSize() / 1024, 1) }} KB</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($images))
            <div class="mt-4 flex justify-end">
                <button wire:click="uploadImages" 
                        wire:loading.attr="disabled"
                        wire:target="uploadImages"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="uploadImages">Upload Images</span>
                    <span wire:loading wire:target="uploadImages" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading...
                    </span>
                </button>
            </div>
            @endif
        </div>

        <!-- Current Images Display -->
        @if(!empty($productImages))
        <div class="border-t pt-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Current Images ({{ count($productImages) }})</h4>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($productImages as $index => $image)
                <div class="relative group">
                    <div class="aspect-w-1 aspect-h-1 w-full rounded-lg bg-gray-200 overflow-hidden">
                        <img src="{{ Storage::url($image['path']) }}" 
                             alt="{{ $image['original_name'] ?? 'Product image' }}" 
                             class="w-full h-full object-cover group-hover:opacity-75 transition-opacity">
                    </div>
                    
                    <!-- Image overlay with actions -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 rounded-lg flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-2">
                            @if(!isset($image['is_main']) || !$image['is_main'])
                            <button wire:click="setMainImage('{{ $image['path'] }}')" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors"
                                    title="Set as main image">
                                Main
                            </button>
                            @else
                            <span class="bg-green-600 text-white px-3 py-1 rounded-md text-xs font-medium">
                                Main
                            </span>
                            @endif
                            
                            <button wire:click="deleteImage('{{ $image['path'] }}')" 
                                    onclick="return confirm('Are you sure you want to delete this image?')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors"
                                    title="Delete image">
                                Delete
                            </button>
                        </div>
                    </div>
                    
                    <!-- Image info -->
                    <div class="mt-2">
                        <p class="text-xs text-gray-500 truncate" title="{{ $image['original_name'] ?? 'Unknown' }}">
                            {{ $image['original_name'] ?? 'Unknown' }}
                        </p>
                        @if(isset($image['size']))
                        <p class="text-xs text-gray-400">{{ number_format($image['size'] / 1024, 1) }} KB</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="border-t pt-6">
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h4 class="mt-2 text-sm font-medium text-gray-900">No images uploaded</h4>
                <p class="mt-1 text-sm text-gray-500">Upload some images to showcase your product.</p>
            </div>
        </div>
        @endif
        
        @error('images.*')
        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('scripts')
<script>
    // File drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.querySelector('[data-drop-zone]');
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
            }

            function unhighlight(e) {
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                // Trigger file input change
                const fileInput = document.getElementById('image-upload');
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    });
</script>
@endpush
