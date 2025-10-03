<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterMethod = '';
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterMethod()
    {
        $this->resetPage();
    }

    public function processRefund($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        if ($payment->status !== 'completed') {
            session()->flash('error', 'Can only refund completed payments');
            return;
        }

        $payment->update([
            'status' => 'refunded',
            'payment_details' => array_merge(
                $payment->payment_details ?? [], 
                ['refunded_at' => now(), 'refund_reason' => 'Admin refund']
            )
        ]);

        session()->flash('message', 'Payment refunded successfully!');
    }

    public function render()
    {
        $query = Payment::with(['order.user']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('transaction_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('order', function($orderQuery) {
                      $orderQuery->where('order_number', 'like', '%' . $this->search . '%')
                                ->orWhereHas('user', function($userQuery) {
                                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                                });
                  });
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterMethod) {
            $query->where('method', $this->filterMethod);
        }

        $payments = $query->latest()->paginate(15);

        $paymentMethods = Payment::distinct('method')->pluck('method');
        $paymentStatuses = ['pending', 'completed', 'failed', 'refunded'];

        return view('livewire.admin.payment-management', [
            'payments' => $payments,
            'paymentMethods' => $paymentMethods,
            'paymentStatuses' => $paymentStatuses,
        ]);
    }
}