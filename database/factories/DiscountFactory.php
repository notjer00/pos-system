<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Discount>
 */
class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true).' Sale',
            'percentage' => fake()->randomFloat(2, 5, 50),
            'is_active' => fake()->boolean(30),
            'starts_at' => fake()->optional()->dateTimeBetween('-1 month', '+1 month'),
            'ends_at' => fake()->optional()->dateTimeBetween('+1 month', '+3 months'),
            'product_id' => null,
        ];
    }
}
