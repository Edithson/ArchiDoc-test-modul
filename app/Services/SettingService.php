<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public const CACHE_KEY = 'settings.all.array';

    /**
     * Dictionnaire des valeurs par défaut par groupe pour DGB Cameroun.
     */
    protected array $defaults = [
        // Groupe Branding / Identité
        'branding.app_name' => 'ArchiDoc',
        'branding.structure_name' => 'Direction Générale du Budget',
        'branding.structure_acronym' => 'DGB',
        'branding.logo' => null,
        'branding.favicon' => null,
        'branding.login_image' => null,
        'branding.primary_color' => '#297a75',   // brand-700
        'branding.secondary_color' => '#21635f', // brand-800
        'branding.accent_color' => '#40beb7',    // brand-500
        'branding.success_color' => '#10b981',   // emerald-500
        'branding.error_color' => '#f43f5e',     // rose-500
        'branding.footer_text' => '© 2026 Direction Générale du Budget — MINFI Cameroun. Tous droits réservés.',
        'branding.contact_email' => 'contact@dgb.cm',
        'branding.contact_phone' => '+237 222 22 00 00',
        'branding.contact_address' => 'Yaoundé, Cameroun — Ministère des Finances',

        // Groupe Archivage
        'archivage.max_upload_size_mb' => 20,
        'archivage.allowed_extensions' => 'pdf, docx, xlsx, png, jpg, zip',
        'archivage.retention_period_years' => 10,

        // Groupe Sécurité
        'securite.max_login_attempts' => 5,
        'securite.lockout_duration_minutes' => 15,
        'securite.session_timeout_minutes' => 120,
    ];

    /**
     * Obtenir tous les paramètres sous forme de tableau associatif (depuis le cache).
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $settings = Setting::all();
            $data = [];

            foreach ($settings as $setting) {
                $fullKey = "{$setting->group}.{$setting->key}";
                $data[$fullKey] = [
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'is_public' => $setting->is_public,
                ];
                $data[$setting->key] = $data[$fullKey];
            }

            return $data;
        });
    }

    /**
     * Récupérer la valeur d'un paramètre avec fallback automatique.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $lookupKey = str_contains($key, '.') ? $key : "branding.{$key}";
        $all = $this->all();

        if (isset($all[$lookupKey])) {
            $item = $all[$lookupKey];
            $val = $item['value'];
            if ($val !== null && $val !== '') {
                return $this->castValue($val, $item['type']);
            }
        }

        if (isset($all[$key])) {
            $item = $all[$key];
            $val = $item['value'];
            if ($val !== null && $val !== '') {
                return $this->castValue($val, $item['type']);
            }
        }

        // Fallback sur le dictionnaire par défaut DGB Cameroun
        if (array_key_exists($lookupKey, $this->defaults)) {
            return $this->defaults[$lookupKey];
        }

        if (array_key_exists($key, $this->defaults)) {
            return $this->defaults[$key];
        }

        return $default;
    }

    /**
     * Mettre à jour ou créer un paramètre en base.
     */
    public function set(string $key, mixed $value, string $group = 'branding', string $type = 'string', bool $isPublic = false, ?int $userId = null): Setting
    {
        if (str_contains($key, '.')) {
            [$group, $key] = explode('.', $key, 2);
        }

        $setting = Setting::updateOrCreate(
            ['group' => $group, 'key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'is_public' => $isPublic,
                'updated_by' => $userId ?? auth()->id(),
            ]
        );

        $this->clearCache();

        return $setting;
    }

    /**
     * Réinitialiser / effacer le cache des paramètres.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Obtenir l'ensemble des defaults de secours.
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * Typer la valeur selon le type enregistré.
     */
    protected function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'int', 'integer' => (int) $value,
            'bool', 'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json', 'array' => is_array($value) ? $value : json_decode((string) $value, true),
            default => (string) $value,
        };
    }
}
