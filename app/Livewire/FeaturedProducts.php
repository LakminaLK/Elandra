<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\MongoProduct;

class FeaturedProducts extends Component
{
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

    #[On('cart-updated')]
    public function refreshComponent()
    {
        // This will re-render the component when cart is updated
        $this->render();
    }

    public function render()
    {
        // Get featured products
        $featuredProducts = MongoProduct::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('livewire.featured-products', [
            'featuredProducts' => $featuredProducts
        ]);
    }
}
