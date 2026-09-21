<?php

use App\Livewire\Admin\UserManagement;
use App\Models\User;
use Livewire\Livewire;

test('non-current user can be deleted via Livewire', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    $this->actingAs($admin);

    Livewire::test(UserManagement::class)
        ->call('delete', $cashier)
        ->assertDispatched('notify', message: 'User deleted successfully!');

    $this->assertDatabaseMissing('users', ['id' => $cashier->id]);
});

test('cannot delete yourself', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);

    Livewire::test(UserManagement::class)
        ->call('delete', $admin)
        ->assertDispatched('notify', message: 'Cannot delete yourself.', type: 'error');

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('delete works with raw integer ID parameter', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    $this->actingAs($admin);

    Livewire::test(UserManagement::class)
        ->call('delete', $cashier->id)
        ->assertDispatched('notify', message: 'User deleted successfully!');

    $this->assertDatabaseMissing('users', ['id' => $cashier->id]);
});
