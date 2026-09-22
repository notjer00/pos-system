<?php

use App\Models\User;

test('unauthenticated user visiting / is redirected to login', function () {
    $response = $this->get('/');
    $response->assertRedirect(route('login'));
});

test('admin user visiting / is redirected to dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->get('/');
    $response->assertRedirect(route('dashboard'));
});

test('cashier user visiting / is redirected to cashier checkout', function () {
    $cashier = User::factory()->create(['role' => 'cashier', 'commission_rate' => 5]);
    $this->actingAs($cashier);

    $response = $this->get('/');
    $response->assertRedirect(route('cashier.checkout'));
});

test('admin user visiting dashboard sees 200', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('cashier user visiting dashboard sees 403', function () {
    $cashier = User::factory()->create(['role' => 'cashier', 'commission_rate' => 5]);
    $this->actingAs($cashier);

    $response = $this->get(route('dashboard'));
    $response->assertForbidden();
});

test('cashier user visiting cashier checkout sees 200', function () {
    $cashier = User::factory()->create(['role' => 'cashier', 'commission_rate' => 5]);
    $this->actingAs($cashier);

    $response = $this->get(route('cashier.checkout'));
    $response->assertOk();
});

test('admin user visiting cashier checkout sees 403', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->get(route('cashier.checkout'));
    $response->assertForbidden();
});
