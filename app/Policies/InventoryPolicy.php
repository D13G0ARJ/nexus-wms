<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;

class InventoryPolicy
{
    /**
     * Determine if the user can view any inventory.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_inventory');
    }

    /**
     * Determine if the user can view the inventory item.
     * Warehouse managers can only view inventory from their assigned warehouse.
     */
    public function view(User $user, Inventory $inventory): bool
    {
        if (!$user->hasPermissionTo('view_inventory')) {
            return false;
        }

        // SuperAdmin can view all
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }

        // Warehouse managers can only view their warehouse's inventory
        if ($user->hasRole('WarehouseManager') && $user->warehouse_id) {
            return $inventory->warehouse_id === $user->warehouse_id;
        }

        // Auditors can view all
        return $user->hasRole('Auditor');
    }

    /**
     * Determine if the user can adjust stock.
     * Only users from the same warehouse can adjust stock.
     */
    public function adjustStock(User $user, Inventory $inventory): bool
    {
        if (!$user->hasPermissionTo('adjust_stock')) {
            return false;
        }

        // SuperAdmin can adjust any warehouse
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }

        // Warehouse managers can only adjust their warehouse
        return $user->warehouse_id === $inventory->warehouse_id;
    }

    /**
     * Determine if the user can manage inventory.
     */
    public function manage(User $user): bool
    {
        return $user->hasPermissionTo('manage_inventory');
    }
}
