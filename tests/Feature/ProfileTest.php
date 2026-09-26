<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/profile');

    $response->assertOk();
    $response->assertSee($user->name);
    $response->assertSee($user->email);
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => 'Nouveau Nom Test',
        'email' => 'nouveau.email@archidoc.cm',
        'phone' => '+237 677 11 22 33',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/profile');

    $user->refresh();

    expect($user->name)->toBe('Nouveau Nom Test');
    expect($user->email)->toBe('nouveau.email@archidoc.cm');
});

test('password can be updated', function () {
    $user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);

    $response = $this->actingAs($user)->put('/password', [
        'current_password' => 'old-password',
        'password' => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    expect(Hash::check('new-password-123', $user->fresh()->password))->toBeTrue();
});
