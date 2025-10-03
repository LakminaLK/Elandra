<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartTotals extends Component
{
    public $cartItems;
    public $subtotal;
    public $tax;
    public $shipping;
    public $total;
    public $itemCount;

    protected $listeners = ['cart-updated' => 'refreshTotals'];

    public function mount()
    {
        $this->refreshTotals();
    }

    public function refreshTotals()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $this->cartItems = Cart::getCartItems($userId, $sessionId);
        
        $this->subtotal = $this->cartItems->sum('total_price');
        $this->tax = $this->subtotal * 0.10; // 10% tax
        $this->shipping = $this->subtotal > 50 ? 0 : 10; // Free shipping over $50
        $this->total = $this->subtotal + $this->tax + $this->shipping;
        $this->itemCount = $this->cartItems->sum('quantity');
    }

    public function proceedToCheckout()
    {
        if ($this->cartItems->isEmpty()) {
            session()->flash('error', 'Your cart is empty');
            return;
        }

        // Redirect to checkout GET route which will handle the POST redirect
        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.cart-totals');
    }
}