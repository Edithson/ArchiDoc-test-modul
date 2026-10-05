<?php

use App\Models\Role;
use App\Models\User;

test('roles table stores json permissions matrix for key models', function () {
    $role = Role::create([
        'name' => 'Test Role',
        'description' => 'Role for testing',
        'permissions' => Role::defaultPermissionsFor('Classic'),
    ]);

    expect($role->permissions)->toBeArray();
    expect($role->hasPermission('Archive', 'read'))->toBeTrue();
    expect($role->hasPermission('User', 'delete'))->toBeFalse();
    expect($role->hasPermission('Personnel', 'zip_download'))->toBeTrue();
});

test('user hasPermission combines role defaults with custom_permissions override', function () {
    $role = Role::firstOrCreate(
        ['name' => 'Classic'],
        ['permissions' => Role::defaultPermissionsFor('Classic')]
    );

    $user = User::factory()->create([
        'roles' => 'Classic',
        'custom_permissions' => null,
    ]);

    // Role default for Classic allows Archive read & download, but disallows User create
    expect($user->hasPermission('Archive', 'read'))->toBeTrue();
    expect($user->hasPermission('User', 'create'))->toBeFalse();

    // Give custom temporary override to user to allow User create
    $user->update([
        'custom_permissions' => [
            'User' => ['create' => true],
        ],
    ]);

    $user->refresh();
    expect($user->hasPermission('User', 'create'))->toBeTrue();
});

test('super user has all permissions implicitly', function () {
    $user = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    expect($user->hasPermission('User', 'delete'))->toBeTrue();
    expect($user->hasPermission('Setting', 'update'))->toBeTrue();
});
