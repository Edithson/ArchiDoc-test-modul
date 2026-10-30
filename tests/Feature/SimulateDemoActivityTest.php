<?php

use App\Models\Archive;
use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\Personnel;
use App\Models\Piece;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $dept = Department::factory()->create(['name' => 'Direction Générale']);
    User::factory()->create([
        'statut' => true,
        'department_id' => $dept->id,
    ]);
    $type = ArchiveType::factory()->create(['name' => 'NOTE DE SERVICE']);
    Archive::factory()->create([
        'department_id' => $dept->id,
        'archive_type_id' => $type->id,
    ]);
    Personnel::factory()->create([
        'department_id' => $dept->id,
    ]);
    Piece::factory()->create();
});

test('simulation command does not run when disabled in config unless force option is passed', function () {
    Config::set('demo.simulation_enabled', false);

    $initialCount = Activity::count();

    $this->artisan('demo:simulate-activity')
        ->expectsOutputToContain('Simulation d\'activité démo désactivée')
        ->assertExitCode(0);

    expect(Activity::count())->toBe($initialCount);

    // Avec l'option --force, elle doit s'exécuter
    $this->artisan('demo:simulate-activity --force --count=3')
        ->expectsOutputToContain('Simulation terminée')
        ->assertExitCode(0);

    expect(Activity::count())->toBeGreaterThan($initialCount);
});

test('simulation command generates exact number of activities specified by count option', function () {
    Config::set('demo.simulation_enabled', true);

    $initialCount = Activity::count();

    $this->artisan('demo:simulate-activity --count=7')
        ->expectsOutputToContain('Génération de 7 activités pour aujourd\'hui')
        ->expectsOutputToContain('7 activités créées avec succès')
        ->assertExitCode(0);

    expect(Activity::count())->toBe($initialCount + 7);
});

test('simulation command handles backfill across multiple days', function () {
    Config::set('demo.simulation_enabled', true);
    Config::set('demo.weekdays_only', false); // Permettre tous les jours pour le test

    $initialCount = Activity::count();

    $this->artisan('demo:simulate-activity --days=3 --count=2')
        ->expectsOutputToContain('Rétro-génération sur les 3 derniers jours')
        ->assertExitCode(0);

    // 4 jours (J-3, J-2, J-1, J-0) * 2 = 8
    expect(Activity::count())->toBeGreaterThanOrEqual($initialCount + 6);

    // Vérifier que des activités ont bien été datées dans le passé
    $pastActivities = Activity::where('created_at', '<', Carbon::now()->startOfDay())->count();
    expect($pastActivities)->toBeGreaterThan(0);
});

test('simulation command is fully resilient and does not crash when tables or models are empty', function () {
    Config::set('demo.simulation_enabled', true);

    // Vider complètement la table des dossiers du personnel
    Personnel::query()->delete();

    // Vider complètement la table des pièces
    Piece::query()->delete();

    // L'automate doit s'exécuter jusqu'au bout sans planter et continuer avec les autres modèles
    $this->artisan('demo:simulate-activity --count=10')
        ->assertExitCode(0);
});

test('simulation creates coherent activity records recognized by analytics queries', function () {
    Config::set('demo.simulation_enabled', true);

    $this->artisan('demo:simulate-activity --count=10')
        ->assertExitCode(0);

    $simulatedActivities = Activity::where('properties->simulated', true)->latest()->take(10)->get();

    expect($simulatedActivities->count())->toBeGreaterThanOrEqual(1);

    foreach ($simulatedActivities as $activity) {
        expect($activity->causer)->not->toBeNull()
            ->and($activity->causer)->toBeInstanceOf(User::class)
            ->and($activity->properties)->toHaveKey('ip')
            ->and($activity->properties)->toHaveKey('user_agent');

        if ($activity->event === 'archive.consultation') {
            expect($activity->subject)->toBeInstanceOf(Archive::class)
                ->and($activity->properties)->toHaveKey('archive_id')
                ->and($activity->properties)->toHaveKey('departement');
        } elseif ($activity->event === 'personnel.consultation') {
            expect($activity->subject)->toBeInstanceOf(Personnel::class)
                ->and($activity->properties)->toHaveKey('personnel_id')
                ->and($activity->properties)->toHaveKey('matricule');
        }
    }
});
