<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Utilisateur Super Privilégié (Super Administrateur)
    $this->adminUser = User::factory()->create([
        'roles' => 'super privilégé',
        'email' => 'admin.audit@minfi.cm',
    ]);

    // Utilisateur Standard (Non Super Privilégié)
    $this->standardUser = User::factory()->create([
        'roles' => 'classique',
        'email' => 'standard.agent@minfi.cm',
    ]);
});

test('non authenticated user cannot access activity log pages', function () {
    $this->get(route('activity-logs.index'))->assertRedirect(route('login'));
    $this->get(route('activity-logs.auth'))->assertRedirect(route('login'));
    $this->get(route('activity-logs.system'))->assertRedirect(route('login'));
});

test('standard non-super-privileged user is forbidden from accessing activity logs', function () {
    $this->actingAs($this->standardUser);

    $this->get(route('activity-logs.index'))->assertStatus(403);
    $this->get(route('activity-logs.auth'))->assertStatus(403);
    $this->get(route('activity-logs.system'))->assertStatus(403);
});

test('super administrator can view main activity log event tree page', function () {
    $this->actingAs($this->adminUser);

    // Créer une activité de test
    Department::create([
        'name' => 'TEST-AUDIT-DEPT',
        'description' => 'Département de test audit',
    ]);

    $response = $this->get(route('activity-logs.index'));

    $response->assertStatus(200);
    $response->assertSee('Boîte Noire — Arbre des Événements');
    $response->assertSee('Department');
});

test('super administrator can filter activity logs by search query and log category', function () {
    $this->actingAs($this->adminUser);

    activity('auth')
        ->causedBy($this->adminUser)
        ->log('Connexion sécurisée administrateur');

    activity('default')
        ->causedBy($this->adminUser)
        ->log('Mise à jour spécifique document');

    // Filtre par catégorie auth
    $authResponse = $this->get(route('activity-logs.index', ['log_name' => 'auth']));
    $authResponse->assertStatus(200);
    $authResponse->assertSee('Connexion sécurisée administrateur');
    $authResponse->assertDontSee('Mise à jour spécifique document');

    // Filtre par recherche textuelle
    $searchResponse = $this->get(route('activity-logs.index', ['search' => 'spécifique']));
    $searchResponse->assertStatus(200);
    $searchResponse->assertSee('Mise à jour spécifique document');
    $searchResponse->assertDontSee('Connexion sécurisée administrateur');
});

test('super administrator can view security auth activity logs dashboard', function () {
    $this->actingAs($this->adminUser);

    activity('auth')
        ->by($this->adminUser)
        ->event('auth.login')
        ->withProperty('ip', '192.168.1.100')
        ->log('Connexion réussie utilisateur');

    $response = $this->get(route('activity-logs.auth'));

    $response->assertStatus(200);
    $response->assertSee('Boîte Noire — Sécurité');
    $response->assertSee('Connexion réussie utilisateur');
    $response->assertSee('192.168.1.100');
});

test('super administrator can view system diagnostic activity logs dashboard', function () {
    $this->actingAs($this->adminUser);

    activity('system')
        ->event('system.error')
        ->withProperties([
            'exception_class' => 'RuntimeException',
            'file' => '/app/Services/TestService.php',
            'line' => 42,
            'trace' => ['#0 /app/Services/TestService.php(42): test()'],
        ])
        ->log('Anomalie critique d\'exécution test');

    $response = $this->get(route('activity-logs.system'));

    $response->assertStatus(200);
    $response->assertSee('Boîte Noire — Diagnostic System');
    $response->assertSee('Anomalie critique d\'exécution test');
    $response->assertSee('RuntimeException');
});

test('super administrator can fetch activity log detail via JSON API endpoint', function () {
    $this->actingAs($this->adminUser);

    $activity = activity('default')
        ->causedBy($this->adminUser)
        ->event('updated')
        ->withProperties([
            'old' => ['name' => 'Ancien Nom'],
            'attributes' => ['name' => 'Nouveau Nom'],
        ])
        ->log('Modification du nom de département');

    $response = $this->getJson(route('activity-logs.show', $activity));

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $activity->id,
        'log_name' => 'default',
        'description' => 'Modification du nom de département',
        'event' => 'updated',
        'causer' => [
            'id' => $this->adminUser->id,
            'name' => $this->adminUser->name,
        ],
        'properties' => [
            'old' => ['name' => 'Ancien Nom'],
            'attributes' => ['name' => 'Nouveau Nom'],
        ],
    ]);
});
