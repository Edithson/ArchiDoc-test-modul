<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('super privileged users are explicitly evaluated against role permissions and custom overrides', function () {
    $superRole = Role::firstOrCreate(
        ['name' => 'Super privilégé'],
        ['permissions' => Role::defaultPermissionsFor('Super privilégé')]
    );

    $superUser = User::factory()->create([
        'role_id' => $superRole->id,
        'custom_permissions' => null,
    ]);

    // 1. By default, Super Privileged role allows all permissions
    expect($superUser->hasPermission('User', 'delete'))->toBeTrue();
    expect($superUser->hasPermission('Archive', 'download'))->toBeTrue();

    // 2. Setting a custom override to false on a Super Privileged user MUST deny that action
    $superUser->update([
        'custom_permissions' => [
            'User' => ['delete' => false],
        ],
    ]);

    $superUser->refresh();
    expect($superUser->hasPermission('User', 'delete'))->toBeFalse();
    expect($superUser->hasPermission('Archive', 'download'))->toBeTrue();

    // 3. Modifying the Super Privileged role permissions in DB updates the permission check for Super Privileged users
    $updatedPermissions = $superRole->permissions;
    $updatedPermissions['Archive']['download'] = false;

    $superRole->update(['permissions' => $updatedPermissions]);

    $superUser->refresh();
    expect($superUser->hasPermission('Archive', 'download'))->toBeFalse();
});

test('permission checks use in-memory memoization and auto-flush on model changes', function () {
    $role = Role::firstOrCreate(
        ['name' => 'Classic'],
        ['permissions' => Role::defaultPermissionsFor('Classic')]
    );

    $user = User::factory()->create([
        'role_id' => $role->id,
        'custom_permissions' => null,
    ]);

    // Initial check caches result in memory
    expect($user->hasPermission('Archive', 'read'))->toBeTrue();
    expect($user->hasPermission('User', 'create'))->toBeFalse();

    // Modify custom_permissions on user instance and save -> triggers model event cache flush
    $user->update([
        'custom_permissions' => [
            'User' => ['create' => true],
        ],
    ]);

    // Fresh user instance or refreshed instance evaluates updated decision
    $user->refresh();
    expect($user->hasPermission('User', 'create'))->toBeTrue();
});

test('laravel gate and user can directives integrate seamlessly with permission matrix', function () {
    $role = Role::firstOrCreate(
        ['name' => 'Classic'],
        ['permissions' => Role::defaultPermissionsFor('Classic')]
    );

    $user = User::factory()->create([
        'role_id' => $role->id,
        'custom_permissions' => null,
    ]);

    expect(Gate::forUser($user)->allows('archive.read'))->toBeTrue();
    expect($user->can('archive.read'))->toBeTrue();
    expect($user->can('user.create'))->toBeFalse();

    $user->update([
        'custom_permissions' => ['User' => ['create' => true]],
    ]);

    $user->refresh();
    expect($user->can('user.create'))->toBeTrue();
});
