<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Electrónica',
            'Herramientas',
            'Construcción',
            'Ferretería',
            'Plomería',
            'Electricidad',
            'Jardinería',
            'Pintura',
            'Seguridad',
            'Iluminación',
        ]);
        
        return [
            'name' => $name,
            'slug' => \Str::slug($name),
            'description' => fake()->sentence(),
            'parent_id' => null, // Will be set manually for subcategories
        ];
    }
}
