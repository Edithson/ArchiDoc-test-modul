<?php

namespace Tests\Feature;

use App\Models\Archive;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'email' => 'superadmin@archidoc.cm',
        'password' => bcrypt('password123'),
        'roles' => 'super privilégé',
        'departement' => 'CAB DGB',
    ]);
});

test('archive consultation response time is under 250ms with clean query count', function () {
    $archives = Archive::factory(20)->create();
    $targetArchive = $archives->first();

    DB::enableQueryLog();
    $startTime = hrtime(true);

    $response = $this->actingAs($this->user)->get(route('archives.show', $targetArchive));

    $durationMs = (hrtime(true) - $startTime) / 1e6;
    $queries = DB::getQueryLog();

    $response->assertOk();
    expect($durationMs)->toBeLessThan(350.0);
    expect(count($queries))->toBeLessThan(20);
});

test('personnel dossier consultation response time is under 250ms', function () {
    $personnels = Personnel::factory(20)->create();
    $targetPersonnel = $personnels->first();

    DB::enableQueryLog();
    $startTime = hrtime(true);

    $response = $this->actingAs($this->user)->get(route('personnels.show', $targetPersonnel));

    $durationMs = (hrtime(true) - $startTime) / 1e6;
    $queries = DB::getQueryLog();

    $response->assertOk();
    expect($durationMs)->toBeLessThan(350.0);
    expect(count($queries))->toBeLessThan(20);
});

test('archives consultations analytics dashboard load time is under 300ms with 100 activity records', function () {
    $archive = Archive::factory()->create();

    for ($i = 0; $i < 100; $i++) {
        Activity::create([
            'log_name' => 'archive',
            'event' => 'archive.consultation',
            'description' => "Consultation PDF #{$i}",
            'subject_type' => Archive::class,
            'subject_id' => $archive->id,
            'causer_type' => User::class,
            'causer_id' => $this->user->id,
            'properties' => [
                'departement' => 'DGB-DSI',
                'format' => 'pdf',
                'ip' => '127.0.0.1',
            ],
            'created_at' => now()->subDays(rand(0, 13)),
        ]);
    }

    DB::enableQueryLog();
    $startTime = hrtime(true);

    $response = $this->actingAs($this->user)->get(route('activity-logs.archives-consultations'));

    $durationMs = (hrtime(true) - $startTime) / 1e6;
    $queries = DB::getQueryLog();

    $response->assertOk();
    expect($durationMs)->toBeLessThan(400.0);
    expect(count($queries))->toBeLessThan(30);
});

test('personnel consultations analytics dashboard load time is under 300ms with 100 activity records', function () {
    $personnel = Personnel::factory()->create();

    for ($i = 0; $i < 100; $i++) {
        Activity::create([
            'log_name' => 'personnel',
            'event' => 'personnel.consultation',
            'description' => "Consultation dossier agent #{$i}",
            'subject_type' => Personnel::class,
            'subject_id' => $personnel->id,
            'causer_type' => User::class,
            'causer_id' => $this->user->id,
            'properties' => [
                'name' => $personnel->name,
                'matricule' => $personnel->matricule,
                'departement' => 'CAB DGB',
                'ip' => '127.0.0.1',
            ],
            'created_at' => now()->subDays(rand(0, 13)),
        ]);
    }

    DB::enableQueryLog();
    $startTime = hrtime(true);

    $response = $this->actingAs($this->user)->get(route('activity-logs.personnel-consultations'));

    $durationMs = (hrtime(true) - $startTime) / 1e6;
    $queries = DB::getQueryLog();

    $response->assertOk();
    expect($durationMs)->toBeLessThan(400.0);
    expect(count($queries))->toBeLessThan(30);
});

test('streaming csv export for 200 activity logs executes in under 300ms', function () {
    for ($i = 0; $i < 200; $i++) {
        Activity::create([
            'log_name' => 'archive',
            'event' => 'archive.consultation',
            'description' => "Export test log #{$i}",
            'causer_type' => User::class,
            'causer_id' => $this->user->id,
            'properties' => ['departement' => 'DGB', 'format' => 'pdf'],
            'created_at' => now(),
        ]);
    }

    $startTime = hrtime(true);

    $response = $this->actingAs($this->user)->get(route('activity-logs.archives-consultations', ['export' => 'csv']));

    $durationMs = (hrtime(true) - $startTime) / 1e6;

    $response->assertOk();
    expect($durationMs)->toBeLessThan(400.0);
});
