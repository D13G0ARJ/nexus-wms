<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ['Rojo', 'Azul', 'Verde', 'Negro', 'Blanco', 'Gris'];
        $sizes = ['S', 'M', 'L', 'XL', '10mm', '15mm', '20mm'];
        $materials = ['Acero', 'Plástico', 'Aluminio', 'Hierro'];
        
        $color = fake()->randomElement($colors);
        $size = fake()->randomElement($sizes);
        $material = fake()->randomElement($materials);
        
        $name = "{$color} - {$size}";
        
        return [
            'product_id' => \App\Models\Product::factory(),
            'sku' => 'VAR-' . strtoupper(fake()->unique()->bothify('####??')),
            'name' => $name,
            'attributes' => [
                'color' => $color,
                'size' => $size,
                'material' => $material,
            ],
            'price_modifier' => fake()->randomFloat(4, -10, 30), // Can be negative (discounts)
            'status' => 'active',
        ];
    }
}
