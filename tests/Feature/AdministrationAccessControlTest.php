<?php

use App\Models\Department;
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

test('administration access is independent of user department', function () {
    $dept = Department::factory()->create(['name' => 'Direction Test', 'parent_id' => null]);

    // User in a specific department with custom granted User:read permission
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $dept->id,
        'custom_permissions' => [
            'User' => [
                'read' => true,
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertStatus(200);
});

test('user without User:read permission is denied access to users index (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'User' => [
                'read' => false,
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertStatus(403);
});

test('user without User:create permission is denied access to create user form (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'User' => [
                'create' => false,
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('users.create'))
        ->assertStatus(403);
});

test('user without User:update permission is denied access to edit user form or toggle status (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'User' => [
                'update' => false,
            ],
        ],
    ]);

    $targetUser = User::factory()->create(['role_id' => $this->classiqueRole->id]);

    $this->actingAs($user)
        ->get(route('users.edit', $targetUser))
        ->assertStatus(403);

    $this->actingAs($user)
        ->post(route('users.toggle-status', $targetUser))
        ->assertStatus(403);
});

test('user without User:delete permission is denied user deletion (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'User' => [
                'delete' => false,
            ],
        ],
    ]);

    $targetUser = User::factory()->create(['role_id' => $this->classiqueRole->id]);

    $this->actingAs($user)
        ->delete(route('users.destroy', $targetUser))
        ->assertStatus(403);
});

test('user without Role:read permission is denied access to roles index (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Role' => [
                'read' => false,
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('roles.index'))
        ->assertStatus(403);
});

test('user without Role:create permission is denied access to create role form (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Role' => [
                'create' => false,
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('roles.create'))
        ->assertStatus(403);
});

test('user without Role:update permission is denied access to edit role form (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Role' => [
                'update' => false,
            ],
        ],
    ]);

    $role = Role::factory()->create(['name' => 'Custom Role']);

    $this->actingAs($user)
        ->get(route('roles.edit', $role))
        ->assertStatus(403);
});

test('user without Role:delete permission is denied role deletion (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Role' => [
                'delete' => false,
            ],
        ],
    ]);

    $role = Role::factory()->create(['name' => 'Custom Role Delete']);

    $this->actingAs($user)
        ->delete(route('roles.destroy', $role))
        ->assertStatus(403);
});

test('user without Setting:read permission is denied access to settings index (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Setting' => [
                'read' => false,
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('settings.index'))
        ->assertStatus(403);
});

test('user without Setting:update permission is denied settings update and reset (403)', function () {
    $user = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Setting' => [
                'update' => false,
            ],
        ],
    ]);

    $this->actingAs($user)
        ->post(route('settings.update'), ['app_name' => 'New Name'])
        ->assertStatus(403);

    $this->actingAs($user)
        ->post(route('settings.reset'))
        ->assertStatus(403);
});

test('activity log auth page requires User:read permission', function () {
    $userWithPermission = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'User' => ['read' => true],
        ],
    ]);

    $userWithoutPermission = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'User' => ['read' => false],
        ],
    ]);

    $this->actingAs($userWithPermission)
        ->get(route('activity-logs.auth'))
        ->assertStatus(200);

    $this->actingAs($userWithoutPermission)
        ->get(route('activity-logs.auth'))
        ->assertStatus(403);
});

test('activity log system page requires Setting:read permission', function () {
    $userWithPermission = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Setting' => ['read' => true],
        ],
    ]);

    $userWithoutPermission = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'custom_permissions' => [
            'Setting' => ['read' => false],
        ],
    ]);

    $this->actingAs($userWithPermission)
        ->get(route('activity-logs.system'))
        ->assertStatus(200);

    $this->actingAs($userWithoutPermission)
        ->get(route('activity-logs.system'))
        ->assertStatus(403);
});

test('non-super user can only view users within their own department in users index', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A']);
    $deptB = Department::factory()->create(['name' => 'Direction B']);

    $userA = User::factory()->create([
        'role_id' => $this->privilegieRole->id,
        'department_id' => $deptA->id,
        'name' => 'User in Dept A',
        'custom_permissions' => ['User' => ['read' => true]],
    ]);

    $userB = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptB->id,
        'name' => 'User in Dept B',
    ]);

    $response = $this->actingAs($userA)->get(route('users.index'));
    $response->assertStatus(200);
    $response->assertSee('User in Dept A');
    $response->assertDontSee('User in Dept B');
});

test('non-super user cannot edit or update user in another department (403)', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A']);
    $deptB = Department::factory()->create(['name' => 'Direction B']);

    $userA = User::factory()->create([
        'role_id' => $this->privilegieRole->id,
        'department_id' => $deptA->id,
        'custom_permissions' => ['User' => ['update' => true]],
    ]);

    $userB = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptB->id,
    ]);

    $this->actingAs($userA)->get(route('users.edit', $userB))->assertStatus(403);

    $this->actingAs($userA)->put(route('users.update', $userB), [
        'name' => 'Hacked Name',
        'matricule' => $userB->matricule,
        'email' => $userB->email,
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptB->id,
        'statut' => 1,
    ])->assertStatus(403);
});

test('non-super user cannot delete or toggle status of user in another department (403)', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A']);
    $deptB = Department::factory()->create(['name' => 'Direction B']);

    $userA = User::factory()->create([
        'role_id' => $this->privilegieRole->id,
        'department_id' => $deptA->id,
        'custom_permissions' => ['User' => ['update' => true, 'delete' => true]],
    ]);

    $userB = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptB->id,
    ]);

    $this->actingAs($userA)->post(route('users.toggle-status', $userB))->assertStatus(403);
    $this->actingAs($userA)->delete(route('users.destroy', $userB))->assertStatus(403);
});

test('non-super user cannot assign Super Privileged role during user creation', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A']);

    $userA = User::factory()->create([
        'role_id' => $this->privilegieRole->id,
        'department_id' => $deptA->id,
        'custom_permissions' => ['User' => ['create' => true]],
    ]);

    $response = $this->actingAs($userA)->post(route('users.store'), [
        'name' => 'New Super Attempt',
        'matricule' => 'SUP-ATTEMPT-01',
        'email' => 'attempt@minfi.cm',
        'role_id' => $this->superRole->id,
        'department_id' => $deptA->id,
        'statut' => 1,
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors(['role_id']);
});
