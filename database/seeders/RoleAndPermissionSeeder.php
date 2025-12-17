<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Products
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            
            // Inventory
            'view_inventory',
            'manage_inventory',
            'adjust_stock',
            
            // Orders
            'view_orders',
            'create_orders',
            'process_orders',
            'cancel_orders',
            
            // Warehouses
            'view_warehouses',
            'manage_warehouses',
            
            // Reports
            'view_reports',
            'export_data',
            
            // Audit
            'view_audit_logs',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        
        // SuperAdmin: Full access
        $superAdmin = \Spatie\Permission\Models\Role::create(['name' => 'SuperAdmin']);
        $superAdmin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // Warehouse Manager: Manage inventory and orders for their warehouse
        $warehouseManager = \Spatie\Permission\Models\Role::create(['name' => 'WarehouseManager']);
        $warehouseManager->givePermissionTo([
            'view_products',
            'view_inventory',
            'manage_inventory',
            'adjust_stock',
            'view_orders',
            'create_orders',
            'process_orders',
            'view_warehouses',
            'view_reports',
        ]);

        // Auditor: Read-only access
        $auditor = \Spatie\Permission\Models\Role::create(['name' => 'Auditor']);
        $auditor->givePermissionTo([
            'view_products',
            'view_inventory',
            'view_orders',
            'view_warehouses',
            'view_reports',
            'view_audit_logs',
        ]);
    }
}
