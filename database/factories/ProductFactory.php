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
            'category' => fake()->randomElement(['Food', 'Beverage', 'Supplements', 'Clothing', 'Accessories', 'Household']),
            'brand' => fake()->optional()->company(),
            'unit' => fake()->randomElement(['Piece', 'Pack', 'Box', 'Bottle', 'Can', 'Tub', 'Pair', 'Kilogram']),
            'is_active' => true,
        ];
    }
}
