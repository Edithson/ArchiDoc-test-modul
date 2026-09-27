<?php

use App\Models\ArchiveLocation;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create([
        'roles' => 'super privilégé',
    ]);
});

test('authenticated user can view archive locations list page', function () {
    ArchiveLocation::factory()->create(['name' => 'FOUDA SITE A']);

    $response = $this->actingAs($this->user)->get(route('archive-locations.index'));

    $response->assertStatus(200);
    $response->assertSee('FOUDA SITE A');
});

test('user can filter archive locations by search and type', function () {
    ArchiveLocation::factory()->physical()->create(['name' => 'MAGASIN FOUDA']);
    ArchiveLocation::factory()->virtual()->create(['name' => 'SERVEUR NAS DGB']);

    $response = $this->actingAs($this->user)->get(route('archive-locations.index', ['type' => '2']));

    $response->assertStatus(200);
    $response->assertSee('SERVEUR NAS DGB');
    $response->assertDontSee('MAGASIN FOUDA');
});

test('user can access create location page', function () {
    $response = $this->actingAs($this->user)->get(route('archive-locations.create'));

    $response->assertStatus(200);
    $response->assertSee('Créer un Emplacement', false);
});

test('user can store a new physical location', function () {
    $data = [
        'name' => 'ANNEXE MESSA',
        'description' => 'Bâtiment annexe pour archives financières',
        'location' => 'Quartier Messa, Yaoundé',
        'type' => ArchiveLocation::TYPE_PHYSICAL,
    ];

    $response = $this->actingAs($this->user)->post(route('archive-locations.store'), $data);

    $response->assertRedirect(route('archive-locations.index'));
    $this->assertDatabaseHas('archive_locations', [
        'name' => 'ANNEXE MESSA',
        'type' => ArchiveLocation::TYPE_PHYSICAL,
        'created_by' => $this->user->id,
    ]);
});

test('user can store a new virtual location', function () {
    $data = [
        'name' => 'SERVEUR SECONDAIRE',
        'description' => 'Serveur miroir de sauvegarde',
        'location' => '192.168.1.150 / SAN',
        'type' => ArchiveLocation::TYPE_VIRTUAL,
    ];

    $response = $this->actingAs($this->user)->post(route('archive-locations.store'), $data);

    $response->assertRedirect(route('archive-locations.index'));
    $this->assertDatabaseHas('archive_locations', [
        'name' => 'SERVEUR SECONDAIRE',
        'type' => ArchiveLocation::TYPE_VIRTUAL,
    ]);
});

test('store fails if location name is duplicate', function () {
    ArchiveLocation::factory()->create(['name' => 'MAGASIN 01']);

    $response = $this->actingAs($this->user)->post(route('archive-locations.store'), [
        'name' => 'MAGASIN 01',
        'type' => ArchiveLocation::TYPE_PHYSICAL,
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('user can edit and update a location', function () {
    $location = ArchiveLocation::factory()->create(['name' => 'ANCIEN NOM', 'type' => 1]);

    $response = $this->actingAs($this->user)->get(route('archive-locations.edit', $location));
    $response->assertStatus(200);
    $response->assertSee('ANCIEN NOM');

    $updateResponse = $this->actingAs($this->user)->put(route('archive-locations.update', $location), [
        'name' => 'NOUVEAU NOM',
        'description' => 'Nouvelle description',
        'location' => 'Nouvelle adresse',
        'type' => 2,
    ]);

    $updateResponse->assertRedirect(route('archive-locations.index'));
    $this->assertDatabaseHas('archive_locations', [
        'id' => $location->id,
        'name' => 'NOUVEAU NOM',
        'type' => 2,
    ]);
});

test('user can soft delete a location', function () {
    $location = ArchiveLocation::factory()->create(['name' => 'SITE A SUPPRIMER']);

    $response = $this->actingAs($this->user)->delete(route('archive-locations.destroy', $location));

    $response->assertRedirect(route('archive-locations.index'));
    $this->assertSoftDeleted('archive_locations', [
        'id' => $location->id,
    ]);
});

test('archive create form fetches dynamic physical and virtual locations from database', function () {
    ArchiveLocation::factory()->physical()->create(['name' => 'MAGASIN TEST DGB', 'description' => 'Magasin test']);
    ArchiveLocation::factory()->virtual()->create(['name' => 'NAS VIRTUAL DGB']);

    $response = $this->actingAs($this->user)->get(route('archives.create'));

    $response->assertStatus(200);
    $response->assertSee('MAGASIN TEST DGB');
    $response->assertSee('NAS VIRTUAL DGB');
});
