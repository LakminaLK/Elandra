<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\MongoProduct;

class CartController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);
        
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please login to add items to cart'], 401);
            }
            return redirect()->route('login')->with('message', 'Please login to add products to cart.');
        }

        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'integer|min:1|max:3'
        ]);

        try {
            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;

            // Find product and validate
            $product = MongoProduct::find($productId);
            if (!$product || !$product->is_active) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Product not available!']);
                }
                return back()->with('error', 'Product not available!');
            }

            // Check stock
            if (isset($product->stock_quantity) && $product->stock_quantity <= 0) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Product is out of stock!']);
                }
                return back()->with('error', 'Product is out of stock!');
            }

            // Check current cart quantity for this product
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            $currentCartItem = Cart::where('product_id', $productId)
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
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Maximum quantity limit reached for this product']);
                }
                return back()->with('error', 'Maximum quantity limit reached for this product');
            }

            // Add to cart
            Cart::addProduct($productId, $quantity, $userId, $sessionId);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => $product->name . ' added to cart successfully!'
                ]);
            }

            return back()->with('success', $product->name . ' added to cart successfully!');

        } catch (\Exception $e) {
            \Log::error('Add to cart error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to add product to cart. Please try again.']);
            }
            return back()->with('error', 'Failed to add product to cart. Please try again.');
        }
    }
}
