<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPaymentStatus = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showStatusModal = false;
    public $selectedOrderId = null;
    public $selectedOrderStatus = '';
    public $statusNotes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterPaymentStatus' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterPaymentStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openStatusModal($orderId, $currentStatus)
    {
        $this->selectedOrderId = $orderId;
        $this->selectedOrderStatus = $currentStatus;
        $this->statusNotes = '';
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->selectedOrderId = null;
        $this->selectedOrderStatus = '';
        $this->statusNotes = '';
    }

    public function updateOrderStatus()
    {
        $this->validate([
            'selectedOrderStatus' => 'required|in:processing,shipped,delivered,cancelled',
            'statusNotes' => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($this->selectedOrderId);
        $oldStatus = $order->status;
        
        $order->update([
            'status' => $this->selectedOrderStatus,
            'notes' => $this->statusNotes ? $order->notes . "\n" . now()->format('Y-m-d H:i') . ": " . $this->statusNotes : $order->notes,
        ]);

        // Handle stock changes if needed
        $this->handleStockChanges($order, $oldStatus, $this->selectedOrderStatus);

        session()->flash('message', 'Order status updated successfully!');
        $this->closeStatusModal();
    }

    private function handleStockChanges($order, $oldStatus, $newStatus)
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
                if (method_exists($item, 'product') && $item->product() && $item->product instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $product = $item->product;
                    if ($product && $product->stock_quantity >= $item->quantity) {
                        $product->decrement('stock_quantity', $item->quantity);
                    }
                }
            }
        }
    }

    public function render()
    {
        $query = Order::with(['orderItems', 'user']);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply status filter
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Apply payment status filter (based on order status)
        if ($this->filterPaymentStatus) {
            if ($this->filterPaymentStatus === 'paid') {
                $query->whereIn('status', ['processing', 'shipped', 'delivered']);
            } elseif ($this->filterPaymentStatus === 'refunded') {
                $query->where('status', 'cancelled');
            } elseif ($this->filterPaymentStatus === 'pending') {
                $query->where('status', 'pending');
            }
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $orders = $query->paginate(10);

        // Get counts for stats
        $totalOrders = Order::count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        return view('livewire.admin.order-management', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'processingOrders' => $processingOrders,
            'shippedOrders' => $shippedOrders,
            'deliveredOrders' => $deliveredOrders,
            'availableStatuses' => Order::getAvailableStatuses(),
        ]);
    }
}