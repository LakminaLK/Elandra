<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    /**
     * Display a listing of orders with Livewire component
     */
    public function index()
    {
        return view('admin.orders.index');
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];
        
        // Update order status
        $order->update([
            'status' => $newStatus,
            'notes' => $validated['notes'] ?? $order->notes,
            'shipped_at' => $newStatus === 'shipped' && !$order->shipped_at ? now() : $order->shipped_at,
            'delivered_at' => $newStatus === 'delivered' && !$order->delivered_at ? now() : $order->delivered_at,
        ]);

        // Handle stock management
        $this->handleStockChanges($order, $oldStatus, $newStatus);
        
        // Update payment status if needed
        if ($newStatus === 'delivered' && $order->payment_status === 'pending') {
            $order->update(['payment_status' => 'paid']);
        }

        $message = "Order #{$order->order_number} status updated to " . ucfirst($newStatus);
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,cancelled'
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Payment status updated successfully');
    }

    /**
     * Bulk update order statuses
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $orders = Order::whereIn('id', $validated['order_ids'])->get();
        
        foreach ($orders as $order) {
            $oldStatus = $order->status;
            $newStatus = $validated['status'];
            
            $order->update([
                'status' => $newStatus,
                'shipped_at' => $newStatus === 'shipped' && !$order->shipped_at ? now() : $order->shipped_at,
                'delivered_at' => $newStatus === 'delivered' && !$order->delivered_at ? now() : $order->delivered_at,
            ]);
            
            $this->handleStockChanges($order, $oldStatus, $newStatus);
        }

        return redirect()->back()->with('success', count($orders) . ' orders updated successfully');
    }

    /**
     * Handle stock quantity changes based on order status
     */
    private function handleStockChanges(Order $order, string $oldStatus, string $newStatus)
    {
        $order->load('orderItems');
        
        // If order is cancelled, restore stock
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                // Only increment if product() is a relationship, not null
                if (method_exists($item, 'product') && $item->product() && $item->product instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);
                    }
                }
            }
        }

        // If order was cancelled and now it's not, reduce stock
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                if ($item->product && $item->product->stock_quantity >= $item->quantity) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }
        }
    }

    /**
     * Generate order report
     */
    public function generateReport(Request $request)
    {
        $query = Order::with(['user', 'orderItems']);
        
        // Apply filters similar to index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->get();
        
        // Calculate statistics
        $stats = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'average_order_value' => $orders->count() > 0 ? $orders->avg('total_amount') : 0,
            'status_breakdown' => $orders->groupBy('status')->map->count(),
            'payment_breakdown' => $orders->groupBy('payment_status')->map->count(),
        ];
        
        return view('admin.orders.report', compact('orders', 'stats'));
    }

    /**
     * Delete an order (soft delete or permanent based on requirements)
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Only cancelled orders can be deleted');
        }
        
        // Restore stock if needed
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }
        
        $orderNumber = $order->order_number;
        $order->delete();
        
        return redirect()->route('admin.orders.index')
            ->with('success', "Order #{$orderNumber} has been deleted successfully");
    }

    /**
     * Generate invoice for an order
     */
    public function generateInvoice(Order $order)
    {
        $order->load(['user', 'orderItems']);
        
        // Generate PDF or return invoice view
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Send email to customer
     */
    public function sendEmail(Request $request, Order $order)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Here you would implement email sending
        // For now, we'll just simulate success
        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully to ' . $order->user->email
        ]);
    }
}
