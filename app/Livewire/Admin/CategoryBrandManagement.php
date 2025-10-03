<?php

namespace App\Livewire\Admin;

use App\Models\MongoCategory;
use App\Models\MongoBrand;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryBrandManagement extends Component
{
    use WithPagination;

    // Category fields
    public $categoryName = '';
    public $categoryDescription = '';
    public $categoryIsActive = true;
    public $editingCategoryId = null;
    public $showCategoryModal = false;
    
    // Brand fields  
    public $brandName = '';
    public $brandDescription = '';
    public $brandIsActive = true;
    public $editingBrandId = null;
    public $showBrandModal = false;

    // Search and filters
    public $categorySearch = '';
    public $brandSearch = '';

    protected $rules = [
        'categoryName' => 'required|string|max:255',
        'categoryDescription' => 'nullable|string|max:500',
        'categoryIsActive' => 'boolean',
        'brandName' => 'required|string|max:255', 
        'brandDescription' => 'nullable|string|max:500',
        'brandIsActive' => 'boolean',
    ];

    // Category Methods
    public function openCreateCategoryModal()
    {
        $this->resetCategoryForm();
        $this->showCategoryModal = true;
    }

    public function openEditCategoryModal($categoryId)
    {
        $category = MongoCategory::find($categoryId);
        if ($category) {
            $this->editingCategoryId = $categoryId;
            $this->categoryName = $category->name;
            $this->categoryDescription = $category->description ?? '';
            $this->categoryIsActive = $category->is_active ?? true;
            $this->showCategoryModal = true;
        }
    }

    public function saveCategory()
    {
        $this->validate([
            'categoryName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string|max:500',
            'categoryIsActive' => 'boolean',
        ]);

        try {
            $data = [
                'name' => $this->categoryName,
                'description' => $this->categoryDescription,
                'is_active' => $this->categoryIsActive,
            ];

            if ($this->editingCategoryId) {
                MongoCategory::find($this->editingCategoryId)->update($data);
                session()->flash('message', 'Category updated successfully!');
            } else {
                MongoCategory::create($data);
                session()->flash('message', 'Category created successfully!');
            }

            $this->closeCategoryModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving category: ' . $e->getMessage());
        }
    }

    public function deleteCategory($categoryId)
    {
        try {
            MongoCategory::find($categoryId)->delete();
            session()->flash('message', 'Category deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    public function closeCategoryModal()
    {
        $this->showCategoryModal = false;
        $this->resetCategoryForm();
    }

    public function resetCategoryForm()
    {
        $this->editingCategoryId = null;
        $this->categoryName = '';
        $this->categoryDescription = '';
        $this->categoryIsActive = true;
        $this->resetValidation();
    }

    // Brand Methods
    public function openCreateBrandModal()
    {
        $this->resetBrandForm();
        $this->showBrandModal = true;
    }

    public function openEditBrandModal($brandId)
    {
        $brand = MongoBrand::find($brandId);
        if ($brand) {
            $this->editingBrandId = $brandId;
            $this->brandName = $brand->name;
            $this->brandDescription = $brand->description ?? '';
            $this->brandIsActive = $brand->is_active ?? true;
            $this->showBrandModal = true;
        }
    }

    public function saveBrand()
    {
        $this->validate([
            'brandName' => 'required|string|max:255',
            'brandDescription' => 'nullable|string|max:500', 
            'brandIsActive' => 'boolean',
        ]);

        try {
            $data = [
                'name' => $this->brandName,
                'description' => $this->brandDescription,
                'is_active' => $this->brandIsActive,
            ];

            if ($this->editingBrandId) {
                MongoBrand::find($this->editingBrandId)->update($data);
                session()->flash('message', 'Brand updated successfully!');
            } else {
                MongoBrand::create($data);
                session()->flash('message', 'Brand created successfully!');
            }

            $this->closeBrandModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving brand: ' . $e->getMessage());
        }
    }

    public function deleteBrand($brandId)
    {
        try {
            MongoBrand::find($brandId)->delete();
            session()->flash('message', 'Brand deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting brand: ' . $e->getMessage());
        }
    }

    public function closeBrandModal()
    {
        $this->showBrandModal = false;
        $this->resetBrandForm();
    }

    public function resetBrandForm()
    {
        $this->editingBrandId = null;
        $this->brandName = '';
        $this->brandDescription = '';
        $this->brandIsActive = true;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = MongoCategory::query()
            ->when($this->categorySearch, function($query) {
                $query->where('name', 'like', '%' . $this->categorySearch . '%');
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'categoriesPage');

        $brands = MongoBrand::query()
            ->when($this->brandSearch, function($query) {
                $query->where('name', 'like', '%' . $this->brandSearch . '%');
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'brandsPage');

        return view('livewire.admin.category-brand-management', [
            'categories' => $categories,
            'brands' => $brands,
        ])->layout('admin.layouts.app');
    }
}
