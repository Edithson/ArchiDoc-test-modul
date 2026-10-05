<?php

use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('index page returns success and displays admin dashboard for authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('admin.index');
});

test('create page returns success and passes dynamic dataset', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/archives/create');

    $response->assertStatus(200);
    $response->assertViewIs('admin.pages.archives.create');
    $response->assertViewHasAll([
        'formats',
        'archiveTypes',
        'mainDepartments',
        'departments',
        'emplacementsPhysiques',
        'emplacementsVirtuels',
    ]);
});

test('storing an archive saves file and creates database record with main and sub department', function () {
    Storage::fake('public');

    $mainDept = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $subDept = Department::factory()->create(['name' => 'DI', 'parent_id' => $mainDept->id]);
    $user = User::factory()->create([
        'department_id' => $mainDept->id,
        'sub_department_id' => $subDept->id,
    ]);
    $type = ArchiveType::factory()->create();
    $file = UploadedFile::fake()->create('ARRETE_01022026.pdf', 500, 'application/pdf');

    $data = [
        'file' => $file,
        'format' => 'Document PDF',
        'archive_type_id' => $type->id,
        'description' => 'ARRETE 01022026',
        'date_doc' => '2026-02-01',
        'emplacement' => 'FOUDA',
        'emplacement2' => 'Serveur',
        'rayon' => 'B1',
        'travee' => 'T2',
        'cote' => 'C-2026-001',
        'department_id' => $mainDept->id,
        'sub_department_id' => $subDept->id,
    ];

    $response = $this->actingAs($user)->postJson('/archives', $data);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('archives', [
        'archive_type_id' => $type->id,
        'description' => 'ARRETE 01022026',
        'date_doc' => '2026-02-01',
        'emplacement' => 'FOUDA',
        'emplacement2' => 'Serveur',
        'department_id' => $mainDept->id,
        'sub_department_id' => $subDept->id,
        'user_id' => $user->id,
    ]);

    Storage::disk('public')->assertExists($response->json('archive.filepath'));
});

test('storing archive fails if sub department does not belong to selected main department', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $mainDept1 = Department::factory()->create(['name' => 'DGB', 'parent_id' => null]);
    $mainDept2 = Department::factory()->create(['name' => 'DGI', 'parent_id' => null]);
    $subDept2 = Department::factory()->create(['name' => 'DGE', 'parent_id' => $mainDept2->id]);
    $type = ArchiveType::factory()->create();
    $file = UploadedFile::fake()->create('DOC.pdf', 100, 'application/pdf');

    $data = [
        'file' => $file,
        'format' => 'Document PDF',
        'archive_type_id' => $type->id,
        'description' => 'DOC TEST INCOMPATIBLE',
        'date_doc' => '2026-02-01',
        'emplacement' => 'FOUDA',
        'emplacement2' => 'Serveur',
        'department_id' => $mainDept1->id,
        'sub_department_id' => $subDept2->id,
    ];

    $response = $this->actingAs($user)->postJson('/archives', $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['sub_department_id']);
});
