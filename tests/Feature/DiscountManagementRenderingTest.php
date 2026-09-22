<?php

use App\Livewire\Admin\DiscountManagement;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

it('renders the create discount modal without array_map Collection error', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    Product::factory()->count(3)->create(['is_active' => true]);

    Livewire::test(DiscountManagement::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->assertSee('Store-wide: applies to all products');
});
