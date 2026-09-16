<?php

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SalesTransaction;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('admin dashboard shows sales stats and quick actions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Sales');
    $response->assertSee('Commission Summary');
    $response->assertSee('Low Stock Alerts');
});

test('cashier can access checkout page', function () {
    $cashier = User::factory()->create(['role' => 'cashier', 'commission_rate' => 5]);
    $this->actingAs($cashier);

    $response = $this->get(route('cashier.checkout'));

    $response->assertOk();
});

test('admin dashboard reflects seeded transaction totals', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    Product::factory()
        ->has(ProductVariant::factory()->state(['current_stock' => 10]), 'variants')
        ->create(['is_active' => true]);

    SalesTransaction::create([
        'cashier_id' => $admin->id,
        'total_amount' => 1500,
        'status' => 'completed',
    ]);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee('₱1,500.00');
});
