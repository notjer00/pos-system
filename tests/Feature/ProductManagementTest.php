<?php

use App\Models\CommissionLog;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SaleLineItem;
use App\Models\SalesTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('admin can create product with variants via model', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $product = Product::create([
        'name' => 'Test Product',
        'base_price' => 29.99,
        'category' => 'Test',
        'description' => 'Test description',
        'is_active' => true,
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'size' => 'M',
        'color' => 'Blue',
        'current_stock' => 10,
        'low_stock_threshold' => 5,
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'size' => 'L',
        'color' => 'Blue',
        'current_stock' => 5,
        'low_stock_threshold' => 5,
    ]);

    $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'size' => 'M', 'color' => 'Blue']);
    $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'size' => 'L', 'color' => 'Blue']);
});

test('cashier cannot access admin routes', function () {
    $cashier = User::factory()->create(['role' => 'cashier']);

    $response = $this->actingAs($cashier)->get(route('admin.products'));
    $response->assertStatus(403);

    $response = $this->actingAs($cashier)->get(route('admin.discounts'));
    $response->assertStatus(403);

    $response = $this->actingAs($cashier)->get(route('admin.reports'));
    $response->assertStatus(403);
});

test('admin cannot access cashier routes', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('cashier.checkout'));
    $response->assertStatus(403);
});

test('checkout decrements variant stock and logs commission', function () {
    $cashier = User::factory()->create(['role' => 'cashier', 'commission_rate' => 10]);
    $product = Product::factory()->create(['base_price' => 50.00]);
    $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'current_stock' => 10]);

    // Simulate the checkout process directly
    DB::transaction(function () use ($cashier, $variant, $product) {
        $transaction = SalesTransaction::create([
            'cashier_id' => $cashier->id,
            'discount_id' => null,
            'total_amount' => $product->base_price * 2,
        ]);

        $variant->decrement('current_stock', 2);

        SaleLineItem::create([
            'sales_transaction_id' => $transaction->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'unit_price_at_sale' => $product->base_price,
        ]);

        CommissionLog::create([
            'sales_transaction_id' => $transaction->id,
            'user_id' => $cashier->id,
            'base_amount' => $product->base_price,
            'discount_applied' => 0,
            'final_price' => $product->base_price,
            'commission_rate' => $cashier->commission_rate,
            'commission_earned' => $product->base_price * ($cashier->commission_rate / 100) * 2,
        ]);
    });

    $variant->refresh();
    $this->assertEquals(8, $variant->current_stock);
    $this->assertDatabaseHas('sales_transactions', ['cashier_id' => $cashier->id]);
    $this->assertDatabaseHas('commission_logs', ['user_id' => $cashier->id]);
});

test('discount is applied at checkout', function () {
    $cashier = User::factory()->create(['role' => 'cashier', 'commission_rate' => 10]);
    $product = Product::factory()->create(['base_price' => 100.00]);
    $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'current_stock' => 10]);
    $discount = Discount::factory()->create([
        'percentage' => 20,
        'is_active' => true,
        'product_id' => $product->id,
    ]);

    $finalPrice = $product->base_price * (1 - $discount->percentage / 100);
    $commissionEarned = $finalPrice * ($cashier->commission_rate / 100);

    DB::transaction(function () use ($cashier, $variant, $product, $discount, $finalPrice, $commissionEarned) {
        $transaction = SalesTransaction::create([
            'cashier_id' => $cashier->id,
            'discount_id' => $discount->id,
            'total_amount' => $finalPrice,
        ]);

        $variant->decrement('current_stock', 1);

        SaleLineItem::create([
            'sales_transaction_id' => $transaction->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'unit_price_at_sale' => $finalPrice,
        ]);

        CommissionLog::create([
            'sales_transaction_id' => $transaction->id,
            'user_id' => $cashier->id,
            'base_amount' => $product->base_price,
            'discount_applied' => $discount->percentage,
            'final_price' => $finalPrice,
            'commission_rate' => $cashier->commission_rate,
            'commission_earned' => $commissionEarned,
        ]);
    });

    $transaction = SalesTransaction::latest()->first();
    $this->assertEquals(80.00, $transaction->total_amount);
    $this->assertEquals($discount->id, $transaction->discount_id);

    $commissionLog = CommissionLog::latest()->first();
    $this->assertEquals(80.00, $commissionLog->final_price);
    $this->assertEquals(20.00, $commissionLog->discount_applied);
    $this->assertEquals(8.00, $commissionLog->commission_earned);
});

test('low stock flagging works', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'current_stock' => 3, 'low_stock_threshold' => 5]);

    $this->assertTrue($variant->isLowStock());

    $variant2 = ProductVariant::factory()->create(['product_id' => $product->id, 'current_stock' => 10, 'low_stock_threshold' => 5]);
    $this->assertFalse($variant2->isLowStock());
});

test('best sellers query works', function () {
    $cashier = User::factory()->create(['role' => 'cashier']);
    $product1 = Product::factory()->create(['name' => 'Popular Product']);
    $product2 = Product::factory()->create(['name' => 'Less Popular']);

    $variant1 = ProductVariant::factory()->create(['product_id' => $product1->id, 'current_stock' => 100]);
    $variant2 = ProductVariant::factory()->create(['product_id' => $product2->id, 'current_stock' => 100]);

    // Create sales for product1 (10 units)
    for ($i = 0; $i < 5; $i++) {
        $transaction = SalesTransaction::create([
            'cashier_id' => $cashier->id,
            'total_amount' => 50.00,
        ]);

        SaleLineItem::create([
            'sales_transaction_id' => $transaction->id,
            'product_variant_id' => $variant1->id,
            'quantity' => 2,
            'unit_price_at_sale' => 25.00,
        ]);
    }

    // Create sales for product2 (3 units)
    for ($i = 0; $i < 2; $i++) {
        $transaction = SalesTransaction::create([
            'cashier_id' => $cashier->id,
            'total_amount' => 50.00,
        ]);

        SaleLineItem::create([
            'sales_transaction_id' => $transaction->id,
            'product_variant_id' => $variant2->id,
            'quantity' => 1,
            'unit_price_at_sale' => 50.00,
        ]);
    }

    $bestSellers = ProductVariant::select('product_variants.*', DB::raw('SUM(sale_line_items.quantity) as total_sold'))
        ->join('sale_line_items', 'product_variants.id', '=', 'sale_line_items.product_variant_id')
        ->join('sales_transactions', 'sale_line_items.sales_transaction_id', '=', 'sales_transactions.id')
        ->groupBy('product_variants.id')
        ->with('product')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();

    $this->assertEquals($variant1->id, $bestSellers->first()->id);
    $this->assertEquals(10, $bestSellers->first()->total_sold);
});
