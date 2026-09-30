<?php

use App\Services\SettingService;

if (! function_exists('setting')) {
    /**
     * Accès simplifié et sécurisé aux paramètres de l'application avec cache et valeur par défaut.
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        $service = app(SettingService::class);

        if (is_null($key)) {
            return $service;
        }

        return $service->get($key, $default);
    }
}
