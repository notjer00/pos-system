<?php

namespace Database\Factories;

use App\Models\SalesTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesTransaction>
 */
class SalesTransactionFactory extends Factory
{
    protected $model = SalesTransaction::class;

    public function definition(): array
    {
        return [
            'cashier_id' => User::factory(),
            'discount_id' => null,
            'total_amount' => fake()->randomFloat(2, 10, 5000),
            'status' => 'completed',
        ];
    }
}
