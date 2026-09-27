<?php

use App\Models\Personnel;
use App\Models\PersonnelFiles;
use App\Models\Piece;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create([
        'roles' => 'super privilégé',
    ]);
});

test('authenticated user can view integration pieces list page', function () {
    Piece::factory()->create(['name' => 'Diplôme de Licence', 'obligatory' => true]);

    $response = $this->actingAs($this->user)->get(route('pieces.index'));

    $response->assertStatus(200);
    $response->assertSee('Diplôme de Licence');
    $response->assertSee('Obligatoire (Block 1)');
});

test('user can access create and edit pages for pieces', function () {
    $piece = Piece::factory()->create(['name' => 'Attestation temporaire']);

    $createResponse = $this->actingAs($this->user)->get(route('pieces.create'));
    $createResponse->assertStatus(200);

    $editResponse = $this->actingAs($this->user)->get(route('pieces.edit', $piece));
    $editResponse->assertStatus(200);
    $editResponse->assertSee('Attestation temporaire');
});

test('user can search pieces by name', function () {
    Piece::factory()->create(['name' => 'Extrait de Casier Judiciaire']);
    Piece::factory()->create(['name' => 'Certificat de Nationalité']);

    $response = $this->actingAs($this->user)->get(route('pieces.index', ['search' => 'Casier']));

    $response->assertStatus(200);
    $response->assertSee('Extrait de Casier Judiciaire');
    $response->assertDontSee('Certificat de Nationalité');
});

test('user can filter pieces by obligation status', function () {
    Piece::factory()->create(['name' => 'Acte de Naissance', 'obligatory' => true]);
    Piece::factory()->create(['name' => 'Permis de Conduire', 'obligatory' => false]);

    $responseObligatory = $this->actingAs($this->user)->get(route('pieces.index', ['obligatory' => '1']));
    $responseObligatory->assertStatus(200);
    $responseObligatory->assertSee('Acte de Naissance');
    $responseObligatory->assertDontSee('Permis de Conduire');

    $responseOptional = $this->actingAs($this->user)->get(route('pieces.index', ['obligatory' => '0']));
    $responseOptional->assertStatus(200);
    $responseOptional->assertSee('Permis de Conduire');
    $responseOptional->assertDontSee('Acte de Naissance');
});

test('user can store a new integration piece', function () {
    $response = $this->actingAs($this->user)->post(route('pieces.store'), [
        'name' => 'Attestation de Non Redevabilité',
        'description' => 'Délivrée par le secteur des impôts',
        'obligatory' => true,
    ]);

    $response->assertRedirect(route('pieces.index'));
    $this->assertDatabaseHas('pieces', [
        'name' => 'Attestation de Non Redevabilité',
        'obligatory' => true,
    ]);
});

test('store fails if piece name is missing', function () {
    $response = $this->actingAs($this->user)->post(route('pieces.store'), [
        'name' => '',
        'obligatory' => true,
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('user can update an integration piece', function () {
    $piece = Piece::factory()->create(['name' => 'Ancien Libellé', 'obligatory' => false]);

    $response = $this->actingAs($this->user)->put(route('pieces.update', $piece), [
        'name' => 'Nouveau Libellé',
        'description' => 'Mise à jour des exigences',
        'obligatory' => true,
    ]);

    $response->assertRedirect(route('pieces.index'));
    $this->assertDatabaseHas('pieces', [
        'id' => $piece->id,
        'name' => 'Nouveau Libellé',
        'obligatory' => true,
    ]);
});

test('user can delete an integration piece when no personnel files depend on it', function () {
    $piece = Piece::factory()->create(['name' => 'Pièce obsolète']);

    $response = $this->actingAs($this->user)->delete(route('pieces.destroy', $piece));

    $response->assertRedirect(route('pieces.index'));
    $response->assertSessionHas('success');
    $this->assertSoftDeleted('pieces', [
        'id' => $piece->id,
    ]);
});

test('cannot delete piece if pivot personnel_files records exist', function () {
    $piece = Piece::factory()->create(['name' => 'Pièce utilisée']);
    $personnel = Personnel::factory()->create();

    PersonnelFiles::create([
        'pieces_id' => $piece->id,
        'personnels_id' => $personnel->id,
        'file_paths' => ['/storage/personnel/test.pdf'],
    ]);

    $response = $this->actingAs($this->user)->delete(route('pieces.destroy', $piece));

    $response->assertRedirect(route('pieces.index'));
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('pieces', [
        'id' => $piece->id,
        'deleted_at' => null,
    ]);
});
