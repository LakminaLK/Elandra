<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MongoProduct;
use App\Models\Cart;

class ProductShow extends Component
{
    public $product;
    public $selectedImage = 0;
    
    public function mount($productId)
    {
        $this->product = MongoProduct::where('is_active', true)
            ->where(function($query) use ($productId) {
                $query->where('slug', $productId)
                      ->orWhere('_id', $productId);
            })
            ->firstOrFail();
    }

    public function selectImage($index)
    {
        $this->selectedImage = $index;
    }

    public function getCartQuantityProperty()
    {
        if (!auth()->check()) {
            return 0;
        }

        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $cartItem = Cart::where('product_id', (string) $this->product->_id)
            ->where(function($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                      ->orWhere('session_id', $sessionId);
            })
            ->first();

        return $cartItem ? $cartItem->quantity : 0;
    }

    public function getMaxQuantityProperty()
    {
        $maxQuantity = 3;
        if (isset($this->product->stock_quantity)) {
            $maxQuantity = min(3, $this->product->stock_quantity);
        }
        return $maxQuantity;
    }

    public function getCanAddToCartProperty()
    {
        return $this->cartQuantity < $this->maxQuantity;
    }

    public function addToCart()
    {
        // Redirect to login if not authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please login to add products to cart.');
        }

        try {
            // Check stock
            if (isset($this->product->stock_quantity) && $this->product->stock_quantity <= 0) {
                session()->flash('error', 'Product is out of stock!');
                return;
            }

            // Check current cart quantity for this product
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            $currentCartItem = Cart::where('product_id', (string) $this->product->_id)
                ->where(function($query) use ($userId, $sessionId) {
                    $query->where('user_id', $userId)
                          ->orWhere('session_id', $sessionId);
                })
                ->first();

            $currentQuantity = $currentCartItem ? $currentCartItem->quantity : 0;
            
            // Check maximum limit: 3 items or stock limit (whichever is lower)
            $maxQuantity = 3;
            if (isset($this->product->stock_quantity)) {
                $maxQuantity = min(3, $this->product->stock_quantity);
            }

            if ($currentQuantity >= $maxQuantity) {
                if (isset($this->product->stock_quantity) && $this->product->stock_quantity < 3) {
                    session()->flash('error', 'Only ' . $this->product->stock_quantity . ' items available in stock');
                } else {
                    session()->flash('error', 'Maximum 3 items per product allowed');
                }
                return;
            }
            
            Cart::addProduct((string) $this->product->_id, 1, $userId, $sessionId);
            
            // Emit event to update cart counter
            $this->dispatch('cart-updated');
            
            session()->flash('message', $this->product->name . ' added to cart successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Add to cart error: ' . $e->getMessage());
            session()->flash('error', 'Failed to add product to cart. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.product-show');
    }
}
