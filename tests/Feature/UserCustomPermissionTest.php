<?php

use App\Models\Department;
use App\Models\Role;
use App\Models\User;

test('user model helper methods detect active custom permission overrides', function () {
    $role = Role::firstOrCreate(
        ['name' => 'Classic'],
        ['permissions' => Role::defaultPermissionsFor('Classic')]
    );

    $user = User::factory()->create([
        'role_id' => $role->id,
        'custom_permissions' => null,
    ]);

    expect($user->hasCustomPermissionOverrides())->toBeFalse();
    expect($user->customPermissionsCount())->toBe(0);

    $user->update([
        'custom_permissions' => [
            'User' => ['create' => true, 'delete' => true],
            'Archive' => ['download' => false],
        ],
    ]);

    $user->refresh();
    expect($user->hasCustomPermissionOverrides())->toBeTrue();
    expect($user->customPermissionsCount())->toBe(3);
});

test('updating user custom permissions stores only deltas compared to base role', function () {
    $super = User::factory()->create(['roles' => 'super privilégé']);
    $role = Role::firstOrCreate(
        ['name' => 'Classic'],
        ['permissions' => Role::defaultPermissionsFor('Classic')]
    );
    $dept = Department::factory()->create(['parent_id' => null]);
    $subDept = Department::factory()->create(['parent_id' => $dept->id]);

    $targetUser = User::factory()->create([
        'role_id' => $role->id,
        'department_id' => $dept->id,
        'sub_department_id' => $subDept->id,
        'custom_permissions' => null,
    ]);

    // Classic role defaults: Archive read=true, User create=false.
    // Submit custom_permissions:
    // - Archive read => 1 (same as role default true -> should NOT be stored)
    // - User create => 1 (force true, differs from role false -> SHOULD be stored as true)
    // - User delete => 0 (same as role default false -> should NOT be stored)
    // - Personnel zip_download => 0 (force false, differs from role true -> SHOULD be stored as false)
    $response = $this->actingAs($super)->put(route('users.update', $targetUser->id), [
        'name' => $targetUser->name,
        'matricule' => $targetUser->matricule,
        'email' => $targetUser->email,
        'role_id' => $role->id,
        'department_id' => $dept->id,
        'sub_department_id' => $subDept->id,
        'statut' => 1,
        'custom_permissions' => [
            'Archive' => ['read' => '1'],
            'User' => ['create' => '1', 'delete' => '0'],
            'Personnel' => ['zip_download' => '0'],
        ],
    ]);

    $response->assertRedirect(route('users.index'));

    $targetUser->refresh();
    expect($targetUser->custom_permissions)->toBeArray();

    // Only actual deltas should be stored
    expect($targetUser->custom_permissions)->toHaveKey('User');
    expect($targetUser->custom_permissions['User'])->toEqual(['create' => true]);

    expect($targetUser->custom_permissions)->toHaveKey('Personnel');
    expect($targetUser->custom_permissions['Personnel'])->toEqual(['zip_download' => false]);

    // Archive should not be in deltas because read=1 matches role default true
    expect($targetUser->custom_permissions)->not->toHaveKey('Archive');

    // Check evaluate permissions
    expect($targetUser->hasPermission('User', 'create'))->toBeTrue();
    expect($targetUser->hasPermission('Personnel', 'zip_download'))->toBeFalse();
    expect($targetUser->hasPermission('Archive', 'read'))->toBeTrue();
});

test('updating user with permissions matching base role sets custom_permissions to null', function () {
    $super = User::factory()->create(['roles' => 'super privilégé']);
    $role = Role::firstOrCreate(
        ['name' => 'Classic'],
        ['permissions' => Role::defaultPermissionsFor('Classic')]
    );
    $dept = Department::factory()->create(['parent_id' => null]);
    $subDept = Department::factory()->create(['parent_id' => $dept->id]);

    $targetUser = User::factory()->create([
        'role_id' => $role->id,
        'department_id' => $dept->id,
        'sub_department_id' => $subDept->id,
        'custom_permissions' => ['User' => ['create' => true]],
    ]);

    // Submit custom_permissions all set to 'inherit' or matching role
    $response = $this->actingAs($super)->put(route('users.update', $targetUser->id), [
        'name' => $targetUser->name,
        'matricule' => $targetUser->matricule,
        'email' => $targetUser->email,
        'role_id' => $role->id,
        'department_id' => $dept->id,
        'sub_department_id' => $subDept->id,
        'statut' => 1,
        'custom_permissions' => [
            'User' => ['create' => 'inherit'],
        ],
    ]);

    $response->assertRedirect(route('users.index'));

    $targetUser->refresh();
    expect($targetUser->custom_permissions)->toBeNull();
    expect($targetUser->hasCustomPermissionOverrides())->toBeFalse();
});

test('one click revocation route resets custom_permissions to null', function () {
    $super = User::factory()->create(['roles' => 'super privilégé']);
    $targetUser = User::factory()->create([
        'custom_permissions' => [
            'User' => ['create' => true, 'delete' => true],
            'Archive' => ['download' => false],
        ],
    ]);

    expect($targetUser->hasCustomPermissionOverrides())->toBeTrue();

    $response = $this->actingAs($super)->post(route('users.revoke-custom-permissions', $targetUser->id));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $targetUser->refresh();
    expect($targetUser->custom_permissions)->toBeNull();
    expect($targetUser->hasCustomPermissionOverrides())->toBeFalse();
});

test('dedicated permissions route renders permission matrix and saves custom deltas', function () {
    $super = User::factory()->create(['roles' => 'super privilégé']);
    $role = Role::firstOrCreate(
        ['name' => 'Classic'],
        ['permissions' => Role::defaultPermissionsFor('Classic')]
    );
    $targetUser = User::factory()->create([
        'role_id' => $role->id,
        'custom_permissions' => null,
    ]);

    // GET users.permissions
    $getResp = $this->actingAs($super)->get(route('users.permissions', $targetUser->id));
    $getResp->assertStatus(200);
    $getResp->assertSee('Droits d\'Accès Personnalisés', false);
    $getResp->assertSee('Matrice des Surcharges Individuelles');

    // PUT users.permissions.update
    $putResp = $this->actingAs($super)->put(route('users.permissions.update', $targetUser->id), [
        'custom_permissions' => [
            'User' => ['create' => '1'],
            'Personnel' => ['zip_download' => '0'],
        ],
    ]);

    $putResp->assertRedirect(route('users.permissions', $targetUser->id));
    $putResp->assertSessionHas('success');

    $targetUser->refresh();
    expect($targetUser->hasCustomPermissionOverrides())->toBeTrue();
    expect($targetUser->custom_permissions['User'])->toEqual(['create' => true]);
    expect($targetUser->custom_permissions['Personnel'])->toEqual(['zip_download' => false]);
});
