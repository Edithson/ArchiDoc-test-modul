<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Database\Seeders\DepartmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('user can view department list page', function () {
    Department::factory()->create(['name' => 'DI', 'description' => 'Division Informatique']);

    $response = $this->actingAs($this->user)->get(route('departments.index'));

    $response->assertStatus(200);
    $response->assertSee('Division Informatique');
    $response->assertSee('DI');
});

test('user can search departments by name or description', function () {
    $main = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    Department::factory()->create(['name' => 'DI', 'description' => 'Division Informatique', 'parent_id' => $main->id]);
    Department::factory()->create(['name' => 'DDPP_TEST', 'description' => 'Direction de la Dépense', 'parent_id' => $main->id]);

    $response = $this->actingAs($this->user)->get(route('departments.index', ['search' => 'Informatique']));

    $response->assertStatus(200);
    $response->assertSee('DI');
    $response->assertDontSee('DDPP_TEST');
});

test('user can view department creation page', function () {
    $response = $this->actingAs($this->user)->get(route('departments.create'));

    $response->assertStatus(200);
    $response->assertSee('Créer un Département / Service');
});

test('user can store new department', function () {
    $response = $this->actingAs($this->user)->post(route('departments.store'), [
        'name' => 'DREF',
        'description' => 'Division de la Réforme Budgétaire',
    ]);

    $response->assertRedirect(route('departments.index'));
    $this->assertDatabaseHas('departments', [
        'name' => 'DREF',
        'description' => 'Division de la Réforme Budgétaire',
    ]);
});

test('department creation fails if name is missing or duplicate', function () {
    Department::factory()->create(['name' => 'PUBLIC']);

    $response = $this->actingAs($this->user)->post(route('departments.store'), [
        'name' => 'PUBLIC',
        'description' => 'Duplicate test',
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('user can view department edit page', function () {
    $department = Department::factory()->create(['name' => 'SO']);

    $response = $this->actingAs($this->user)->get(route('departments.edit', $department));

    $response->assertStatus(200);
    $response->assertSee('Modifier le Département');
});

test('user can update existing department', function () {
    $department = Department::factory()->create(['name' => 'SGDB', 'description' => 'Ancien']);

    $response = $this->actingAs($this->user)->put(route('departments.update', $department), [
        'name' => 'SGDB',
        'description' => 'Service de Gestion des Documents Budgétaires',
    ]);

    $response->assertRedirect(route('departments.index'));
    $this->assertDatabaseHas('departments', [
        'id' => $department->id,
        'description' => 'Service de Gestion des Documents Budgétaires',
    ]);
});

test('user can soft delete department', function () {
    $department = Department::factory()->create(['name' => 'S-DAG']);

    $response = $this->actingAs($this->user)->delete(route('departments.destroy', $department));

    $response->assertRedirect(route('departments.index'));
    $this->assertSoftDeleted('departments', [
        'id' => $department->id,
    ]);
});

test('department seeder seeds main MINFI directions and sub-departments', function () {
    $this->seed(DepartmentSeeder::class);

    $defaultDepts = ['CAB DGB', 'DCOB', 'DDPP', 'DI', 'DPB', 'DPC', 'DREF', 'PUBLIC', 'S-DAG', 'S-DCF', 'SGCCC', 'SGDB', 'SO'];
    expect(Department::whereIn('name', $defaultDepts)->count())->toBe(13);
    $this->assertDatabaseHas('departments', ['name' => 'DGB', 'parent_id' => null]);
    $this->assertDatabaseHas('departments', ['name' => 'DI', 'parent_id' => Department::where('name', 'DGB')->first()->id]);
});

test('user can create sub-department attached to main direction', function () {
    $mainDept = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);

    $response = $this->actingAs($this->user)->post(route('departments.store'), [
        'name' => 'DI-TEST',
        'description' => 'Division Informatique Test',
        'parent_id' => $mainDept->id,
    ]);

    $response->assertRedirect(route('departments.index'));
    $this->assertDatabaseHas('departments', [
        'name' => 'DI-TEST',
        'parent_id' => $mainDept->id,
    ]);
});

test('updating department to be its own parent fails validation', function () {
    $dept = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);

    $response = $this->actingAs($this->user)->put(route('departments.update', $dept), [
        'name' => 'DGB',
        'parent_id' => $dept->id,
    ]);

    $response->assertSessionHasErrors(['parent_id']);
});

test('user can filter departments by type and parent_id', function () {
    $mainDept = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $subDept = Department::factory()->create(['name' => 'DI', 'parent_id' => $mainDept->id]);

    $responseMain = $this->actingAs($this->user)->get(route('departments.index', ['type' => 'main']));
    $responseMain->assertSee('DGB');
    $responseMain->assertDontSee('DI');

    $responseSub = $this->actingAs($this->user)->get(route('departments.index', ['type' => 'sub']));
    $responseSub->assertSee('DI');

    $responseParent = $this->actingAs($this->user)->get(route('departments.index', ['parent_id' => $mainDept->id]));
    $responseParent->assertSee('DI');
});
