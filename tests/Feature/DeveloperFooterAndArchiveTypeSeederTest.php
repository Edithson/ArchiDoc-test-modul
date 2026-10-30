<?php

use App\Models\ArchiveType;
use App\Models\User;
use Database\Seeders\ArchiveTypeSeeder;

test('archive type seeder creates coherent archive types with meaningful descriptions and DUA', function () {
    $this->seed(ArchiveTypeSeeder::class);

    $types = ArchiveType::all();
    expect($types->count())->toBeGreaterThanOrEqual(20);

    // Ensure specific essential coherent business archive types exist
    $names = $types->pluck('name')->all();
    expect($names)->toContain('CONTRAT & CONVENTION')
        ->toContain('FACTURE & PIECE COMPTABLE')
        ->toContain('NOTE DE SERVICE')
        ->toContain('PROCES-VERBAL')
        ->toContain('RAPPORT D\'ACTIVITE')
        ->toContain('DECISION DE DIRECTION')
        ->toContain('DOSSIER TECHNIQUE & PROJET');

    // Ensure all archive types have non-empty description and valid DUA
    foreach ($types as $type) {
        expect($type->description)->not->toBeEmpty()
            ->and($type->dua)->toBeGreaterThan(0)
            ->and($type->dua)->toBeLessThanOrEqual(50);
    }
});

test('login screen displays developer information footer from config', function () {
    config([
        'app.dev_name' => 'MOAFO GAÜS',
        'app.dev_adr' => 'https://moafogaus.abndns.com',
    ]);

    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Développé par');
    $response->assertSee('MOAFO GAÜS');
    $response->assertSee('https://moafogaus.abndns.com');
});

test('home page dashboard displays developer information footer from config for authenticated user', function () {
    config([
        'app.dev_name' => 'MOAFO GAÜS',
        'app.dev_adr' => 'https://moafogaus.abndns.com',
    ]);

    $user = User::factory()->create([
        'roles' => 'super privilégé',
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
    $response->assertSee('Développé par');
    $response->assertSee('MOAFO GAÜS');
    $response->assertSee('https://moafogaus.abndns.com');
});

test('developer config file developer.php exists and maps to environment variables', function () {
    expect(config('developer.name'))->toBe(config('app.dev_name'))
        ->and(config('developer.adr'))->toBe(config('app.dev_adr'));
});
