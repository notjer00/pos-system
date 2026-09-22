<?php

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SaleLineItem;
use App\Models\SalesTransaction;
use App\Models\User;

test('transaction with existing cashier displays cashier name', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier', 'name' => 'Jane Cashier']);

    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    $transaction = SalesTransaction::create([
        'cashier_id' => $cashier->id,
        'total_amount' => 100.00,
        'status' => 'completed',
    ]);

    SaleLineItem::create([
        'sales_transaction_id' => $transaction->id,
        'product_variant_id' => $variant->id,
        'quantity' => 1,
        'unit_price_at_sale' => 100.00,
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.transactions'));

    $response->assertOk();
    $response->assertSee('Jane Cashier');
});

test('transaction with deleted cashier renders successfully and shows fallback', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    $transaction = SalesTransaction::create([
        'cashier_id' => null,
        'total_amount' => 250.00,
        'status' => 'completed',
    ]);

    SaleLineItem::create([
        'sales_transaction_id' => $transaction->id,
        'product_variant_id' => $variant->id,
        'quantity' => 2,
        'unit_price_at_sale' => 125.00,
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.transactions'));

    $response->assertOk();
    $response->assertSee('Deleted User');
    $response->assertDontSee('ErrorException');
});

test('transaction with null cashier_id is visible in transaction history', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $transaction = SalesTransaction::create([
        'cashier_id' => null,
        'total_amount' => 50.00,
        'status' => 'completed',
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.transactions'));

    $response->assertOk();
    $response->assertSee('#'.$transaction->id);
    $response->assertSee('50.00');
});
