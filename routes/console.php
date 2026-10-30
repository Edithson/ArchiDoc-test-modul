<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Planification de l'automate de simulation d'activité (Démo / Préprod)
|--------------------------------------------------------------------------
|
| Exécute l'automate toutes les heures pendant les heures de bureau en semaine
| pour simuler des consultations et téléchargements réalistes.
| Activable / désactivable directement via la variable DEMO_SIMULATION_ENABLED.
|
*/
Schedule::command('demo:simulate-activity --hourly')
    ->hourly()
    ->weekdays()
    ->between('08:00', '18:00')
    ->when(fn () => (bool) config('demo.simulation_enabled', false));
