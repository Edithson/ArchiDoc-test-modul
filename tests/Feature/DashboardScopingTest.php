<?php

use App\Models\Archive;
use App\Models\Department;
use App\Models\Personnel;
use App\Models\Role;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->classiqueRole = Role::firstOrCreate(
        ['name' => 'Classique'],
        ['permissions' => Role::defaultPermissionsFor('Classique'), 'is_primary' => true]
    );

    $this->privilegieRole = Role::firstOrCreate(
        ['name' => 'Privilégié'],
        ['permissions' => Role::defaultPermissionsFor('Privilégié'), 'is_primary' => true]
    );

    $this->superRole = Role::firstOrCreate(
        ['name' => 'Super Privilégié'],
        ['permissions' => Role::defaultPermissionsFor('Super Privilégié'), 'is_primary' => true]
    );
});

test('super user dashboard sees global system counts and all activities', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A']);
    $deptB = Department::factory()->create(['name' => 'Direction B']);

    $super = User::factory()->create(['role_id' => $this->superRole->id]);

    Archive::factory()->create(['department_id' => $deptA->id, 'description' => 'Archive Dept A']);
    Archive::factory()->create(['department_id' => $deptB->id, 'description' => 'Archive Dept B']);

    Personnel::factory()->create(['name' => 'Agent Test']);

    Activity::query()->delete();

    activity('archive')
        ->by($super)
        ->event('archive.consultation')
        ->log('Test global activity');

    $response = $this->actingAs($super)->get(route('archives.index'));
    $response->assertStatus(200);
    $response->assertSee('Archive Dept A');
    $response->assertSee('Archive Dept B');
    $response->assertSee('Agent Test');
    $response->assertSee('Test global activity');
});

test('non-super user dashboard is scoped to their department archives', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A']);
    $deptB = Department::factory()->create(['name' => 'Direction B']);

    $userA = User::factory()->create([
        'role_id' => $this->privilegieRole->id,
        'department_id' => $deptA->id,
    ]);

    Archive::factory()->create(['department_id' => $deptA->id, 'description' => 'Archive Visible Dept A']);
    Archive::factory()->create(['department_id' => $deptB->id, 'description' => 'Archive Hidden Dept B']);

    $response = $this->actingAs($userA)->get(route('archives.index'));
    $response->assertStatus(200);
    $response->assertSee('Archive Visible Dept A');
    $response->assertDontSee('Archive Hidden Dept B');
});

test('classique user dashboard only sees their own activities in live feed', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A']);

    $classiqueUser = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
    ]);

    $otherUser = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
    ]);

    Activity::query()->delete();

    activity('archive')
        ->by($classiqueUser)
        ->event('archive.consultation')
        ->log('Action par Classique');

    activity('archive')
        ->by($otherUser)
        ->event('archive.consultation')
        ->log('Action par Autre Agent');

    $response = $this->actingAs($classiqueUser)->get(route('archives.index'));
    $response->assertStatus(200);
    $response->assertSee('Action par Classique');
    $response->assertDontSee('Action par Autre Agent');
});

test('user without personnel read permission does not see personnel card on dashboard', function () {
    $deptA = Department::factory()->create(['name' => 'Direction A']);

    $userWithoutPersonnelRead = User::factory()->create([
        'role_id' => $this->classiqueRole->id,
        'department_id' => $deptA->id,
        'custom_permissions' => [
            'Personnel' => ['read' => false],
        ],
    ]);

    Personnel::factory()->create(['name' => 'Confidential Personnel Agent']);

    $response = $this->actingAs($userWithoutPersonnelRead)->get(route('archives.index'));
    $response->assertStatus(200);
    $response->assertDontSee('Confidential Personnel Agent');
    $response->assertDontSee('Récents Dossiers Agents');
});
