<?php

namespace Database\Factories;

use App\Models\CommissionLog;
use App\Models\SalesTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CommissionLog>
 */
class CommissionLogFactory extends Factory
{
    protected $model = CommissionLog::class;

    public function definition(): array
    {
        $baseAmount = fake()->randomFloat(2, 50, 5000);
        $commissionRate = fake()->randomFloat(2, 1, 20);

        return [
            'sales_transaction_id' => SalesTransaction::factory(),
            'user_id' => User::factory(),
            'base_amount' => $baseAmount,
            'discount_applied' => 0,
            'final_price' => $baseAmount,
            'commission_rate' => $commissionRate,
            'commission_earned' => round($baseAmount * $commissionRate / 100, 2),
            'is_voided' => false,
        ];
    }
}
