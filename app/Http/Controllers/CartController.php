<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);
        
        return view('cart.index', compact('cartItems'));
    }
}
