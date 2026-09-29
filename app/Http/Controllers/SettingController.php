<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Database\Seeders\SettingSeeder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings management page.
     */
    public function index(): View
    {
        $settingService = app(SettingService::class);
        $allSettings = $settingService->all();

        return view('admin.pages.settings.index', [
            'settingService' => $settingService,
            'allSettings' => $allSettings,
        ]);
    }

    /**
     * Update application settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            // Branding & Identité
            'app_name' => ['nullable', 'string', 'max:100'],
            'structure_name' => ['nullable', 'string', 'max:255'],
            'structure_acronym' => ['nullable', 'string', 'max:50'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:255'],

            // Couleurs
            'primary_color' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{3}){1,2}$/'],
            'secondary_color' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{3}){1,2}$/'],
            'accent_color' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{3}){1,2}$/'],
            'success_color' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{3}){1,2}$/'],
            'error_color' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{3}){1,2}$/'],

            // Fichiers visuels
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,svg', 'max:1024'],
            'login_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],

            // Archivage
            'max_upload_size_mb' => ['nullable', 'integer', 'min:1', 'max:500'],
            'allowed_extensions' => ['nullable', 'string', 'max:255'],
            'retention_period_years' => ['nullable', 'integer', 'min:1', 'max:100'],

            // Sécurité
            'max_login_attempts' => ['nullable', 'integer', 'min:1', 'max:20'],
            'lockout_duration_minutes' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'session_timeout_minutes' => ['nullable', 'integer', 'min:15', 'max:1440'],
        ]);

        $settingService = app(SettingService::class);
        $userId = auth()->id();

        // 1. Mise à jour des paramètres textes et numériques
        $textFields = [
            'branding' => [
                'app_name' => 'string',
                'structure_name' => 'string',
                'structure_acronym' => 'string',
                'footer_text' => 'string',
                'contact_email' => 'string',
                'contact_phone' => 'string',
                'contact_address' => 'string',
                'primary_color' => 'color',
                'secondary_color' => 'color',
                'accent_color' => 'color',
                'success_color' => 'color',
                'error_color' => 'color',
            ],
            'archivage' => [
                'max_upload_size_mb' => 'int',
                'allowed_extensions' => 'string',
                'retention_period_years' => 'int',
            ],
            'securite' => [
                'max_login_attempts' => 'int',
                'lockout_duration_minutes' => 'int',
                'session_timeout_minutes' => 'int',
            ],
        ];

        foreach ($textFields as $group => $fields) {
            foreach ($fields as $key => $type) {
                if ($request->has($key)) {
                    $settingService->set($key, $request->input($key), $group, $type, true, $userId);
                }
            }
        }

        // 2. Traitement des uploads de fichiers (Logo, Favicon, Login Image)
        $fileInputs = ['logo', 'favicon', 'login_image'];
        foreach ($fileInputs as $fileInput) {
            if ($request->hasFile($fileInput) && $request->file($fileInput)->isValid()) {
                $path = $request->file($fileInput)->store('branding', 'public');
                $url = Storage::url($path);
                $settingService->set($fileInput, $url, 'branding', 'file', true, $userId);
            }
        }

        return redirect()->route('settings.index')->with('success', 'Les paramètres de l\'application ont été mis à jour avec succès.');
    }

    /**
     * Réinitialiser tous les paramètres aux valeurs par défaut DGB Cameroun.
     */
    public function reset(Request $request): RedirectResponse
    {
        // Exécuter le seeder pour rétablir les valeurs par défaut
        app(SettingSeeder::class)->run();

        return redirect()->route('settings.index')->with('success', 'Tous les paramètres ont été réinitialisés avec succès aux valeurs par défaut de la DGB Cameroun.');
    }
}
