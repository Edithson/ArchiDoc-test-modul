<?php

use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'Super privilégé'], [
        'description' => 'Accès absolu',
        'permissions' => Role::defaultPermissionsFor('Super privilégé'),
    ]);
    Role::firstOrCreate(['name' => 'Privilégié'], [
        'description' => 'Accès étendu',
        'permissions' => Role::defaultPermissionsFor('Privilégié'),
    ]);
    Role::firstOrCreate(['name' => 'Classic'], [
        'description' => 'Accès standard',
        'permissions' => Role::defaultPermissionsFor('Classic'),
    ]);

    $this->superUser = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $this->classicUser = User::factory()->create([
        'roles' => 'classique',
    ]);
});

test('super user can view roles management index page', function () {
    $response = $this->actingAs($this->superUser)->get(route('roles.index'));

    $response->assertStatus(200);
    $response->assertSee('Configuration des Habilitations par Rôle');
    $response->assertSee('Habilitations par Modèle');
});

test('classic user is forbidden from accessing roles management page', function () {
    $response = $this->actingAs($this->classicUser)->get(route('roles.index'));

    $response->assertStatus(403);
});

test('super user can view role creation page', function () {
    $response = $this->actingAs($this->superUser)->get(route('roles.create'));

    $response->assertStatus(200);
    $response->assertSee('Créer un Nouveau Rôle');
});

test('super user can store a new role with custom permissions matrix', function () {
    $response = $this->actingAs($this->superUser)->post(route('roles.store'), [
        'name' => 'Auditeur Externe',
        'description' => 'Rôle pour la vérification annuelle',
        'permissions' => [
            'Archive' => ['read' => '1', 'download' => '1'],
            'Personnel' => ['read' => '1'],
        ],
    ]);

    $role = Role::where('name', 'Auditeur Externe')->first();
    expect($role)->not->toBeNull();
    expect($role->hasPermission('Archive', 'read'))->toBeTrue();
    expect($role->hasPermission('Archive', 'delete'))->toBeFalse();

    $response->assertRedirect(route('roles.index'));
});

test('super user can view role edit page and update permissions', function () {
    $role = Role::create([
        'name' => 'Inspecteur',
        'description' => 'Contrôle interne',
        'permissions' => Role::defaultPermissionsFor('Classic'),
    ]);

    $response = $this->actingAs($this->superUser)->get(route('roles.edit', $role));
    $response->assertStatus(200);
    $response->assertSee('Inspecteur');

    $updateResponse = $this->actingAs($this->superUser)->put(route('roles.update', $role), [
        'name' => 'Inspecteur Général',
        'description' => 'Inspection générale',
        'permissions' => [
            'Archive' => ['read' => '1', 'download' => '1', 'delete' => '1'],
            'User' => ['read' => '1'],
        ],
    ]);

    $updateResponse->assertRedirect(route('roles.index'));

    $role->refresh();
    expect($role->name)->toBe('Inspecteur Général');
    expect($role->hasPermission('Archive', 'delete'))->toBeTrue();
});

test('all three primary system roles cannot be deleted', function (string $roleName) {
    $systemRole = Role::firstOrCreate(
        ['name' => $roleName],
        ['permissions' => Role::defaultPermissionsFor($roleName)]
    );

    $response = $this->actingAs($this->superUser)->delete(route('roles.destroy', $systemRole));

    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Role::where('id', $systemRole->id)->exists())->toBeTrue();
})->with(['Super privilégé', 'Privilégié', 'Classic']);

test('custom role with assigned users cannot be deleted', function () {
    $customRole = Role::create([
        'name' => 'Stagiaire',
        'description' => 'Rôle temporaire',
        'permissions' => Role::defaultPermissionsFor('Classic'),
    ]);

    User::factory()->create([
        'roles' => 'Stagiaire',
    ]);

    $response = $this->actingAs($this->superUser)->delete(route('roles.destroy', $customRole));

    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Role::where('id', $customRole->id)->exists())->toBeTrue();
});

test('unassigned custom role can be deleted', function () {
    $customRole = Role::create([
        'name' => 'Consultant Externe',
        'description' => 'Role temporaire sans utilisateur',
        'permissions' => Role::defaultPermissionsFor('Classic'),
    ]);

    $response = $this->actingAs($this->superUser)->delete(route('roles.destroy', $customRole));

    $response->assertRedirect(route('roles.index'));
    $response->assertSessionHas('success');
    expect(Role::where('id', $customRole->id)->exists())->toBeFalse();
});
