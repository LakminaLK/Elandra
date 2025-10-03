<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view the model.
     * Only customers can view their own orders. Admin operations use separate Admin guard.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can update the model.
     * Customer users cannot update orders. Admin operations use separate Admin guard.
     */
    public function update(User $user, Order $order): bool
    {
        return false; // Customers cannot update orders
    }

    /**
     * Determine whether the user can delete the model.
     * Customer users cannot delete orders. Admin operations use separate Admin guard.
     */
    public function delete(User $user, Order $order): bool
    {
        return false; // Customers cannot delete orders
    }
}
