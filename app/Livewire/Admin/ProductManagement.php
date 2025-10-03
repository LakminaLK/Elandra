<?php

namespace App\Livewire\Admin;

use App\Models\MongoProduct;
use App\Models\MongoCategory;
use App\Models\MongoBrand;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filterCategory = '';
    public $filterStatus = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Product form fields
    public $productId = null;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $short_description = '';
    public $price = '';
    public $sku = '';
    public $stock_quantity = 0;
    public $category_id = '';
    public $brand_id = '';
    public $brand = '';
    public $weight = '';
    public $dimensions = '';
    public $is_active = true;
    public $is_featured = false;
    public $meta_title = '';
    public $meta_description = '';
    public $mainImage = null;
    public $additionalImages = [];
    public $currentMainImage = '';
    public $currentAdditionalImages = [];
    public $productToDelete = null;
    public $productToDeleteName = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'filterCategory' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|string|max:255',
            'brand_id' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'mainImage' => 'nullable|image|max:2048',
            'additionalImages.*' => 'nullable|image|max:2048',
        ];

        // MongoDB doesn't have built-in unique validation, so we'll handle it manually if needed

        return $rules;
    }

    public function updatedName()
    {
        if (!$this->productId) {
            $this->slug = Str::slug($this->name);
            // Auto-generate SKU from product name with hyphens between words
            $this->sku = strtoupper(str_replace(' ', '-', trim($this->name)));
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($productId)
    {
        $product = MongoProduct::find($productId);
        
        if (!$product) {
            session()->flash('error', 'Product not found!');
            return;
        }
        
        $this->productId = $product->_id;
        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->short_description = $product->short_description ?? '';
        $this->price = $product->price;
        $this->sku = $product->sku ?? '';
        $this->stock_quantity = $product->stock_quantity;
        // Map category and brand names to IDs for the dropdowns
        $category = $product->category ? MongoCategory::where('name', $product->category)->first() : null;
        $brand = $product->brand ? MongoBrand::where('name', $product->brand)->first() : null;
        
        $this->category_id = $category ? $category->_id : '';
        $this->brand_id = $brand ? $brand->_id : '';
        $this->brand = $product->brand ?? '';
        $this->weight = $product->weight ?? '';
        $this->dimensions = $product->dimensions ?? '';
        $this->is_active = $product->is_active ?? true;
        $this->is_featured = $product->is_featured ?? false;
        $this->meta_title = $product->meta_title ?? '';
        $this->meta_description = $product->meta_description ?? '';
        
        // Handle current images for dual system
        if (!empty($product->images)) {
            $this->currentMainImage = $product->images[0]; // First image as main
            $this->currentAdditionalImages = array_slice($product->images, 1); // Rest as additional
        } else {
            $this->currentMainImage = '';
            $this->currentAdditionalImages = [];
        }
        
        $this->showEditModal = true;
    }

    public function openDeleteModal($productId)
    {
        try {
            $product = MongoProduct::find($productId);
            
            if (!$product) {
                session()->flash('error', 'Product not found with ID: ' . $productId);
                return;
            }
            
            // Clear any previous data
            $this->resetValidation();
            
            // Set the product data for deletion
            $this->productId = $productId;
            $this->productToDelete = $productId;
            $this->productToDeleteName = $product->name;
            $this->showDeleteModal = true;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading product for deletion: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->productId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->short_description = '';
        $this->price = '';
        $this->sku = '';
        $this->stock_quantity = 0;
        $this->category_id = '';
        $this->brand_id = '';
        $this->brand = '';
        $this->weight = '';
        $this->dimensions = '';
        $this->is_active = true;
        $this->is_featured = false;
        $this->meta_title = '';
        $this->meta_description = '';
        $this->mainImage = null;
        $this->additionalImages = [];
        $this->currentMainImage = '';
        $this->currentAdditionalImages = [];
        $this->productToDelete = null;
        $this->productToDeleteName = '';
        $this->resetValidation();
    }

    public function createProduct()
    {
        $this->validate();

        // Get category and brand names from IDs
        $category = $this->category_id ? MongoCategory::find($this->category_id) : null;
        $brand = $this->brand_id ? MongoBrand::find($this->brand_id) : null;

        $productData = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'category' => $category ? $category->name : '',
            'category_slug' => $category ? $category->slug : '',
            'brand' => $brand ? $brand->name : '',
            'brand_slug' => $brand ? $brand->slug : '',
            'stock_quantity' => (int) $this->stock_quantity,
            'sku' => $this->sku,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
        ];

        // Add optional fields
        if (!empty($this->short_description)) {
            $productData['short_description'] = $this->short_description;
        }
        if (!empty($this->weight)) {
            $productData['weight'] = (float) $this->weight;
        }
        if (!empty($this->dimensions)) {
            $productData['dimensions'] = $this->dimensions;
        }

        // Handle dual image upload
        $images = [];
        
        // Handle main image
        if ($this->mainImage) {
            $mainImagePath = $this->mainImage->store('products/main', 'public');
            $images[] = $mainImagePath;
        }
        
        // Handle additional images
        if (!empty($this->additionalImages)) {
            foreach ($this->additionalImages as $additionalImage) {
                $additionalImagePath = $additionalImage->store('products/additional', 'public');
                $images[] = $additionalImagePath;
            }
        }
        
        $productData['images'] = $images;

        MongoProduct::create($productData);

        session()->flash('message', 'Product created successfully!');
        $this->closeModal();
    }

    public function updateProduct()
    {
        $this->validate();

        $product = MongoProduct::findOrFail($this->productId);
        
        // Get category and brand names from IDs
        $category = $this->category_id ? MongoCategory::find($this->category_id) : null;
        $brand = $this->brand_id ? MongoBrand::find($this->brand_id) : null;
        
        $productData = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'category' => $category ? $category->name : '',
            'category_slug' => $category ? $category->slug : '',
            'brand' => $brand ? $brand->name : '',
            'brand_slug' => $brand ? $brand->slug : '',
            'stock_quantity' => (int) $this->stock_quantity,
            'sku' => $this->sku,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
        ];

        // Add optional fields
        if (!empty($this->short_description)) {
            $productData['short_description'] = $this->short_description;
        }
        if (!empty($this->weight)) {
            $productData['weight'] = (float) $this->weight;
        }
        if (!empty($this->dimensions)) {
            $productData['dimensions'] = $this->dimensions;
        }

        // Handle dual image upload
        $images = [];
        $shouldUpdateImages = false;
        
        // Handle main image
        if ($this->mainImage) {
            $mainImagePath = $this->mainImage->store('products/main', 'public');
            $images[] = $mainImagePath;
            $shouldUpdateImages = true;
        }
        
        // Handle additional images
        if (!empty($this->additionalImages)) {
            foreach ($this->additionalImages as $additionalImage) {
                $additionalImagePath = $additionalImage->store('products/additional', 'public');
                $images[] = $additionalImagePath;
            }
            $shouldUpdateImages = true;
        }
        
        // Update images if new ones were uploaded
        if ($shouldUpdateImages) {
            // Delete old images if exist
            if (!empty($product->images)) {
                foreach ($product->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $productData['images'] = $images;
        }

        $product->update($productData);

        session()->flash('message', 'Product updated successfully!');
        $this->closeModal();
    }

    public function deleteProduct()
    {
        try {
            // Check if we have a product ID
            if (!$this->productId) {
                session()->flash('error', 'No product ID specified for deletion!');
                $this->closeModal();
                return;
            }

            $product = MongoProduct::find($this->productId);
            if (!$product) {
                session()->flash('error', 'Product not found! ID: ' . $this->productId);
                $this->closeModal();
                return;
            }
            
            $productName = $product->name;
            
            // Delete images if exist
            if (!empty($product->images)) {
                foreach ($product->images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
            
            $product->delete();

            session()->flash('message', 'Product "' . $productName . '" deleted successfully!');
            $this->closeModal();
            
            // Reset pagination to first page to avoid empty pages
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting product: ' . $e->getMessage());
            $this->closeModal();
        }
    }

    public function toggleProductStatus($productId)
    {
        $product = MongoProduct::find($productId);
        if (!$product) {
            session()->flash('error', 'Product not found!');
            return;
        }
        
        $product->update(['is_active' => !$product->is_active]);
        
        $status = $product->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Product {$status} successfully!");
    }

    public function toggleFeatured($productId)
    {
        $product = MongoProduct::find($productId);
        if (!$product) {
            session()->flash('error', 'Product not found!');
            return;
        }
        
        $product->update(['is_featured' => !$product->is_featured]);
        
        $status = $product->is_featured ? 'marked as featured' : 'removed from featured';
        session()->flash('message', "Product {$status} successfully!");
    }

    public function render()
    {
        $query = MongoProduct::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('brand', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === '1');
        }

        $products = $query->orderBy($this->sortField, $this->sortDirection)
                         ->paginate(15);

        // Get active categories and brands
        $categories = MongoCategory::active()->orderBy('name')->get();
        $brands = MongoBrand::active()->orderBy('name')->get();

        return view('livewire.admin.product-management', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}