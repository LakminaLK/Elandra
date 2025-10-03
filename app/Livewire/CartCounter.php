<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    #[On('cart-updated')]
    public function updateCartCount()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $this->cartCount = Cart::getCartCount($userId, $sessionId);
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
