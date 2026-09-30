<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Department;
use App\Models\Personnel;
use App\Models\PersonnelFiles;
use App\Models\Piece;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'email' => 'testlog@archidoc.cm',
        'password' => bcrypt('password123'),
        'roles' => 'super privilégé',
    ]);
});

test('successful login triggers auth.login activity log with IP and user details', function () {
    event(new Login('web', $this->user, false));

    $activity = Activity::where('event', 'auth.login')->latest()->first();

    expect($activity)->not->toBeNull();
    expect($activity->log_name)->toBe('auth');
    expect($activity->causer_id)->toBe($this->user->id);
    expect($activity->properties->has('ip'))->toBeTrue();
});

test('failed login attempt triggers auth.failed_login activity log with attempted credentials', function () {
    event(new Failed('web', null, ['email' => 'hacker@minfi.cm', 'password' => 'wrong']));

    $activity = Activity::where('event', 'auth.failed_login')->latest()->first();

    expect($activity)->not->toBeNull();
    expect($activity->log_name)->toBe('auth');
    expect($activity->properties['attempted'])->toBe('hacker@minfi.cm');
});

test('logout event triggers auth.logout activity log', function () {
    event(new Logout('web', $this->user));

    $activity = Activity::where('event', 'auth.logout')->latest()->first();

    expect($activity)->not->toBeNull();
    expect($activity->causer_id)->toBe($this->user->id);
});

test('lockout event triggers auth.lockout activity log', function () {
    event(new Lockout(request()));

    $activity = Activity::where('event', 'auth.lockout')->latest()->first();

    expect($activity)->not->toBeNull();
});

test('model creation and update generate detailed activity log records with old and new values', function () {
    $this->actingAs($this->user);

    $department = Department::create([
        'name' => 'ACTIVITY-DEPT',
        'description' => 'Original Description',
    ]);

    $createActivity = Activity::where('subject_type', Department::class)
        ->where('subject_id', $department->id)
        ->where('event', 'created')
        ->first();

    expect($createActivity)->not->toBeNull();
    expect($createActivity->causer_id)->toBe($this->user->id);

    $department->update([
        'description' => 'New Description',
    ]);

    $updateActivity = Activity::where('subject_type', Department::class)
        ->where('subject_id', $department->id)
        ->where('event', 'updated')
        ->first();

    expect($updateActivity)->not->toBeNull();
    expect($updateActivity->attribute_changes['old']['description'])->toBe('Original Description');
    expect($updateActivity->attribute_changes['attributes']['description'])->toBe('New Description');
});

test('consulting an archive logs archive.consultation event asynchronously', function () {
    $archive = Archive::factory()->create(['description' => 'DOC-SURVEILLANCE-001']);

    $response = $this->actingAs($this->user)->get(route('archives.show', $archive));

    $response->assertOk();

    $activity = Activity::where('event', 'archive.consultation')
        ->where('subject_type', Archive::class)
        ->where('subject_id', $archive->id)
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->causer_id)->toBe($this->user->id);
    expect($activity->properties['description'])->toBe('DOC-SURVEILLANCE-001');
});

test('downloading an archive logs archive.download event asynchronously', function () {
    Storage::fake('public');
    Storage::disk('public')->put('archives/doc_test.pdf', 'Content');

    $archive = Archive::factory()->create([
        'description' => 'DOC-DOWNLOAD-001',
        'filepath' => 'archives/doc_test.pdf',
    ]);

    $response = $this->actingAs($this->user)->get(route('archives.download', $archive));

    $response->assertOk();

    $activity = Activity::where('event', 'archive.download')
        ->where('subject_type', Archive::class)
        ->where('subject_id', $archive->id)
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->causer_id)->toBe($this->user->id);
    expect($activity->properties['description'])->toBe('DOC-DOWNLOAD-001');
});

test('consulting a personnel dossier logs personnel.consultation event asynchronously', function () {
    $personnel = Personnel::factory()->create(['name' => 'TCHATCHOUANG Paul', 'matricule' => 'MAT-8877']);

    $response = $this->actingAs($this->user)->get(route('personnels.show', $personnel));

    $response->assertOk();

    $activity = Activity::where('event', 'personnel.consultation')
        ->where('subject_type', Personnel::class)
        ->where('subject_id', $personnel->id)
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->causer_id)->toBe($this->user->id);
    expect($activity->properties['name'])->toBe('TCHATCHOUANG Paul');
    expect($activity->properties['matricule'])->toBe('MAT-8877');
});

test('downloading a personnel ZIP dossier logs personnel.download event asynchronously', function () {
    Storage::fake('public');
    Storage::disk('public')->put('personnel_files/MAT-9988/piece_test.pdf', 'Content');

    $personnel = Personnel::factory()->create(['name' => 'FOUDA Joseph', 'matricule' => 'MAT-9988']);
    $piece = Piece::factory()->create(['name' => 'Acte de nomination']);

    PersonnelFiles::create([
        'personnels_id' => $personnel->id,
        'pieces_id' => $piece->id,
        'file_paths' => ['personnel_files/MAT-9988/piece_test.pdf'],
    ]);

    $response = $this->actingAs($this->user)->get(route('personnels.download-zip', $personnel));

    $response->assertOk();

    $activity = Activity::where('event', 'personnel.download')
        ->where('subject_type', Personnel::class)
        ->where('subject_id', $personnel->id)
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->causer_id)->toBe($this->user->id);
    expect($activity->properties['name'])->toBe('FOUDA Joseph');
    expect($activity->properties['matricule'])->toBe('MAT-9988');
});

test('archives consultations dashboard page renders correctly with KPI statistics and filters', function () {
    $archive = Archive::factory()->create(['description' => 'DOC-HISTORIQUE-TEST']);

    activity('archive')
        ->performedOn($archive)
        ->causedBy($this->user)
        ->event('archive.consultation')
        ->withProperties([
            'archive_id' => $archive->id,
            'description' => $archive->description,
            'format' => 'pdf',
            'departement' => 'DGB-DSI',
            'ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit Test Agent',
        ])
        ->log('Consultation archive PDF: DOC-HISTORIQUE-TEST');

    $response = $this->actingAs($this->user)->get(route('activity-logs.archives-consultations'));

    $response->assertOk();
    $response->assertViewIs('admin.pages.activity_logs.archives_consultations');
    $response->assertSee('DOC-HISTORIQUE-TEST');
});

test('archives consultations export outputs csv, json, and txt formats', function () {
    $archive = Archive::factory()->create(['description' => 'DOC-EXPORT-TEST']);

    activity('archive')
        ->performedOn($archive)
        ->causedBy($this->user)
        ->event('archive.consultation')
        ->withProperties([
            'archive_id' => $archive->id,
            'description' => $archive->description,
            'format' => 'pdf',
            'departement' => 'DGB-DGB',
            'ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit Test Agent',
        ])
        ->log('Consultation archive PDF: DOC-EXPORT-TEST');

    // Test CSV export
    $csvResponse = $this->actingAs($this->user)->get(route('activity-logs.archives-consultations', ['export' => 'csv']));
    $csvResponse->assertOk();
    $csvResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');

    // Test JSON export
    $jsonResponse = $this->actingAs($this->user)->get(route('activity-logs.archives-consultations', ['export' => 'json']));
    $jsonResponse->assertOk();
    $jsonResponse->assertHeader('content-type', 'application/json; charset=UTF-8');

    // Test TXT export
    $txtResponse = $this->actingAs($this->user)->get(route('activity-logs.archives-consultations', ['export' => 'txt']));
    $txtResponse->assertOk();
    $txtResponse->assertHeader('content-type', 'text/plain; charset=UTF-8');
});
