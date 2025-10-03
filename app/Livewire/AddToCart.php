<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MongoProduct;
use App\Models\Cart;

class AddToCart extends Component
{
    public MongoProduct $product;
    public $quantity = 1;
    public $isLoading = false;
    public $showQuantity = true;
    public $showSuccess = false;
    public $errorMessage = null;

    public function mount(MongoProduct $product, $showQuantity = true)
    {
        $this->product = $product;
        $this->showQuantity = $showQuantity;
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->stock_quantity) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        // Clear previous messages
        $this->errorMessage = null;
        $this->showSuccess = false;

        if (!auth()->check()) {
            $this->errorMessage = 'Please login to add items to cart';
            return;
        }

        if (!$this->product->is_active) {
            $this->errorMessage = 'This product is not available';
            return;
        }

        if ($this->product->stock_quantity < $this->quantity) {
            $this->errorMessage = 'Insufficient stock. Available: ' . $this->product->stock_quantity;
            return;
        }

        $this->isLoading = true;

        try {
            // Check if item already exists in cart
            $existingCartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $this->product->id)
                ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $this->quantity;
                
                if ($this->product->stock_quantity < $newQuantity) {
                    $this->errorMessage = 'Cannot add more items. Total would exceed available stock.';
                    $this->isLoading = false;
                    return;
                }

                $existingCartItem->updateQuantity($newQuantity);
            } else {
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $this->product->id,
                    'quantity' => $this->quantity,
                    'price' => $this->product->current_price
                ]);
            }

            // Emit event to update cart counter
            $this->dispatch('cart-updated');
            
            $this->showSuccess = true;
            session()->flash('success', 'Product added to cart successfully!');
            
            // Reset quantity
            $this->quantity = 1;
            
            // Hide success message after 3 seconds
            $this->dispatch('hideSuccess');

        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to add product to cart. Please try again.';
        }

        $this->isLoading = false;
    }

    public function hideSuccessMessage()
    {
        $this->showSuccess = false;
    }

    public function hideErrorMessage()
    {
        $this->errorMessage = null;
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
