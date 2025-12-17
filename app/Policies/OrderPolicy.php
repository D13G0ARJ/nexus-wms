<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine if the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_orders');
    }

    /**
     * Determine if the user can view the order.
     * Warehouse managers can only view orders from their warehouse.
     */
    public function view(User $user, Order $order): bool
    {
        if (!$user->hasPermissionTo('view_orders')) {
            return false;
        }

        // SuperAdmin can view all orders
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }

        // Warehouse managers can only view their warehouse orders
        if ($user->hasRole('WarehouseManager') && $user->warehouse_id) {
            return $order->warehouse_id === $user->warehouse_id;
        }

        // Auditors can view all
        return $user->hasRole('Auditor');
    }

    /**
     * Determine if the user can create orders.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_orders');
    }

    /**
     * Determine if the user can process the order.
     * Only users from the order's warehouse can process it.
     */
    public function process(User $user, Order $order): bool
    {
        if (!$user->hasPermissionTo('process_orders')) {
            return false;
        }

        // SuperAdmin can process any order
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }

        // Warehouse managers can only process their warehouse orders
        return $user->warehouse_id === $order->warehouse_id;
    }

    /**
     * Determine if the user can cancel the order.
     * Only SuperAdmin or the warehouse manager can cancel.
     */
    public function cancel(User $user, Order $order): bool
    {
        if (!$user->hasPermissionTo('cancel_orders')) {
            return false;
        }

        if ($user->hasRole('SuperAdmin')) {
            return true;
        }

        // WarehouseManager can cancel if it's their warehouse and order is not yet processed
        return $user->warehouse_id === $order->warehouse_id 
            && in_array($order->status, ['pending', 'processing']);
    }

    /**
     * Determine if the user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        // Only SuperAdmin can delete orders
        return $user->hasRole('SuperAdmin');
    }
}
