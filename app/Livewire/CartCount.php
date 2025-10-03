<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Cart;

class CartCount extends Component
{
    public $count = 0;
    public $mobile = false;

    public function mount($mobile = false)
    {
        $this->mobile = $mobile;
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $this->count = Cart::getCartCount($userId, $sessionId);
    }

    public function render()
    {
        return view('livewire.cart-count');
    }
}