<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productTypes = [
            'Taladro', 'Martillo', 'Destornillador', 'Llave', 'Alicate',
            'Sierra', 'Nivel', 'Cinta Métrica', 'Tornillo', 'Tuerca',
            'Lámpara', 'Cable', 'Interruptor', 'Enchufe', 'Candado',
        ];
        
        $name = fake()->randomElement($productTypes) . ' ' . fake()->word();
        $sku = 'PROD-' . strtoupper(fake()->unique()->bothify('####??'));
        
        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => $name,
            'slug' => \Str::slug($name),
            'sku' => $sku,
            'description' => fake()->sentence(),
            'base_price' => fake()->randomFloat(4, 5, 500),
            'status' => fake()->randomElement(['active', 'active', 'active', 'inactive']),
            'created_by' => null, // Will be set in seeder
        ];
    }
}
