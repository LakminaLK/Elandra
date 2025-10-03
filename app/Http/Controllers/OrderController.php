<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['orderItems'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load(['orderItems', 'user']);
        
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        $this->authorize('view', $order);
        
        // Only allow cancellation of pending orders
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order cannot be cancelled');
        }

        // Update order status
        $order->update(['status' => 'cancelled']);

        // Restore product stock
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Order cancelled successfully');
    }
}
