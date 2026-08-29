<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Volt;

test('OTP verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create([
        'otp_code' => Hash::make('123456'),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Session::put('pending_verification_user_id', $user->id);

    $response = $this->get('/verify-otp');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.verify-otp');
});

test('user can verify OTP and login', function () {
    $user = User::factory()->unverified()->create([
        'email' => 'test@example.com',
        'otp_code' => Hash::make('123456'),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Session::put('pending_verification_user_id', $user->id);

    // Test direct update first
    $user->update([
        'email_verified_at' => now(),
        'otp_code' => null,
        'otp_expires_at' => null,
    ]);
    $user->refresh();
    logger('Direct update test - email_verified_at: ' . ($user->email_verified_at ?? 'null'));

    $component = Volt::test('pages.auth.verify-otp')
        ->set('otp', '123456');

    $component->call('verifyOtp');

    $component->assertRedirect(route('dashboard', absolute: false));

    $user->refresh();
    logger('Test: user email_verified_at after refresh: ' . ($user->email_verified_at ?? 'null'));
    expect($user->email_verified_at)->not->toBeNull();
    expect($user->otp_code)->toBeNull();
    expect($user->otp_expires_at)->toBeNull();
});

test('invalid OTP shows error', function () {
    $user = User::factory()->unverified()->create([
        'otp_code' => Hash::make('123456'),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Session::put('pending_verification_user_id', $user->id);

    $component = Volt::test('pages.auth.verify-otp')
        ->set('otp', '654321');

    $component->call('verifyOtp');

    $component->assertHasErrors(['otp' => 'Invalid OTP. Please try again.']);
});

test('expired OTP shows error', function () {
    $user = User::factory()->unverified()->create([
        'otp_code' => Hash::make('123456'),
        'otp_expires_at' => now()->subMinutes(5),
    ]);

    Session::put('pending_verification_user_id', $user->id);

    $component = Volt::test('pages.auth.verify-otp')
        ->set('otp', '123456');

    $component->call('verifyOtp');

    $component->assertHasErrors(['otp' => 'OTP has expired. Please request a new one.']);
});

test('user can resend OTP', function () {
    $user = User::factory()->unverified()->create([
        'email' => 'test@example.com',
        'otp_code' => Hash::make('123456'),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Session::put('pending_verification_user_id', $user->id);

    $component = Volt::test('pages.auth.verify-otp');
    $oldOtpHash = $user->otp_code;

    $component->call('resendOtp');

    $component->assertHasNoErrors();

    $user->refresh();
    expect($user->otp_code)->not->toBe($oldOtpHash);
    expect($user->otp_expires_at->gt(now()))->toBeTrue();
});

test('unverified user cannot login', function () {
    $user = User::factory()->unverified()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $component = Volt::test('pages.auth.login')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password123');

    $component->call('login');

    $component->assertHasErrors(['form.email' => 'Please verify your email address before logging in. Check your inbox for the OTP code.']);
    $this->assertGuest();
});

test('verified user can login', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]);

    $component = Volt::test('pages.auth.login')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password123');

    $component->call('login');

    $component->assertHasNoErrors();
    $this->assertAuthenticatedAs($user);
});