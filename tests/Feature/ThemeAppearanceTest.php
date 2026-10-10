<?php

use App\Models\User;

test('dashboard renders the theme selector and shared theme script', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('window.ElevationTheme', false);
    $response->assertSee('aria-label="Appearance mode"', false);
    $response->assertSee('data-theme-mode="light"', false);
    $response->assertSee('data-theme-mode="dark"', false);
    $response->assertSee('data-theme-mode="system"', false);
});

test('dashboard header links to settings and appearance pages', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee(route('profile.edit'), false);
    $response->assertSee(route('appearance.edit'), false);
});

test('a dark appearance cookie renders the html dark class', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->withUnencryptedCookie('appearance', 'dark')->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('class="dark" data-appearance="dark"', false);
});

test('a light appearance cookie renders without the html dark class', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->withUnencryptedCookie('appearance', 'light')->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('data-appearance="light"', false);
    $response->assertDontSee('class="dark"', false);
});

test('the login page boots the shared theme script', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertSee('window.ElevationTheme', false);
    $response->assertSee('html.dark', false);
});
