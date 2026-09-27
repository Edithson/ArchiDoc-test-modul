<?php

use App\Models\Personnel;
use App\Models\PersonnelFiles;
use App\Models\Piece;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    Storage::fake('public');
});

test('authenticated user can view personnels list page and stats', function () {
    Personnel::factory()->create(['name' => 'NGONO Marie', 'matricule' => 'MAT-9901']);

    $response = $this->actingAs($this->user)->get(route('personnels.index'));

    $response->assertStatus(200);
    $response->assertSee('NGONO Marie');
    $response->assertSee('MAT-9901');
});

test('user can search personnel by matricule or name', function () {
    Personnel::factory()->create(['name' => 'FOUDA Jean', 'matricule' => 'MAT-1111']);
    Personnel::factory()->create(['name' => 'MBIDA Pierre', 'matricule' => 'MAT-2222']);

    $response = $this->actingAs($this->user)->get(route('personnels.index', ['search' => 'FOUDA']));

    $response->assertStatus(200);
    $response->assertSee('FOUDA Jean');
    $response->assertDontSee('MBIDA Pierre');
});

test('user can access create personnel page', function () {
    Piece::factory()->create(['name' => 'Acte de recrutement', 'obligatory' => true]);

    $response = $this->actingAs($this->user)->get(route('personnels.create'));

    $response->assertStatus(200);
    $response->assertSee('Acte de recrutement');
    $response->assertSee('Block 1 : Pièces Obligatoires');
});

test('user can store new personnel with uploaded integration files', function () {
    $pieceObligatory = Piece::factory()->create(['name' => 'Acte de Recrutement', 'obligatory' => true]);
    $pieceOptional = Piece::factory()->create(['name' => 'Attestation de mariage', 'obligatory' => false]);

    $fakeFile = UploadedFile::fake()->create('recrutement.pdf', 100, 'application/pdf');

    $response = $this->actingAs($this->user)->post(route('personnels.store'), [
        'name' => 'TCHATCHOUA Alain',
        'matricule' => 'MAT-7788',
        'email' => 'alain@minfi.cm',
        'phone' => '+237699001122',
        'address' => 'Yaoundé',
        'files' => [
            $pieceObligatory->id => $fakeFile,
        ],
    ]);

    $personnel = Personnel::where('matricule', 'MAT-7788')->first();
    expect($personnel)->not->toBeNull();

    $response->assertRedirect(route('personnels.show', $personnel));

    $this->assertDatabaseHas('personnels', [
        'name' => 'TCHATCHOUA Alain',
        'matricule' => 'MAT-7788',
    ]);

    $this->assertDatabaseHas('personnel_files', [
        'personnels_id' => $personnel->id,
        'pieces_id' => $pieceObligatory->id,
    ]);
});

test('personnel completion accessors calculate rate accurately', function () {
    $piece1 = Piece::factory()->create(['name' => 'Piece 1', 'obligatory' => true]);
    $piece2 = Piece::factory()->create(['name' => 'Piece 2', 'obligatory' => true]);

    $personnel = Personnel::factory()->create(['name' => 'TEST Agent']);

    expect($personnel->taux_achevement)->toEqual(0);
    expect($personnel->missing_obligatory_pieces_count)->toBe(2);
    expect($personnel->is_complete)->toBeFalse();
});

test('user can view personnel dossier details page', function () {
    $personnel = Personnel::factory()->create(['name' => 'EBOA Francois']);

    $response = $this->actingAs($this->user)->get(route('personnels.show', $personnel));

    $response->assertStatus(200);
    $response->assertSee('EBOA Francois');
});

test('user can update personnel details and append files', function () {
    $personnel = Personnel::factory()->create(['name' => 'OLD NAME', 'matricule' => 'MAT-5000']);
    $piece = Piece::factory()->create(['name' => 'CV Certifié', 'obligatory' => false]);
    $fakeFile = UploadedFile::fake()->create('cv.pdf', 50, 'application/pdf');

    $response = $this->actingAs($this->user)->put(route('personnels.update', $personnel), [
        'name' => 'NEW NAME',
        'matricule' => 'MAT-5000',
        'email' => 'newname@minfi.cm',
        'files' => [
            $piece->id => $fakeFile,
        ],
    ]);

    $response->assertRedirect(route('personnels.show', $personnel));
    $this->assertDatabaseHas('personnels', [
        'id' => $personnel->id,
        'name' => 'NEW NAME',
    ]);
});

test('user receives error when trying to download zip of personnel with no files', function () {
    $personnel = Personnel::factory()->create(['name' => 'Agent Sans Fichier']);

    $response = $this->actingAs($this->user)->get(route('personnels.download-zip', $personnel));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('user can download zip archive of personnel dossier when files exist', function () {
    $personnel = Personnel::factory()->create(['name' => 'Agent Avec Fichier', 'matricule' => 'MAT-ZIP']);
    $piece = Piece::factory()->create(['name' => 'Acte Recrutement']);

    Storage::disk('public')->put('personnel_files/MAT-ZIP/test.pdf', 'dummy content');

    PersonnelFiles::create([
        'personnels_id' => $personnel->id,
        'pieces_id' => $piece->id,
        'file_paths' => ['personnel_files/MAT-ZIP/test.pdf'],
    ]);

    $response = $this->actingAs($this->user)->get(route('personnels.download-zip', $personnel));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/zip');
});

test('user can soft delete personnel record', function () {
    $personnel = Personnel::factory()->create();

    $response = $this->actingAs($this->user)->delete(route('personnels.destroy', $personnel));

    $response->assertRedirect(route('personnels.index'));
    $this->assertSoftDeleted('personnels', [
        'id' => $personnel->id,
    ]);
});
