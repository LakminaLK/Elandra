<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get user's orders
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * Create a new order from cart
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|array',
            'shipping_address.street' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.state' => 'required|string',
            'shipping_address.zip' => 'required|string',
            'shipping_address.country' => 'required|string',
            'billing_address' => 'nullable|array',
            'payment_method' => 'required|string|in:credit_card,debit_card,paypal,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get cart items
        $cartItems = Cart::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart is empty'
            ], 400);
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Insufficient stock for {$item->product->name}. Available: {$item->product->stock_quantity}"
                ], 400);
            }
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = $cartItems->sum('total_price');
            $taxRate = 0.10; // 10% tax
            $taxAmount = $subtotal * $taxRate;
            $shippingAmount = $subtotal > 100 ? 0 : 15; // Free shipping over $100
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;

            // Create order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address ?? $request->shipping_address,
                'notes' => $request->notes,
            ]);

            // Create order items and reduce stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->current_price,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                ]);

                // Reduce stock
                $cartItem->product->reduceStock($cartItem->quantity);
            }

            // Clear cart
            Cart::where('user_id', $request->user()->id)->delete();

            DB::commit();

            $order->load('orderItems.product');

            return response()->json([
                'status' => 'success',
                'message' => 'Order placed successfully',
                'data' => [
                    'order' => $order
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order. Please try again.'
            ], 500);
        }
    }

    /**
     * Get specific order
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        // Check if order belongs to user
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $order->load('orderItems.product');

        return response()->json([
            'status' => 'success',
            'data' => [
                'order' => $order
            ]
        ]);
    }

    /**
     * Cancel an order
     */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        // Check if order belongs to user
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!$order->canBeCancelled()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order cannot be cancelled'
            ], 400);
        }

        $order->cancel();

        return response()->json([
            'status' => 'success',
            'message' => 'Order cancelled successfully',
            'data' => [
                'order' => $order
            ]
        ]);
    }

    /**
     * Admin: Get all orders
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = Order::with('user', 'orderItems.product');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by order number or customer
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * Admin: Update order status
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,processing,shipped,delivered,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $newStatus = $request->status;

        // Update status based on the new value
        switch ($newStatus) {
            case 'processing':
                $order->markAsProcessing();
                break;
            case 'shipped':
                $order->markAsShipped();
                break;
            case 'delivered':
                $order->markAsDelivered();
                break;
            case 'completed':
                $order->markAsCompleted();
                break;
            case 'cancelled':
                if (!$order->canBeCancelled()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Order cannot be cancelled at this stage'
                    ], 400);
                }
                $order->cancel();
                break;
            default:
                $order->update(['status' => $newStatus]);
        }

        $order->load('user', 'orderItems.product');

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully',
            'data' => [
                'order' => $order
            ]
        ]);
    }
}
