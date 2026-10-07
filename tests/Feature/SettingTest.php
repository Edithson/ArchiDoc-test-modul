<?php

namespace Tests\Feature;

use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\User;
use App\Services\SettingService;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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

test('super administrator can view settings page with default enterprise values', function () {
    $this->actingAs($this->adminUser);

    $this->seed(SettingSeeder::class);

    $response = $this->get(route('settings.index'));

    $response->assertStatus(200);
    $response->assertSee('Paramètres du Système');
    $response->assertSee('Entreprise Générale');
    $response->assertSee('#297a75');
});

test('super administrator can update settings and cache is invalidated immediately', function () {
    $this->actingAs($this->adminUser);

    $this->seed(SettingSeeder::class);

    expect(setting('branding.structure_name'))->toBe('Entreprise Générale');

    $response = $this->post(route('settings.update'), [
        'app_name' => 'ArchiDoc Pro',
        'structure_name' => 'Nouvelle Société Anonyme',
        'structure_acronym' => 'NSA',
        'primary_color' => '#194c49',
        'max_upload_size_mb' => 50,
    ]);

    $response->assertRedirect(route('settings.index'));
    $response->assertSessionHas('success');

    // Vérifier l'invalidation du cache et les nouvelles valeurs
    expect(setting('branding.app_name'))->toBe('ArchiDoc Pro');
    expect(setting('branding.structure_name'))->toBe('Nouvelle Société Anonyme');
    expect(setting('branding.primary_color'))->toBe('#194c49');
    expect(setting('archivage.max_upload_size_mb'))->toBe(50);
});

test('setting helper returns fallback defaults when database has missing key', function () {
    Cache::forget(SettingService::CACHE_KEY);

    expect(setting('branding.app_name'))->toBe('ArchiDoc');
    expect(setting('branding.structure_name'))->toBe('Entreprise Générale');
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

test('super administrator can upload application logo and favicon', function () {
    Storage::fake('public');
    $this->actingAs($this->adminUser);

    $logo = UploadedFile::fake()->image('custom_logo.png', 100, 100);

    $response = $this->post(route('settings.update'), [
        'logo' => $logo,
    ]);

    $response->assertRedirect(route('settings.index'));

    $storedUrl = setting('branding.logo');
    expect($storedUrl)->not()->toBeNull();
});

test('archive file upload enforces dynamic max size and allowed extensions from settings', function () {
    $this->actingAs($this->adminUser);
    $this->seed(SettingSeeder::class);

    // Définir la limite à 1 MB et autoriser uniquement PDF
    $this->post(route('settings.update'), [
        'max_upload_size_mb' => 1,
        'allowed_extensions' => 'pdf',
    ]);

    $dept = Department::factory()->create();
    $type = ArchiveType::factory()->create();

    // Tentative avec un fichier PNG (non autorisé)
    $filePng = UploadedFile::fake()->create('document.png', 500);
    $res1 = $this->post(route('archives.store'), [
        'file' => $filePng,
        'format' => 'Numérique',
        'archive_type_id' => $type->id,
        'description' => 'Test PNG',
        'date_doc' => '2026-09-29',
        'emplacement' => 'Serveur A',
        'emplacement2' => 'Magasin 1',
        'department_id' => $dept->id,
    ]);
    $res1->assertSessionHasErrors(['file']);

    // Tentative avec un fichier PDF de 2 MB (dépassant 1 MB)
    $filePdfBig = UploadedFile::fake()->create('document_big.pdf', 2048);
    $res2 = $this->post(route('archives.store'), [
        'file' => $filePdfBig,
        'format' => 'Numérique',
        'archive_type_id' => $type->id,
        'description' => 'Test Big PDF',
        'date_doc' => '2026-09-29',
        'emplacement' => 'Serveur A',
        'emplacement2' => 'Magasin 1',
        'department_id' => $dept->id,
    ]);
    $res2->assertSessionHasErrors(['file']);
});

test('login rate limiting enforces dynamic max attempts from settings', function () {
    $this->seed(SettingSeeder::class);

    // Régler les tentatives max à 2
    app(SettingService::class)->set('max_login_attempts', 2, 'securite', 'int');

    // Échec 1
    $this->post('/login', ['email' => 'admin.settings@minfi.cm', 'password' => 'wrong']);
    // Échec 2
    $this->post('/login', ['email' => 'admin.settings@minfi.cm', 'password' => 'wrong']);

    // Échec 3 -> doit être bloqué par le rate limiter
    $response = $this->post('/login', ['email' => 'admin.settings@minfi.cm', 'password' => 'wrong']);
    $response->assertSessionHasErrors(['email']);
    $errorMessage = session('errors')->get('email')[0];
    expect($errorMessage)->toMatch('/(Too many login attempts|tentatives)/i');
});

test('session timeout middleware logs out inactive users when timeout is exceeded', function () {
    $this->actingAs($this->adminUser);

    // Régler l'inactivité max à 10 minutes
    app(SettingService::class)->set('session_timeout_minutes', 10, 'securite', 'int');

    // Simuler une dernière activité il y a 15 minutes
    session(['last_activity_time' => time() - (15 * 60)]);

    $response = $this->get(route('archives.index'));
    $response->assertRedirect(route('login'));
    expect(auth()->check())->toBeFalse();
});
