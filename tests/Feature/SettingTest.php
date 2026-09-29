<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SettingService;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Utilisateur Super Privilégié (Super Administrateur)
    $this->adminUser = User::factory()->create([
        'roles' => 'super privilégé',
        'email' => 'admin.settings@minfi.cm',
    ]);

    // Utilisateur Standard (Non Super Privilégié)
    $this->standardUser = User::factory()->create([
        'roles' => 'classique',
        'email' => 'standard.agent@minfi.cm',
    ]);
});

test('non authenticated user cannot access settings pages', function () {
    $this->get(route('settings.index'))->assertRedirect(route('login'));
    $this->post(route('settings.update'), [])->assertRedirect(route('login'));
});

test('standard non-super-privileged user is forbidden from settings management', function () {
    $this->actingAs($this->standardUser);

    $this->get(route('settings.index'))->assertStatus(403);
    $this->post(route('settings.update'), ['app_name' => 'HackedAppName'])->assertStatus(403);
});

test('super administrator can view settings page with default DGB Cameroun values', function () {
    $this->actingAs($this->adminUser);

    $this->seed(SettingSeeder::class);

    $response = $this->get(route('settings.index'));

    $response->assertStatus(200);
    $response->assertSee('Paramètres du Système');
    $response->assertSee('Direction Générale du Budget');
    $response->assertSee('#297a75');
});

test('super administrator can update settings and cache is invalidated immediately', function () {
    $this->actingAs($this->adminUser);

    $this->seed(SettingSeeder::class);

    expect(setting('branding.structure_name'))->toBe('Direction Générale du Budget');

    $response = $this->post(route('settings.update'), [
        'app_name' => 'ArchiDoc Pro',
        'structure_name' => 'Ministère des Finances du Cameroun',
        'structure_acronym' => 'MINFI',
        'primary_color' => '#194c49',
        'max_upload_size_mb' => 50,
    ]);

    $response->assertRedirect(route('settings.index'));
    $response->assertSessionHas('success');

    // Vérifier l'invalidation du cache et les nouvelles valeurs
    expect(setting('branding.app_name'))->toBe('ArchiDoc Pro');
    expect(setting('branding.structure_name'))->toBe('Ministère des Finances du Cameroun');
    expect(setting('branding.primary_color'))->toBe('#194c49');
    expect(setting('archivage.max_upload_size_mb'))->toBe(50);
});

test('setting helper returns fallback defaults when database has missing key', function () {
    Cache::forget(SettingService::CACHE_KEY);

    expect(setting('branding.app_name'))->toBe('ArchiDoc');
    expect(setting('branding.structure_name'))->toBe('Direction Générale du Budget');
    expect(setting('branding.primary_color'))->toBe('#297a75');
    expect(setting('non_existing_key', 'fallback_custom'))->toBe('fallback_custom');
});

test('super administrator can reset settings to default DGB values', function () {
    $this->actingAs($this->adminUser);

    $this->seed(SettingSeeder::class);

    // Modifier un paramètre
    $this->post(route('settings.update'), [
        'app_name' => 'CustomName',
        'primary_color' => '#123456',
    ]);
    expect(setting('branding.app_name'))->toBe('CustomName');
    expect(setting('branding.primary_color'))->toBe('#123456');

    // Réinitialiser
    $response = $this->post(route('settings.reset'));
    $response->assertRedirect(route('settings.index'));

    expect(setting('branding.app_name'))->toBe('ArchiDoc');
    expect(setting('branding.primary_color'))->toBe('#297a75');
});

test('layout renders dynamically injected CSS color variables', function () {
    $this->actingAs($this->adminUser);

    $this->seed(SettingSeeder::class);

    $response = $this->get(route('archives.index'));
    $response->assertStatus(200);
    $response->assertSee('--color-brand-700: #297a75', false);
    $response->assertSee('--color-brand-800: #21635f', false);
});
