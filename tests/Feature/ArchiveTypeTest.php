<?php

use App\Models\ArchiveType;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create([
        'roles' => 'super privilégé',
    ]);
});

test('authenticated user can view archive types list page', function () {
    ArchiveType::factory()->create(['name' => 'ARRETE TEST']);

    $response = $this->actingAs($this->user)->get(route('archive-types.index'));

    $response->assertStatus(200);
    $response->assertSee('ARRETE TEST');
});

test('user can search archive types by name or description', function () {
    ArchiveType::factory()->create(['name' => 'DECISION SPECIALE']);
    ArchiveType::factory()->create(['name' => 'CIRCULAIRE INTERNE']);

    $response = $this->actingAs($this->user)->get(route('archive-types.index', ['search' => 'SPECIALE']));

    $response->assertStatus(200);
    $response->assertSee('DECISION SPECIALE');
    $response->assertDontSee('CIRCULAIRE INTERNE');
});

test('user can access create archive type page', function () {
    $response = $this->actingAs($this->user)->get(route('archive-types.create'));

    $response->assertStatus(200);
    $response->assertSee('Créer un Type d\'Archive', false);
});

test('user can store a new archive type', function () {
    $data = [
        'name' => 'CONVENTION FINANCIERE',
        'description' => 'Toutes conventions relatives au budget',
        'dua' => 15,
    ];

    $response = $this->actingAs($this->user)->post(route('archive-types.store'), $data);

    $response->assertRedirect(route('archive-types.index'));
    $this->assertDatabaseHas('archive_types', [
        'name' => 'CONVENTION FINANCIERE',
        'description' => 'Toutes conventions relatives au budget',
        'dua' => 15,
        'created_by' => $this->user->id,
    ]);
});

test('store fails if archive type name is missing or duplicate', function () {
    ArchiveType::factory()->create(['name' => 'ATTESTATION UNIQUE']);

    $response = $this->actingAs($this->user)->post(route('archive-types.store'), [
        'name' => 'ATTESTATION UNIQUE',
        'dua' => 10,
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('user can edit and update an archive type', function () {
    $archiveType = ArchiveType::factory()->create(['name' => 'NOTE ANCIENNE', 'dua' => 5]);

    $response = $this->actingAs($this->user)->get(route('archive-types.edit', $archiveType));
    $response->assertStatus(200);
    $response->assertSee('NOTE ANCIENNE');

    $updateResponse = $this->actingAs($this->user)->put(route('archive-types.update', $archiveType), [
        'name' => 'NOTE NOUVELLE',
        'description' => 'Description mise à jour',
        'dua' => 20,
    ]);

    $updateResponse->assertRedirect(route('archive-types.index'));
    $this->assertDatabaseHas('archive_types', [
        'id' => $archiveType->id,
        'name' => 'NOTE NOUVELLE',
        'dua' => 20,
    ]);
});

test('user can soft delete an archive type', function () {
    $archiveType = ArchiveType::factory()->create(['name' => 'TYPE A SUPPRIMER']);

    $response = $this->actingAs($this->user)->delete(route('archive-types.destroy', $archiveType));

    $response->assertRedirect(route('archive-types.index'));
    $this->assertSoftDeleted('archive_types', [
        'id' => $archiveType->id,
    ]);
});

test('archive create form fetches dynamic archive types from database', function () {
    ArchiveType::factory()->create(['name' => 'DYNAMIC TYPE DGB']);

    $response = $this->actingAs($this->user)->get(route('archives.create'));

    $response->assertStatus(200);
    $response->assertSee('DYNAMIC TYPE DGB');
});
