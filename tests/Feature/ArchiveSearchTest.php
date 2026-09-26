<?php

use App\Models\Archive;

test('search page returns status 200 and renders search view with archives', function () {
    Archive::factory(15)->create();

    $response = $this->get('/consultations');

    $response->assertStatus(200);
    $response->assertViewIs('admin.pages.archives.search');
    $response->assertViewHasAll(['archives', 'archiveTypes', 'users', 'filters']);
});

test('search page filters archives by criteria', function () {
    Archive::factory()->create([
        'typearchive' => 'ARRETE',
        'description' => 'Nomination de directeurs specifiques',
        'date_doc' => '2026-01-15',
    ]);

    Archive::factory()->create([
        'typearchive' => 'CIRCULAIRE',
        'description' => 'Directives financieres annuelles',
        'date_doc' => '2026-02-20',
    ]);

    $response = $this->get('/consultations?typearchive=ARRETE&description=Nomination');

    $response->assertStatus(200);
    $response->assertSee('Nomination de directeurs specifiques');
    $response->assertDontSee('Directives financieres annuelles');
});

test('search page sorts archives correctly', function () {
    $archiveA = Archive::factory()->create([
        'description' => 'AAA premier document',
        'date_doc' => '2026-01-01',
    ]);

    $archiveZ = Archive::factory()->create([
        'description' => 'ZZZ dernier document',
        'date_doc' => '2026-12-31',
    ]);

    $responseAsc = $this->get('/consultations?sort_by=description&sort_order=asc');
    $responseAsc->assertStatus(200);

    $archivesInView = $responseAsc->viewData('archives');
    expect($archivesInView->first()->description)->toBe('AAA premier document');
});

test('show page displays single archive details', function () {
    $archive = Archive::factory()->create([
        'typearchive' => 'DECISIONS',
        'description' => 'Decision relative au controle budgetaire',
        'emplacement' => 'FOUDA',
    ]);

    $response = $this->get('/archives/'.$archive->id);

    $response->assertStatus(200);
    $response->assertViewIs('admin.pages.archives.show');
    $response->assertSee('Decision relative au controle budgetaire');
    $response->assertSee('FOUDA');
});
