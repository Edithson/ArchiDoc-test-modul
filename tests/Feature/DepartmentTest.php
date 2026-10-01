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
    Department::factory()->create(['name' => 'DI', 'description' => 'Division Informatique']);
    Department::factory()->create(['name' => 'DDPP', 'description' => 'Direction de la Dépense']);

    $response = $this->actingAs($this->user)->get(route('departments.index', ['search' => 'Informatique']));

    $response->assertStatus(200);
    $response->assertSee('DI');
    $response->assertDontSee('DDPP');
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

test('department seeder seeds all default 13 departments', function () {
    $this->seed(DepartmentSeeder::class);

    $defaultDepts = ['CAB DGB', 'DCOB', 'DDPP', 'DI', 'DPB', 'DPC', 'DREF', 'PUBLIC', 'S-DAG', 'S-DCF', 'SGCCC', 'SGDB', 'SO'];
    expect(Department::whereIn('name', $defaultDepts)->count())->toBe(13);
    $this->assertDatabaseHas('departments', ['name' => 'CAB DGB']);
    $this->assertDatabaseHas('departments', ['name' => 'SGCCC']);
});
