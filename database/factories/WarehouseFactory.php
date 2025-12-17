<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $warehouseNumber = fake()->unique()->numberBetween(1, 999);
        
        return [
            'name' => 'Almacén ' . fake()->city(),
            'code' => 'ALM-' . str_pad($warehouseNumber, 3, '0', STR_PAD_LEFT),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'España',
            'postal_code' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'manager_id' => null, // Will be set after users are created
            'active' => true,
        ];
    }
}
