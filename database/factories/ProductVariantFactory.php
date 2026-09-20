<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $variantName = fake()->randomElement([
            'Standard',
            'Large / Black',
            'Small / White',
            'Chocolate / 60g',
            '330ml',
            '200g',
            '1 Liter',
            'Vanilla / 500ml',
        ]);

        return [
            'product_id' => Product::factory(),
            'variant_name' => $variantName,
            'sku' => strtoupper(fake()->bothify('???-####')),
            'size' => null,
            'color' => null,
            'current_stock' => fake()->numberBetween(0, 20),
            'low_stock_threshold' => 5,
        ];
    }
}
