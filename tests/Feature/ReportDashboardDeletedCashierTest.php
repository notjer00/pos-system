<?php

use App\Models\CommissionLog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SaleLineItem;
use App\Models\SalesTransaction;
use App\Models\User;

test('reports page renders with existing cashier in commission summary', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier', 'name' => 'Active Cashier']);

    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    $transaction = SalesTransaction::create([
        'cashier_id' => $cashier->id,
        'total_amount' => 500.00,
        'status' => 'completed',
    ]);

    SaleLineItem::create([
        'sales_transaction_id' => $transaction->id,
        'product_variant_id' => $variant->id,
        'quantity' => 5,
        'unit_price_at_sale' => 100.00,
    ]);

    CommissionLog::create([
        'sales_transaction_id' => $transaction->id,
        'user_id' => $cashier->id,
        'base_amount' => 500.00,
        'discount_applied' => 0,
        'final_price' => 500.00,
        'commission_rate' => 10,
        'commission_earned' => 50.00,
        'is_voided' => false,
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.reports'));

    $response->assertOk();
    $response->assertSee('Active Cashier');
    $response->assertDontSee('Deleted User');
});

test('reports page renders with null user_id in commission logs showing Deleted User', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    $transaction = SalesTransaction::create([
        'cashier_id' => $admin->id,
        'total_amount' => 1000.00,
        'status' => 'completed',
    ]);

    SaleLineItem::create([
        'sales_transaction_id' => $transaction->id,
        'product_variant_id' => $variant->id,
        'quantity' => 10,
        'unit_price_at_sale' => 100.00,
    ]);

    CommissionLog::create([
        'sales_transaction_id' => $transaction->id,
        'user_id' => null,
        'base_amount' => 1000.00,
        'discount_applied' => 0,
        'final_price' => 1000.00,
        'commission_rate' => 10,
        'commission_earned' => 100.00,
        'is_voided' => false,
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.reports'));

    $response->assertOk();
    $response->assertSee('Deleted User');
    $response->assertDontSee('ErrorException');
});

test('reports page handles mix of existing and deleted cashiers in commission summary', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $activeCashier = User::factory()->create(['role' => 'cashier', 'name' => 'Still Here']);

    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    $transaction1 = SalesTransaction::create([
        'cashier_id' => $activeCashier->id,
        'total_amount' => 300.00,
        'status' => 'completed',
    ]);

    SaleLineItem::create([
        'sales_transaction_id' => $transaction1->id,
        'product_variant_id' => $variant->id,
        'quantity' => 3,
        'unit_price_at_sale' => 100.00,
    ]);

    CommissionLog::create([
        'sales_transaction_id' => $transaction1->id,
        'user_id' => $activeCashier->id,
        'base_amount' => 300.00,
        'discount_applied' => 0,
        'final_price' => 300.00,
        'commission_rate' => 10,
        'commission_earned' => 30.00,
        'is_voided' => false,
    ]);

    $transaction2 = SalesTransaction::create([
        'cashier_id' => $admin->id,
        'total_amount' => 700.00,
        'status' => 'completed',
    ]);

    SaleLineItem::create([
        'sales_transaction_id' => $transaction2->id,
        'product_variant_id' => $variant->id,
        'quantity' => 7,
        'unit_price_at_sale' => 100.00,
    ]);

    CommissionLog::create([
        'sales_transaction_id' => $transaction2->id,
        'user_id' => null,
        'base_amount' => 700.00,
        'discount_applied' => 0,
        'final_price' => 700.00,
        'commission_rate' => 10,
        'commission_earned' => 70.00,
        'is_voided' => false,
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.reports'));

    $response->assertOk();
    $response->assertSee('Still Here');
    $response->assertSee('Deleted User');
});
