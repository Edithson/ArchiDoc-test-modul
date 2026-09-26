<?php

use App\Models\User;

test('super privileged user can view user list', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    User::factory(5)->create();

    $response = $this->actingAs($superUser)->get('/users');

    $response->assertStatus(200);
    $response->assertViewIs('admin.pages.users.index');
    $response->assertViewHasAll(['users', 'departments', 'roleOptions']);
});

test('user list can be filtered by search, role, department, and status', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $activeUser = User::factory()->create([
        'name' => 'Filtre Cible Active',
        'email' => 'cible.active@archidoc.cm',
        'matricule' => 'MAT-CIBLE-01',
        'roles' => 'privilégié',
        'departement' => 'DI',
        'statut' => true,
    ]);

    $suspendedUser = User::factory()->create([
        'name' => 'Filtre Cible Suspendue',
        'email' => 'cible.suspendue@archidoc.cm',
        'matricule' => 'MAT-CIBLE-02',
        'roles' => 'classique',
        'departement' => 'DCOB',
        'statut' => false,
    ]);

    // Test filter by search
    $responseSearch = $this->actingAs($superUser)->get('/users?search=MAT-CIBLE-01');
    $responseSearch->assertSee('Filtre Cible Active');
    $responseSearch->assertDontSee('Filtre Cible Suspendue');

    // Test filter by role
    $responseRole = $this->actingAs($superUser)->get('/users?roles=privilégié');
    $responseRole->assertSee('Filtre Cible Active');
    $responseRole->assertDontSee('Filtre Cible Suspendue');

    // Test filter by status (suspended)
    $responseStatus = $this->actingAs($superUser)->get('/users?statut=0');
    $responseStatus->assertSee('Filtre Cible Suspendue');
    $responseStatus->assertDontSee('Filtre Cible Active');
});

test('non-super privileged user receives 403 forbidden on user list', function () {
    $classiqueUser = User::factory()->create([
        'roles' => 'classique',
    ]);

    $response = $this->actingAs($classiqueUser)->get('/users');

    $response->assertStatus(403);
});

test('super privileged user can create a user account', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $userData = [
        'name' => 'Jean Dupont',
        'matricule' => 'MAT-9999',
        'email' => 'j.dupont@archidoc.cm',
        'phone' => '+237 655 44 33 22',
        'roles' => 'privilégié',
        'departement' => 'DI',
        'statut' => '1',
        'password' => 'password123',
    ];

    $response = $this->actingAs($superUser)->post('/users', $userData);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'email' => 'j.dupont@archidoc.cm',
        'matricule' => 'MAT-9999',
        'departement' => 'DI',
        'roles' => 'privilégié',
        'statut' => true,
    ]);
});

test('super privileged user can update a user account', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $targetUser = User::factory()->create([
        'name' => 'Ancien Nom',
        'roles' => 'classique',
    ]);

    $response = $this->actingAs($superUser)->put("/users/{$targetUser->id}", [
        'name' => 'Nom Mis a Jour',
        'matricule' => $targetUser->matricule,
        'email' => $targetUser->email,
        'phone' => $targetUser->phone,
        'roles' => 'privilégié',
        'departement' => 'DCOB',
        'statut' => '1',
    ]);

    $response->assertRedirect(route('users.index'));
    expect($targetUser->fresh()->name)->toBe('Nom Mis a Jour');
    expect($targetUser->fresh()->roles)->toBe('privilégié');
});

test('super privileged user can toggle user status to suspend and reactivate', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $targetUser = User::factory()->create([
        'statut' => true,
    ]);

    // Suspend
    $response = $this->actingAs($superUser)->post("/users/{$targetUser->id}/toggle-status");
    $response->assertRedirect();
    expect($targetUser->fresh()->statut)->toBeFalse();

    // Reactivate
    $response2 = $this->actingAs($superUser)->post("/users/{$targetUser->id}/toggle-status");
    $response2->assertRedirect();
    expect($targetUser->fresh()->statut)->toBeTrue();
});

test('user cannot suspend or delete their own connected account', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
        'statut' => true,
    ]);

    $responseToggle = $this->actingAs($superUser)->post("/users/{$superUser->id}/toggle-status");
    $responseToggle->assertSessionHas('error');
    expect($superUser->fresh()->statut)->toBeTrue();

    $responseDelete = $this->actingAs($superUser)->delete("/users/{$superUser->id}");
    $responseDelete->assertSessionHas('error');
    expect(User::find($superUser->id))->not->toBeNull();
});

test('suspended user is immediately blocked from logging in', function () {
    $superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $targetUser = User::factory()->create([
        'email' => 'to_suspend@archidoc.cm',
        'password' => bcrypt('password123'),
        'statut' => true,
    ]);

    // Super user suspends target user
    $this->actingAs($superUser)->post("/users/{$targetUser->id}/toggle-status");
    expect($targetUser->fresh()->statut)->toBeFalse();

    // Logout super user first
    auth()->logout();

    // Target user tries to log in
    $response = $this->post('/login', [
        'email' => 'to_suspend@archidoc.cm',
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});
