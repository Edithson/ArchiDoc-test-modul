<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo Simulation Active State
    |--------------------------------------------------------------------------
    |
    | Cette option permet d'activer ou désactiver l'automate de simulation
    | d'activité quotidienne pour la démo / pré-production.
    |
    */
    'simulation_enabled' => (bool) env('DEMO_SIMULATION_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Quota Quotidien d'Événements
    |--------------------------------------------------------------------------
    |
    | Détermine la fourchette min et max d'activités simulées par jour
    | d'activité (consultations, téléchargements, recherches, etc.).
    |
    */
    'simulation_min' => (int) env('DEMO_SIMULATION_MIN', 5),
    'simulation_max' => (int) env('DEMO_SIMULATION_MAX', 15),

    /*
    |--------------------------------------------------------------------------
    | Plage Horaire de Travail
    |--------------------------------------------------------------------------
    |
    | Heures d'activité de bureau pour générer des horodatages réalistes
    | (ex. entre 08h00 et 18h00, jours ouvrés).
    |
    */
    'business_hours' => [
        'start' => (int) env('DEMO_SIMULATION_START_HOUR', 8),
        'end' => (int) env('DEMO_SIMULATION_END_HOUR', 18),
    ],

    'weekdays_only' => (bool) env('DEMO_SIMULATION_WEEKDAYS_ONLY', true),

];
