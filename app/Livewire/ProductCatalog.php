<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\MongoProduct;
use App\Models\MongoCategory;
use App\Models\MongoBrand;
use Illuminate\Support\Facades\Cache;

class ProductCatalog extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $filterCategory = '';
    public $filterBrand = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $inStockOnly = false;
    public $featuredOnly = false;
    public $perPage = 12;
    
    // Additional filter properties
    public $priceRange = '';
    public $minPrice = '';
    public $maxPrice = '';
    
    // UI state properties
    public $showMobileFilters = false;
    public $viewMode = 'grid';
    public $showFilters = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterBrand' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'inStockOnly' => ['except' => false],
        'featuredOnly' => ['except' => false],
        'priceRange' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
    ];

    public function mount()
    {
        // Initialize from URL parameters - simple approach like admin
        $this->search = request('search', '');
        $this->filterCategory = request('filterCategory', '');
        $this->filterBrand = request('filterBrand', '');
        $this->sortField = request('sortField', 'created_at');
        $this->sortDirection = request('sortDirection', 'desc');
        $this->inStockOnly = (bool) request('inStockOnly', false);
        $this->featuredOnly = (bool) request('featuredOnly', false);
        $this->priceRange = request('priceRange', '');
        $this->minPrice = request('minPrice', '');
        $this->maxPrice = request('maxPrice', '');
    }

    public function clearAllFilters()
    {
        $this->search = '';
        $this->filterCategory = '';
        $this->filterBrand = '';
        $this->inStockOnly = false;
        $this->featuredOnly = false;
        $this->resetPage();
    }

    public function hasActiveFilters()
    {
        return !empty($this->search) || 
               !empty($this->filterCategory) || 
               !empty($this->filterBrand) || 
               $this->inStockOnly || 
               $this->featuredOnly;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterBrand()
    {
        $this->resetPage();
    }

    public function updatedInStockOnly()
    {
        $this->resetPage();
    }

    public function updatedFeaturedOnly()
    {
        $this->resetPage();
    }

    // Test method to verify filter functionality
    public function testFilters()
    {
        $products = $this->getFilteredProducts();
        
        $debug = [
            'search' => $this->search,
            'filterCategory' => $this->filterCategory,
            'filterBrand' => $this->filterBrand,
            'inStockOnly' => $this->inStockOnly,
            'featuredOnly' => $this->featuredOnly,
            'products_count' => $products->count(),
        ];
        
        session()->flash('message', 'Filter Debug: ' . json_encode($debug));
    }
    
    private function getFilteredProducts()
    {
        $query = MongoProduct::where('is_active', true);

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('brand', 'like', '%' . $this->search . '%');
            });
        }

        // Apply category filter
        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        // Apply brand filter
        if ($this->filterBrand) {
            $query->where('brand', $this->filterBrand);
        }

        // Apply stock filter
        if ($this->inStockOnly) {
            $query->where('stock_quantity', '>', 0);
        }

        // Apply featured filter
        if ($this->featuredOnly) {
            $query->where('is_featured', true);
        }

        // Apply price filters
        if ($this->minPrice) {
            $query->where('price', '>=', floatval($this->minPrice));
        }

        if ($this->maxPrice) {
            $query->where('price', '<=', floatval($this->maxPrice));
        }

        return $query;
    }

    public function updatingPriceRange()
    {
        $this->resetPage();
        $this->dispatch('filter-changed');
        
        // Parse price range and set min/max prices
        if ($this->priceRange) {
            if ($this->priceRange === '500+') {
                $this->minPrice = 500;
                $this->maxPrice = '';
            } else {
                $range = explode('-', $this->priceRange);
                $this->minPrice = $range[0] ?? '';
                $this->maxPrice = $range[1] ?? '';
            }
        } else {
            $this->minPrice = '';
            $this->maxPrice = '';
        }
    }

    public function updatingInStockOnly()
    {
        $this->resetPage();
        $this->dispatch('filter-changed');
    }

    public function updatingFeaturedOnly()
    {
        $this->resetPage();
        $this->dispatch('filter-changed');
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'filterCategory',
            'filterBrand',
            'priceRange',
            'minPrice',
            'maxPrice',
            'inStockOnly',
            'featuredOnly'
        ]);
        $this->resetPage();
    }

    public function toggleMobileFilters()
    {
        $this->showMobileFilters = !$this->showMobileFilters;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }





    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
    }
    
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }
    
    #[On('product-updated')]
    public function refreshProducts()
    {
        // Real-time product updates
        $this->resetPage();
    }
    
    #[On('cart-updated')]
    public function updateCartCount()
    {
        // Emit event to update cart count in header
        $this->dispatch('refresh-cart-count');
    }
    
    public function addToCart($productId)
    {
        // Redirect to login if not authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please login to add products to cart.');
        }

        try {
            // Find product and validate
            $product = MongoProduct::find($productId);
            if (!$product || !$product->is_active) {
                session()->flash('error', 'Product not available!');
                return;
            }

            // Check stock
            if (isset($product->stock_quantity) && $product->stock_quantity <= 0) {
                session()->flash('error', 'Product is out of stock!');
                return;
            }

            // Check current cart quantity for this product
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            $currentCartItem = \App\Models\Cart::where('product_id', $productId)
                ->where(function($query) use ($userId, $sessionId) {
                    $query->where('user_id', $userId)
                          ->orWhere('session_id', $sessionId);
                })
                ->first();

            $currentQuantity = $currentCartItem ? $currentCartItem->quantity : 0;
            
            // Check maximum limit: 3 items or stock limit (whichever is lower)
            $maxQuantity = 3;
            if (isset($product->stock_quantity)) {
                $maxQuantity = min(3, $product->stock_quantity);
            }

            if ($currentQuantity >= $maxQuantity) {
                if (isset($product->stock_quantity) && $product->stock_quantity < 3) {
                    session()->flash('error', 'Only ' . $product->stock_quantity . ' items available in stock');
                } else {
                    session()->flash('error', 'Maximum 3 items per product allowed');
                }
                return;
            }
            
            \App\Models\Cart::addProduct($productId, 1, $userId, $sessionId);
            
            // Emit event to update cart counter
            $this->dispatch('cart-updated');
            
            session()->flash('message', $product->name . ' added to cart successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Add to cart error: ' . $e->getMessage());
            session()->flash('error', 'Failed to add product to cart. Please try again.');
        }
    }
    
    public function addToWishlist($productId)
    {
        // Add wishlist functionality
        $this->dispatch('add-to-wishlist', productId: $productId);
    }

    public function getCartQuantity($productId)
    {
        if (!auth()->check()) {
            return 0;
        }

        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $cartItem = \App\Models\Cart::where('product_id', $productId)
            ->where(function($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                      ->orWhere('session_id', $sessionId);
            })
            ->first();

        return $cartItem ? $cartItem->quantity : 0;
    }

    public function canAddToCart($product)
    {
        if (!auth()->check()) {
            return true;
        }

        $currentQuantity = $this->getCartQuantity($product->_id);
        $maxQuantity = 3;
        
        if (isset($product->stock_quantity)) {
            $maxQuantity = min(3, $product->stock_quantity);
        }

        return $currentQuantity < $maxQuantity;
    }

    public function render()
    {
        // Get filtered products with pagination
        $query = $this->getFilteredProducts();
        $products = $query->orderBy($this->sortField, $this->sortDirection)
                         ->paginate($this->perPage);

        // Get unique categories and brands from all products
        $allProducts = MongoProduct::where('is_active', true)->get();
        $categories = $allProducts->pluck('category')->filter()->unique()->sort()->values()->all();
        $brands = $allProducts->pluck('brand')->filter()->unique()->sort()->values()->all();

        return view('livewire.product-catalog', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
