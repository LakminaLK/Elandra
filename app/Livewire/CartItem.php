<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartItem extends Component
{
    public $cartItem;
    public $quantity;

    public function mount($cartItem)
    {
        $this->cartItem = $cartItem;
        $this->quantity = $cartItem->quantity;
    }

    public function incrementQuantity()
    {
        // Get the product data from MongoDB
        $product = $this->cartItem->product;
        
        if (!$product) {
            session()->flash('error', 'Product not found');
            return;
        }
        
        // Check maximum limit: 3 items or stock limit (whichever is lower)
        $stockQuantity = $product->stock_quantity ?? 0;
        $maxQuantity = min(3, $stockQuantity);
        
        if ($this->quantity >= $maxQuantity) {
            if ($stockQuantity < 3) {
                session()->flash('error', 'Only ' . $stockQuantity . ' items available in stock');
            } else {
                session()->flash('error', 'Maximum 3 items per product allowed');
            }
            return;
        }
        
        $this->quantity++;
        $this->updateQuantity();
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->updateQuantity();
        }
    }

    public function updateQuantity()
    {
        $this->cartItem->update(['quantity' => $this->quantity]);
        $this->dispatch('cart-updated');
        $this->dispatch('$refresh'); // Refresh the parent component
    }

    public function removeItem()
    {
        $this->cartItem->delete();
        $this->dispatch('cart-updated');
        $this->dispatch('item-removed');
        
        session()->flash('message', $this->cartItem->product_name . ' removed from cart.');
        
        // Redirect to refresh the page
        return redirect()->route('cart');
    }

    public function render()
    {
        return view('livewire.cart-item');
    }
}
