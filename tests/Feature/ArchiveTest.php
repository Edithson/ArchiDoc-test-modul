<?php

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
        'emplacementsPhysiques',
        'emplacementsVirtuels',
        'groupesAcces',
    ]);
});

test('storing an archive saves file and creates database record', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('ARRETE_01022026.pdf', 500, 'application/pdf');

    $data = [
        'file' => $file,
        'format' => 'Document PDF',
        'typearchive' => 'ARRETE',
        'description' => 'ARRETE 01022026',
        'date_doc' => '2026-02-01',
        'emplacement' => 'FOUDA',
        'emplacement2' => 'Serveur',
        'rayon' => 'B1',
        'travee' => 'T2',
        'cote' => 'C-2026-001',
        'departement' => 'CAB DGB',
    ];

    $response = $this->actingAs($user)->postJson('/archives', $data);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('archives', [
        'typearchive' => 'ARRETE',
        'description' => 'ARRETE 01022026',
        'date_doc' => '2026-02-01',
        'emplacement' => 'FOUDA',
        'emplacement2' => 'Serveur',
        'departement' => 'CAB DGB',
        'user_id' => $user->id,
    ]);

    Storage::disk('public')->assertExists($response->json('archive.filepath'));
});
