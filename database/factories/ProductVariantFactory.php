<?php

namespace Database\Factories;

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
        $sizeSystem = fake()->randomElement(['apparel', 'us_footwear', 'eu_footwear', 'uk_footwear']);

        $size = $sizeSystem === 'apparel'
            ? fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34', '36', '38'])
            : ($sizeSystem === 'us_footwear'
                ? fake()->randomElement([4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 13, 14])
                : ($sizeSystem === 'eu_footwear'
                    ? fake()->randomElement(range(35, 48))
                    : fake()->randomElement([3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 13])));

        $footwearSize = in_array($sizeSystem, ['us_footwear', 'eu_footwear', 'uk_footwear']) ? (string) $size : null;
        $regularSize = $sizeSystem === 'apparel' ? (string) $size : null;

        return [
            'size' => $regularSize,
            'footwear_size' => $footwearSize,
            'color' => fake()->randomElement(['Black', 'White', 'Gray', 'Navy', 'Blue', 'Red', 'Green']),
            'current_stock' => fake()->numberBetween(0, 20),
            'low_stock_threshold' => 5,
        ];
    }
}
