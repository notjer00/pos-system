<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.register');
});

test('new users can register and are redirected to OTP verification', function () {
    $component = Volt::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123');

    $component->call('register');

    $component->assertRedirect(route('verification.otp', absolute: false));

    $this->assertGuest();

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->otp_code)->not->toBeNull();
    expect($user->otp_expires_at)->not->toBeNull();
    expect($user->email_verified_at)->toBeNull();
    expect(Hash::check('123456', $user->otp_code))->toBeFalse(); // OTP is random
});
