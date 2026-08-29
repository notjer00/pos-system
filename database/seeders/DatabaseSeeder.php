<?php

namespace Database\Seeders;

use App\Models\CommissionLog;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SaleLineItem;
use App\Models\SalesTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'commission_rate' => 10.00,
        ]);

        // Create cashier user
        $cashier = User::factory()->create([
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
            'commission_rate' => 10.00,
        ]);

        // Create products with variants
        $products = [
            [
                'name' => 'Classic T-Shirt',
                'base_price' => 29.99,
                'category' => 'Tops',
                'description' => 'Comfortable cotton t-shirt for everyday wear',
                'variants' => [
                    ['size' => 'S', 'color' => 'Black', 'stock' => 10],
                    ['size' => 'M', 'color' => 'Black', 'stock' => 15],
                    ['size' => 'L', 'color' => 'Black', 'stock' => 8],
                    ['size' => 'XL', 'color' => 'Black', 'stock' => 5],
                    ['size' => 'S', 'color' => 'White', 'stock' => 12],
                    ['size' => 'M', 'color' => 'White', 'stock' => 18],
                    ['size' => 'L', 'color' => 'White', 'stock' => 6],
                    ['size' => 'XL', 'color' => 'White', 'stock' => 3],
                ],
            ],
            [
                'name' => 'Slim Fit Jeans',
                'base_price' => 59.99,
                'category' => 'Bottoms',
                'description' => 'Modern slim fit jeans with stretch',
                'variants' => [
                    ['size' => '28', 'color' => 'Blue', 'stock' => 7],
                    ['size' => '30', 'color' => 'Blue', 'stock' => 12],
                    ['size' => '32', 'color' => 'Blue', 'stock' => 9],
                    ['size' => '34', 'color' => 'Blue', 'stock' => 4],
                    ['size' => '28', 'color' => 'Black', 'stock' => 8],
                    ['size' => '30', 'color' => 'Black', 'stock' => 14],
                    ['size' => '32', 'color' => 'Black', 'stock' => 11],
                    ['size' => '34', 'color' => 'Black', 'stock' => 5],
                ],
            ],
            [
                'name' => 'Hooded Sweatshirt',
                'base_price' => 49.99,
                'category' => 'Outerwear',
                'description' => 'Warm fleece hoodie with front pocket',
                'variants' => [
                    ['size' => 'S', 'color' => 'Gray', 'stock' => 6],
                    ['size' => 'M', 'color' => 'Gray', 'stock' => 10],
                    ['size' => 'L', 'color' => 'Gray', 'stock' => 7],
                    ['size' => 'XL', 'color' => 'Gray', 'stock' => 4],
                    ['size' => 'S', 'color' => 'Navy', 'stock' => 5],
                    ['size' => 'M', 'color' => 'Navy', 'stock' => 9],
                    ['size' => 'L', 'color' => 'Navy', 'stock' => 6],
                    ['size' => 'XL', 'color' => 'Navy', 'stock' => 2],
                ],
            ],
            [
                'name' => 'Summer Dress',
                'base_price' => 39.99,
                'category' => 'Dresses',
                'description' => 'Light and breezy summer dress',
                'variants' => [
                    ['size' => 'XS', 'color' => 'Floral', 'stock' => 8],
                    ['size' => 'S', 'color' => 'Floral', 'stock' => 12],
                    ['size' => 'M', 'color' => 'Floral', 'stock' => 10],
                    ['size' => 'L', 'color' => 'Floral', 'stock' => 4],
                    ['size' => 'XS', 'color' => 'Solid Blue', 'stock' => 7],
                    ['size' => 'S', 'color' => 'Solid Blue', 'stock' => 11],
                    ['size' => 'M', 'color' => 'Solid Blue', 'stock' => 9],
                    ['size' => 'L', 'color' => 'Solid Blue', 'stock' => 3],
                ],
            ],
            [
                'name' => 'Polo Shirt',
                'base_price' => 34.99,
                'category' => 'Tops',
                'description' => 'Classic polo shirt for casual or business casual',
                'variants' => [
                    ['size' => 'S', 'color' => 'Navy', 'stock' => 9],
                    ['size' => 'M', 'color' => 'Navy', 'stock' => 14],
                    ['size' => 'L', 'color' => 'Navy', 'stock' => 8],
                    ['size' => 'XL', 'color' => 'Navy', 'stock' => 5],
                    ['size' => 'S', 'color' => 'White', 'stock' => 10],
                    ['size' => 'M', 'color' => 'White', 'stock' => 15],
                    ['size' => 'L', 'color' => 'White', 'stock' => 7],
                    ['size' => 'XL', 'color' => 'White', 'stock' => 4],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $variants = $productData['variants'];
            unset($productData['variants']);

            // Set default gender and size_system for demo data
            $productData['gender'] = $productData['gender'] ?? 'unisex';
            $productData['size_system'] = $productData['size_system'] ?? 'apparel';

            $product = Product::create($productData);

            foreach ($variants as $variant) {
                $variantData = [
                    'product_id' => $product->id,
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'current_stock' => $variant['stock'],
                    'low_stock_threshold' => 5,
                ];

                // Create variant and generate SKU using model method
                $variantModel = new ProductVariant($variantData);
                $variantModel->setRelation('product', $product);
                $variantData['sku'] = $variantModel->generateSku();

                ProductVariant::create($variantData);
            }
        }

        // Create discounts
        Discount::create([
            'name' => 'Summer Sale 20%',
            'percentage' => 20.00,
            'is_active' => false,
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(21),
        ]);

        Discount::create([
            'name' => 'Clearance 30%',
            'percentage' => 30.00,
            'is_active' => false,
            'product_id' => Product::where('category', 'Outerwear')->first()?->id,
            'starts_at' => now()->addDays(1),
            'ends_at' => now()->addDays(14),
        ]);

        Discount::create([
            'name' => 'Flash Sale 15%',
            'percentage' => 15.00,
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(2),
        ]);

        // Create sample sales transactions
        $this->createSampleTransactions($cashier);
    }

    private function createSampleTransactions(User $cashier): void
    {
        $variants = ProductVariant::where('current_stock', '>', 0)->get();

        for ($i = 0; $i < 10; $i++) {
            $variant = $variants->random();
            $quantity = rand(1, 3);

            if ($variant->current_stock < $quantity) {
                continue;
            }

            $activeDiscount = $variant->product->activeDiscount();
            $discountPercentage = $activeDiscount ? $activeDiscount->percentage : 0;
            $finalPrice = $variant->product->base_price * (1 - $discountPercentage / 100);

            $transaction = SalesTransaction::create([
                'cashier_id' => $cashier->id,
                'discount_id' => $activeDiscount?->id,
                'total_amount' => $finalPrice * $quantity,
            ]);

            SaleLineItem::create([
                'sales_transaction_id' => $transaction->id,
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
                'unit_price_at_sale' => $finalPrice,
            ]);

            CommissionLog::create([
                'sales_transaction_id' => $transaction->id,
                'user_id' => $cashier->id,
                'base_amount' => $variant->product->base_price,
                'discount_applied' => $discountPercentage,
                'final_price' => $finalPrice,
                'commission_rate' => $cashier->commission_rate,
                'commission_earned' => $finalPrice * ($cashier->commission_rate / 100) * $quantity,
            ]);

            $variant->decrement('current_stock', $quantity);
        }
    }
}
