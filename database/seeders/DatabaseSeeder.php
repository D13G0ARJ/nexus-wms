<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles and Permissions
        $this->call(RoleAndPermissionSeeder::class);

        // 2. Create Users with Roles
        $superAdmin = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@nexuswms.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);
        $superAdmin->assignRole('SuperAdmin');

        $manager1 = \App\Models\User::create([
            'name' => 'Carlos García',
            'email' => 'carlos@nexuswms.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);
        $manager1->assignRole('WarehouseManager');

        $manager2 = \App\Models\User::create([
            'name' => 'Ana Martínez',
            'email' => 'ana@nexuswms.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);
        $manager2->assignRole('WarehouseManager');

        $auditor = \App\Models\User::create([
            'name' => 'Luis Rodríguez',
            'email' => 'luis@nexuswms.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);
        $auditor->assignRole('Auditor');

        // 3. Create Warehouses and assign managers
        $warehouse1 = \App\Models\Warehouse::factory()->create([
            'name' => 'Almacén Madrid',
            'code' => 'ALM-001',
            'city' => 'Madrid',
            'manager_id' => $manager1->id,
        ]);

        $warehouse2 = \App\Models\Warehouse::factory()->create([
            'name' => 'Almacén Barcelona',
            'code' => 'ALM-002',
            'city' => 'Barcelona',
            'manager_id' => $manager2->id,
        ]);

        $warehouse3 = \App\Models\Warehouse::factory()->create([
            'name' => 'Almacén Valencia',
            'code' => 'ALM-003',
            'city' => 'Valencia',
            'manager_id' => null,
        ]);

        // Update users with their warehouse assignment
        $manager1->update(['warehouse_id' => $warehouse1->id]);
        $manager2->update(['warehouse_id' => $warehouse2->id]);

        // 4. Create Categories (10 main categories)
        $categories = \App\Models\Category::factory()->count(10)->create();

        // 5. Create 100 Products with variants
        \App\Models\Product::factory()
            ->count(100)
            ->create([
                'category_id' => fn() => $categories->random()->id,
                'created_by' => $superAdmin->id,
            ])
            ->each(function ($product) use ($warehouse1, $warehouse2, $warehouse3) {
                // Create 2-3 variants per product
                $variantsCount = rand(2, 3);
                
                for ($i = 0; $i < $variantsCount; $i++) {
                    $variant = \App\Models\ProductVariant::factory()->create([
                        'product_id' => $product->id,
                    ]);

                    // Distribute stock across warehouses
                    \App\Models\Inventory::create([
                        'variant_id' => $variant->id,
                        'warehouse_id' => $warehouse1->id,
                        'quantity' => rand(50, 500),
                        'reserved_quantity' => rand(0, 20),
                        'aisle' => 'A' . rand(1, 10),
                        'rack' => 'R' . rand(1, 20),
                        'bin' => 'B' . rand(1, 50),
                        'min_stock' => 20,
                        'max_stock' => 1000,
                    ]);

                    \App\Models\Inventory::create([
                        'variant_id' => $variant->id,
                        'warehouse_id' => $warehouse2->id,
                        'quantity' => rand(30, 400),
                        'reserved_quantity' => rand(0, 15),
                        'aisle' => 'B' . rand(1, 8),
                        'rack' => 'R' . rand(1, 15),
                        'bin' => 'B' . rand(1, 40),
                        'min_stock' => 15,
                        'max_stock' => 800,
                    ]);

                    \App\Models\Inventory::create([
                        'variant_id' => $variant->id,
                        'warehouse_id' => $warehouse3->id,
                        'quantity' => rand(20, 300),
                        'reserved_quantity' => rand(0, 10),
                        'aisle' => 'C' . rand(1, 6),
                        'rack' => 'R' . rand(1, 12),
                        'bin' => 'B' . rand(1, 30),
                        'min_stock' => 10,
                        'max_stock' => 600,
                    ]);
                }
            });

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Users: 4 (1 SuperAdmin, 2 Managers, 1 Auditor)');
        $this->command->info('   - Warehouses: 3');
        $this->command->info('   - Categories: 10');
        $this->command->info('   - Products: 100');
        $this->command->info('   - Variants: ~250');
        $this->command->info('   - Inventory records: ~750');
    }
}
