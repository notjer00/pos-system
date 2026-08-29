<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'base_price' => fake()->randomFloat(2, 10, 200),
            'category' => fake()->randomElement(['Tops', 'Bottoms', 'Outerwear', 'Dresses', 'Accessories', 'Footwear']),
            'description' => fake()->sentence(),
            'is_active' => true,
            'gender' => fake()->randomElement(['male', 'female', 'unisex']),
            'size_system' => fake()->randomElement(['apparel', 'us_footwear', 'eu_footwear', 'uk_footwear']),
        ];
    }
}
