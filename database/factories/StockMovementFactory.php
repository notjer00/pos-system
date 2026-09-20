<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'type' => fake()->randomElement(['restock', 'sale', 'void_reversal', 'refund_reversal']),
            'quantity_change' => fake()->numberBetween(-10, 50),
            'note' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
