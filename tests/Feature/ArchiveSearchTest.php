<?php

use App\Models\Archive;
use App\Models\ArchiveType;
use App\Models\User;

test('search page returns status 200 and renders search view with archives', function () {
    $user = User::factory()->create();
    Archive::factory(15)->create();

    $response = $this->actingAs($user)->get('/consultations');

    $response->assertStatus(200);
    $response->assertViewIs('admin.pages.archives.search');
    $response->assertViewHasAll(['archives', 'archiveTypes', 'departments', 'users', 'filters']);
});

test('search page filters archives by criteria', function () {
    $user = User::factory()->create();
    $typeArrete = ArchiveType::factory()->create(['name' => 'ARRETE']);
    $typeCirculaire = ArchiveType::factory()->create(['name' => 'CIRCULAIRE']);

    Archive::factory()->create([
        'archive_type_id' => $typeArrete->id,
        'description' => 'Nomination de directeurs specifiques',
        'date_doc' => '2026-01-15',
    ]);

    Archive::factory()->create([
        'archive_type_id' => $typeCirculaire->id,
        'description' => 'Directives financieres annuelles',
        'date_doc' => '2026-02-20',
    ]);

    $response = $this->actingAs($user)->get("/consultations?archive_type_id={$typeArrete->id}&description=Nomination");

    $response->assertStatus(200);
    $response->assertSee('Nomination de directeurs specifiques');
    $response->assertDontSee('Directives financieres annuelles');
});

test('search page sorts archives correctly', function () {
    $user = User::factory()->create();

    $archiveA = Archive::factory()->create([
        'description' => 'AAA premier document',
        'date_doc' => '2026-01-01',
    ]);

    $archiveZ = Archive::factory()->create([
        'description' => 'ZZZ dernier document',
        'date_doc' => '2026-12-31',
    ]);

    $responseAsc = $this->actingAs($user)->get('/consultations?sort_by=description&sort_order=asc');
    $responseAsc->assertStatus(200);

    $archivesInView = $responseAsc->viewData('archives');
    expect($archivesInView->first()->description)->toBe('AAA premier document');
});

test('show page displays single archive details', function () {
    $user = User::factory()->create();
    $type = ArchiveType::factory()->create(['name' => 'DECISIONS']);

    $archive = Archive::factory()->create([
        'archive_type_id' => $type->id,
        'description' => 'Decision relative au controle budgetaire',
        'emplacement' => 'FOUDA',
    ]);

    $response = $this->actingAs($user)->get('/archives/'.$archive->id);

    $response->assertStatus(200);
    $response->assertViewIs('admin.pages.archives.show');
    $response->assertSee('Decision relative au controle budgetaire');
    $response->assertSee('FOUDA');
});
