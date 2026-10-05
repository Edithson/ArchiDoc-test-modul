<?php

use App\Models\User;

test('404 custom error page renders with ArchiDoc design layout', function () {
    $response = $this->get('/non-existent-route-for-testing-404');

    $response->assertStatus(404);
    $response->assertSee('Code Erreur 404', false);
    $response->assertSee('Page ou Document Introuvable', false);
    $response->assertSee('ArchiDoc DGB', false);
});

test('403 custom error page renders when unauthorized action occurs', function () {
    $user = User::factory()->create(['roles' => 'classique']);

    // Attempting to access users management without User:read permission triggers 403
    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertStatus(403);
    $response->assertSee('Code Erreur 403', false);
    $response->assertSee('Accès Refusé', false);
});

test('error view files exist for 403, 404, 419, 422, 429, 500, 503', function () {
    expect(view()->exists('errors.layout'))->toBeTrue();
    expect(view()->exists('errors.403'))->toBeTrue();
    expect(view()->exists('errors.404'))->toBeTrue();
    expect(view()->exists('errors.419'))->toBeTrue();
    expect(view()->exists('errors.422'))->toBeTrue();
    expect(view()->exists('errors.429'))->toBeTrue();
    expect(view()->exists('errors.500'))->toBeTrue();
    expect(view()->exists('errors.503'))->toBeTrue();
});
