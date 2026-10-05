<?php

use App\Models\Department;
use App\Models\Personnel;
use App\Models\Piece;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    $this->classiqueRole = Role::firstOrCreate(
        ['name' => 'Classique'],
        ['permissions' => Role::defaultPermissionsFor('Classique')]
    );

    $this->privilegieRole = Role::firstOrCreate(
        ['name' => 'Privilégié'],
        ['permissions' => Role::defaultPermissionsFor('Privilégié')]
    );

    $this->superRole = Role::firstOrCreate(
        ['name' => 'Super Privilégié'],
        ['permissions' => Role::defaultPermissionsFor('Super Privilégié')]
    );
});

test('user can view personnel files regardless of department if read permission is granted', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A', 'parent_id' => null]);
    $deptB = Department::factory()->create(['name' => 'Direction B', 'parent_id' => null]);

    // User in Direction A
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
    ]);

    // Personnel in Direction B
    $personnelInB = Personnel::factory()->create([
        'department_id' => $deptB->id,
        'name' => 'Agent Direction B',
        'matricule' => 'MATB001',
    ]);

    // Access to index and show of personnel in Direction B must succeed (200)
    $response = $this->actingAs($user)->get(route('personnels.index'));
    $response->assertStatus(200);
    $response->assertSee('Agent Direction B');

    $this->actingAs($user)
        ->get(route('personnels.show', $personnelInB))
        ->assertStatus(200)
        ->assertSee('Agent Direction B');
});

test('user without personnel read permission is denied access (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Personnel' => [
                'read' => false,
            ],
        ],
    ]);

    $personnel = Personnel::factory()->create();

    $this->actingAs($user)->get(route('personnels.index'))->assertStatus(403);
    $this->actingAs($user)->get(route('personnels.show', $personnel))->assertStatus(403);
});

test('user without personnel create permission cannot access create form or store (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Personnel' => [
                'create' => false,
            ],
        ],
    ]);

    $this->actingAs($user)->get(route('personnels.create'))->assertStatus(403);

    $this->actingAs($user)->post(route('personnels.store'), [
        'name' => 'Test Agent',
        'matricule' => 'TST999',
    ])->assertStatus(403);
});

test('user without personnel update permission cannot access edit or update (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Personnel' => [
                'update' => false,
            ],
        ],
    ]);

    $personnel = Personnel::factory()->create();

    $this->actingAs($user)->get(route('personnels.edit', $personnel))->assertStatus(403);

    $this->actingAs($user)->put(route('personnels.update', $personnel), [
        'name' => 'Updated Name',
        'matricule' => $personnel->matricule,
    ])->assertStatus(403);
});

test('user without personnel delete permission cannot delete personnel file (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Personnel' => [
                'delete' => false,
            ],
        ],
    ]);

    $personnel = Personnel::factory()->create();

    $this->actingAs($user)->delete(route('personnels.destroy', $personnel))->assertStatus(403);
});

test('user without personnel zip_download permission cannot download zip (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Personnel' => [
                'zip_download' => false,
            ],
        ],
    ]);

    $personnel = Personnel::factory()->create();

    $this->actingAs($user)->get(route('personnels.download-zip', $personnel))->assertStatus(403);
});

test('user without piece permissions is denied piece management (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Piece' => [
                'read' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
        ],
    ]);

    $piece = Piece::factory()->create(['name' => 'Acte de naissance']);

    $this->actingAs($user)->get(route('pieces.index'))->assertStatus(403);
    $this->actingAs($user)->get(route('pieces.create'))->assertStatus(403);
    $this->actingAs($user)->post(route('pieces.store'), ['name' => 'Nouvelle Piece', 'obligatory' => 1])->assertStatus(403);
    $this->actingAs($user)->get(route('pieces.edit', $piece))->assertStatus(403);
    $this->actingAs($user)->put(route('pieces.update', $piece), ['name' => 'Piece Modifiée', 'obligatory' => 1])->assertStatus(403);
    $this->actingAs($user)->delete(route('pieces.destroy', $piece))->assertStatus(403);
});
