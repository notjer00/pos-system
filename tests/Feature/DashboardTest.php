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
    $user = User::factory()->create();
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
    $response->assertSee('Quick Actions');
    $response->assertSee(route('admin.products'));
    $response->assertSee(route('admin.discounts'));
    $response->assertSee('<a href="'.route('admin.products').'" wire:navigate', false);
    $response->assertSee('<a href="'.route('admin.reports').'" wire:navigate', false);
});

test('cashier dashboard shows commission and checkout access', function () {
    $cashier = User::factory()->create(['role' => 'cashier', 'commission_rate' => 5]);
    $this->actingAs($cashier);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('My Commission');
    $response->assertSee('Checkout');
    $response->assertSee(route('cashier.checkout'));
    $response->assertSee('<a href="'.route('cashier.checkout').'" wire:navigate', false);
    $response->assertSee('<a href="'.route('cashier.inbox').'" wire:navigate', false);
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
