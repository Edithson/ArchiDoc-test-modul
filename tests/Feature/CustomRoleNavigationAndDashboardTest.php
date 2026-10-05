<?php

use App\Models\Department;
use App\Models\Role;
use App\Models\User;

test('user with custom role lacking Archive read can access dashboard home without HTTP 403', function () {
    $role = Role::create([
        'name' => 'Gestionnaire Paramètres & Utilisateurs',
        'permissions' => [
            'User' => ['read' => true, 'create' => true, 'update' => true],
            'Setting' => ['read' => true, 'update' => true],
            'Role' => ['read' => true],
            'Archive' => ['read' => false, 'create' => false],
        ],
    ]);

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);

    $response = $this->actingAs($user)->get(route('archives.index'));

    $response->assertStatus(200);
    $response->assertSee('Tableau de bord', false);
    $response->assertDontSee('Consulter les archives');
});

test('user with custom role can be created without a sub-department', function () {
    $super = User::factory()->create(['roles' => 'super privilégé']);
    $dept = Department::factory()->create(['parent_id' => null]);

    $customRole = Role::create([
        'name' => 'Auditeur Général',
        'permissions' => Role::defaultPermissionsFor('Classic'),
    ]);

    $response = $this->actingAs($super)->post(route('users.store'), [
        'name' => 'Agent Auditeur',
        'matricule' => 'AUD-9999',
        'email' => 'auditeur@minfi.cm',
        'role_id' => $customRole->id,
        'department_id' => $dept->id,
        'sub_department_id' => null,
        'statut' => 1,
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'auditeur@minfi.cm',
        'role_id' => $customRole->id,
        'department_id' => $dept->id,
        'sub_department_id' => null,
    ]);
});
