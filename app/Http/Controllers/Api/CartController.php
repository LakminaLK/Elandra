<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Get user's cart items
     */
    public function index(Request $request): JsonResponse
    {
        $cartItems = Cart::with('product.category')
            ->forUser($request->user()->id)
            ->get();

        $total = $cartItems->sum('total_price');
        $totalItems = $cartItems->sum('quantity');

        return response()->json([
            'status' => 'success',
            'data' => [
                'cart_items' => $cartItems,
                'total_amount' => $total,
                'total_items' => $totalItems,
                'formatted_total' => '$' . number_format($total, 2)
            ]
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::findOrFail($request->product_id);

        // Check if product is active and in stock
        if (!$product->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product is not available'
            ], 400);
        }

        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient stock. Available: ' . $product->stock_quantity
            ], 400);
        }

        // Check if item already exists in cart
        $existingCartItem = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCartItem) {
            $newQuantity = $existingCartItem->quantity + $request->quantity;
            
            if ($product->stock_quantity < $newQuantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot add more items. Total would exceed available stock.'
                ], 400);
            }

            $existingCartItem->updateQuantity($newQuantity);
            $cartItem = $existingCartItem;
        } else {
            $cartItem = Cart::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->current_price
            ]);
        }

        $cartItem->load('product');

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
            'data' => [
                'cart_item' => $cartItem
            ]
        ], 201);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, Cart $cartItem): JsonResponse
    {
        // Check if cart item belongs to user
        if ($cartItem->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check stock availability
        if ($cartItem->product->stock_quantity < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient stock. Available: ' . $cartItem->product->stock_quantity
            ], 400);
        }

        $cartItem->updateQuantity($request->quantity);
        $cartItem->load('product');

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item updated successfully',
            'data' => [
                'cart_item' => $cartItem
            ]
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, Cart $cartItem): JsonResponse
    {
        // Check if cart item belongs to user
        if ($cartItem->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $cartItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear(Request $request): JsonResponse
    {
        Cart::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Get cart total
     */
    public function total(Request $request): JsonResponse
    {
        $cartItems = Cart::forUser($request->user()->id)->get();
        $total = $cartItems->sum('total_price');
        $totalItems = $cartItems->sum('quantity');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_amount' => $total,
                'total_items' => $totalItems,
                'formatted_total' => '$' . number_format($total, 2)
            ]
        ]);
    }
}
