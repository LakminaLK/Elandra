<div class="p-6">
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

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Category & Brand Management</h1>
        <p class="text-gray-600 mt-2">Manage product categories and brands</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Categories Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Categories</h2>
                    <button wire:click="openCreateCategoryModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus"></i>
                        <span>Add Category</span>
                    </button>
                </div>
                <div class="mt-4">
                    <input type="text" wire:model.live="categorySearch" placeholder="Search categories..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="p-6">
                <!--[if BLOCK]><![endif]--><?php if($categories->count() > 0): ?>
                    <div class="space-y-3">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900"><?php echo e($category->name); ?></h3>
                                    <!--[if BLOCK]><![endif]--><?php if($category->description): ?>
                                        <p class="text-sm text-gray-500 mt-1"><?php echo e($category->description); ?></p>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <div class="flex items-center mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($category->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="openEditCategoryModal('<?php echo e($category->_id); ?>')" 
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </button>
                                    <button wire:click="deleteCategory('<?php echo e($category->_id); ?>')" 
                                            onclick="return confirm('Are you sure you want to delete this category?')"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-trash mr-1"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="mt-6">
                        <?php echo e($categories->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-tags text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No categories found. Create your first category!</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Brands Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Brands</h2>
                    <button wire:click="openCreateBrandModal" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus"></i>
                        <span>Add Brand</span>
                    </button>
                </div>
                <div class="mt-4">
                    <input type="text" wire:model.live="brandSearch" placeholder="Search brands..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            
            <div class="p-6">
                <!--[if BLOCK]><![endif]--><?php if($brands->count() > 0): ?>
                    <div class="space-y-3">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900"><?php echo e($brand->name); ?></h3>
                                    <!--[if BLOCK]><![endif]--><?php if($brand->description): ?>
                                        <p class="text-sm text-gray-500 mt-1"><?php echo e($brand->description); ?></p>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <div class="flex items-center mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($brand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($brand->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="openEditBrandModal('<?php echo e($brand->_id); ?>')" 
                                            class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </button>
                                    <button wire:click="deleteBrand('<?php echo e($brand->_id); ?>')" 
                                            onclick="return confirm('Are you sure you want to delete this brand?')"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-trash mr-1"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="mt-6">
                        <?php echo e($brands->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-bookmark text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No brands found. Create your first brand!</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showCategoryModal): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCategoryModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveCategory">
                        <div class="bg-white px-6 py-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                <?php echo e($editingCategoryId ? 'Edit Category' : 'Create Category'); ?>

                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                                    <input type="text" wire:model="categoryName" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['categoryName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea wire:model="categoryDescription" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['categoryDescription'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="categoryIsActive" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button type="button" wire:click="closeCategoryModal" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700">
                                <?php echo e($editingCategoryId ? 'Update' : 'Create'); ?> Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Brand Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showBrandModal): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeBrandModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveBrand">
                        <div class="bg-white px-6 py-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                <?php echo e($editingBrandId ? 'Edit Brand' : 'Create Brand'); ?>

                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand Name</label>
                                    <input type="text" wire:model="brandName" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['brandName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea wire:model="brandDescription" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['brandDescription'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="brandIsActive" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button type="button" wire:click="closeBrandModal" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700">
                                <?php echo e($editingBrandId ? 'Update' : 'Create'); ?> Brand
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\Elandra\resources\views/livewire/admin/category-brand-management.blade.php ENDPATH**/ ?>