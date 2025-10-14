<div>
    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 flex items-center space-x-4 shadow-2xl animate-scale-in">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
            <div class="text-gray-700">
                <p class="font-semibold text-lg">Loading products...</p>
                <p class="text-sm text-gray-500">Please wait while we fetch the latest products</p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Error Message -->
    <?php if(session()->has('error')): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Header with Create Button -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Product Management</h1>
            <p class="text-gray-600 mt-1">Manage your product catalog</p>
        </div>
        <button wire:click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="fas fa-plus text-lg"></i>
            <span>Add Product</span>
        </button>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search by name, SKU, or brand..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select wire:model.live="filterCategory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Categories</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!--[if BLOCK]><![endif]--><?php if($category): ?>
                            <option value="<?php echo e($category); ?>"><?php echo e(ucfirst($category)); ?></option>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select wire:model.live="sortField" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="created_at">Date Created</option>
                    <option value="name">Name</option>
                    <option value="price">Price</option>
                    <option value="stock_quantity">Stock</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                            Name
                            <!--[if BLOCK]><![endif]--><?php if($sortField === 'name'): ?>
                                <i class="fas fa-sort-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> ml-1"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('category')">
                            Category
                            <!--[if BLOCK]><![endif]--><?php if($sortField === 'category'): ?>
                                <i class="fas fa-sort-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> ml-1"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('price')">
                            Price
                            <!--[if BLOCK]><![endif]--><?php if($sortField === 'price'): ?>
                                <i class="fas fa-sort-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> ml-1"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('stock_quantity')">
                            Stock
                            <!--[if BLOCK]><![endif]--><?php if($sortField === 'stock_quantity'): ?>
                                <i class="fas fa-sort-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> ml-1"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($product->name); ?></div>
                                    <!--[if BLOCK]><![endif]--><?php if($product->brand): ?>
                                        <div class="text-sm text-gray-500"><?php echo e($product->brand); ?></div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!--[if BLOCK]><![endif]--><?php if(!empty($product->images) && isset($product->images[0])): ?>
                                    <img src="<?php echo e(Storage::url($product->images[0])); ?>" alt="<?php echo e($product->name); ?>" class="h-12 w-12 rounded-lg object-cover">
                                <?php else: ?>
                                    <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?php echo e($product->category ?? 'Uncategorized'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">$<?php echo e(number_format($product->price, 2)); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900"><?php echo e($product->stock_quantity); ?></span>
                                <!--[if BLOCK]><![endif]--><?php if($product->stock_quantity <= 5): ?>
                                    <span class="text-red-500 text-xs ml-1">(Low)</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button 
                                    wire:click="toggleProductStatus('<?php echo e($product->_id); ?>')"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($product->is_active ? 'Active' : 'Inactive'); ?>

                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="openEditModal('<?php echo e($product->_id); ?>')" 
                                            class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                            title="Edit Product">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </button>
                                    <button wire:click="toggleFeatured('<?php echo e($product->_id); ?>')" 
                                            class="inline-flex items-center px-3 py-2 <?php echo e($product->is_featured ? 'bg-orange-500 hover:bg-orange-600 text-white' : 'bg-gray-600 hover:bg-gray-700 text-white'); ?> rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                            title="<?php echo e($product->is_featured ? 'Remove from Featured' : 'Mark as Featured'); ?>">
                                        <i class="fas fa-star<?php echo e($product->is_featured ? '' : '-o'); ?> mr-1"></i>
                                        <?php echo e($product->is_featured ? 'Featured' : 'Feature'); ?>

                                    </button>
                                    <button wire:click="openDeleteModal('<?php echo e($product->_id); ?>')" 
                                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                            title="Delete Product">
                                        <i class="fas fa-trash mr-1"></i>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-box-open text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No products found</p>
                                    <p class="text-sm">Add your first product to get started</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($products->links()); ?>

        </div>
    </div>

    <!-- Create/Edit Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showCreateModal || $showEditModal): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="<?php echo e($showCreateModal ? 'createProduct' : 'updateProduct'); ?>">
                        <div class="bg-white px-6 py-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    <?php echo e($showCreateModal ? 'Create Product' : 'Edit Product'); ?>

                                </h3>
                                <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Name -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                    <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- Price -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                    <input type="number" step="0.01" wire:model="price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- Stock Quantity -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                                    <input type="number" wire:model="stock_quantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['stock_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                    <select wire:model="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="">Select Category</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->_id); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- Brand -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                    <select wire:model="brand_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="">Select Brand</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($brand->_id); ?>"><?php echo e($brand->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- SKU (Auto-generated) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-xs text-gray-500">(Auto-generated)</span></label>
                                    <input type="text" wire:model="sku" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- Dual Image Upload -->
                                <div class="md:col-span-2 space-y-6">
                                    <!-- Main Image Section -->
                                    <div class="bg-gray-50 p-4 rounded-lg border">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                                            Main Product Image
                                        </label>
                                        <input type="file" wire:model="mainImage" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['mainImage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                        
                                        <!--[if BLOCK]><![endif]--><?php if($mainImage): ?>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Preview:</p>
                                                <img src="<?php echo e($mainImage->temporaryUrl()); ?>" class="h-20 w-20 object-cover rounded-lg mt-1 border-2 border-emerald-200">
                                            </div>
                                        <?php elseif($currentMainImage): ?>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Current main image:</p>
                                                <img src="<?php echo e(Storage::url($currentMainImage)); ?>" class="h-20 w-20 object-cover rounded-lg mt-1 border-2 border-emerald-200">
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <!-- Additional Images Section -->
                                    <div class="bg-blue-50 p-4 rounded-lg border">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-images text-blue-500 mr-1"></i>
                                            Additional Images
                                        </label>
                                        <input type="file" wire:model="additionalImages" accept="image/*" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['additionalImages.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                        <p class="text-xs text-gray-500 mt-1">You can select multiple images for gallery</p>
                                        
                                        <!--[if BLOCK]><![endif]--><?php if($additionalImages && count($additionalImages) > 0): ?>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Previews:</p>
                                                <div class="flex flex-wrap gap-2 mt-1">
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $additionalImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $additionalImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <img src="<?php echo e($additionalImage->temporaryUrl()); ?>" class="h-16 w-16 object-cover rounded border-2 border-blue-200">
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </div>
                                        <?php elseif(!empty($currentAdditionalImages)): ?>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Current additional images:</p>
                                                <div class="flex flex-wrap gap-2 mt-1">
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currentAdditionalImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $additionalImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <img src="<?php echo e(Storage::url($additionalImage)); ?>" class="h-16 w-16 object-cover rounded border-2 border-blue-200">
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>

                                <!-- Status Toggles -->
                                <div class="md:col-span-2 flex space-x-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="is_active" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-700">Active</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="is_featured" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-700">Featured</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal" class="px-6 py-2 border border-gray-400 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-100 transition-all duration-200 shadow-md hover:shadow-lg">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <?php echo e($showCreateModal ? 'Create' : 'Update'); ?> Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Delete Confirmation Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showDeleteModal): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 py-4">
                        <div class="flex items-center mb-4">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Delete Product</h3>
                            <!--[if BLOCK]><![endif]--><?php if($productToDeleteName): ?>
                                <p class="text-sm text-gray-500 mb-2">Are you sure you want to delete <strong>"<?php echo e($productToDeleteName); ?>"</strong>?</p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <p class="text-sm text-gray-500">This action cannot be undone.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-center space-x-4">
                        <button wire:click="closeModal" class="px-6 py-2 border border-gray-400 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-100 transition-all duration-200 shadow-md hover:shadow-lg">
                            Cancel
                        </button>
                        <button wire:click="deleteProduct" class="px-6 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-red-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            Delete Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <style>
        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-scale-in {
            animation: scale-in 0.5s ease-out forwards;
        }
    </style>
</div><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/livewire/admin/product-management.blade.php ENDPATH**/ ?>