<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Connexion — ArchiDoc DGB');
});

test('active user can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'email' => 'active@archidoc.cm',
        'password' => bcrypt('secret123'),
        'statut' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'active@archidoc.cm',
        'password' => 'secret123',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('archives.index'));
});

test('suspended user cannot authenticate and receives error message', function () {
    $user = User::factory()->suspended()->create([
        'email' => 'suspended@archidoc.cm',
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'suspended@archidoc.cm',
        'password' => 'secret123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'user@archidoc.cm',
        'password' => bcrypt('secret123'),
    ]);

    $this->post('/login', [
        'email' => 'user@archidoc.cm',
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('register route is disabled', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
