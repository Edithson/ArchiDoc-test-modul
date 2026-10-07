<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use App\Services\SettingService;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed application default settings for DGB Cameroun.
     */
    public function run(): void
    {
        $admin = User::whereHas('role', function ($q) {
            $q->where('name', 'like', '%super%');
        })->first() ?? User::first();
        $adminId = $admin ? $admin->id : null;

        $settings = [
            // Groupe Branding & Identité DGB Cameroun
            [
                'group' => 'branding',
                'key' => 'app_name',
                'value' => 'ArchiDoc',
                'type' => 'string',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'structure_name',
                'value' => 'Direction Générale du Budget',
                'type' => 'string',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'structure_acronym',
                'value' => 'DGB',
                'type' => 'string',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'logo',
                'value' => null,
                'type' => 'file',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'favicon',
                'value' => null,
                'type' => 'file',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'login_image',
                'value' => null,
                'type' => 'file',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'primary_color',
                'value' => '#297a75', // brand-700
                'type' => 'color',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'secondary_color',
                'value' => '#21635f', // brand-800
                'type' => 'color',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'accent_color',
                'value' => '#40beb7', // brand-500
                'type' => 'color',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'success_color',
                'value' => '#10b981', // emerald-500
                'type' => 'color',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'error_color',
                'value' => '#f43f5e', // rose-500
                'type' => 'color',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'footer_text',
                'value' => '© 2026 Direction Générale du Budget — MINFI Cameroun. Tous droits réservés.',
                'type' => 'string',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'contact_email',
                'value' => 'contact@dgb.cm',
                'type' => 'string',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'contact_phone',
                'value' => '+237 222 22 00 00',
                'type' => 'string',
                'is_public' => true,
            ],
            [
                'group' => 'branding',
                'key' => 'contact_address',
                'value' => 'Yaoundé, Cameroun — Ministère des Finances',
                'type' => 'string',
                'is_public' => true,
            ],

            // Groupe Archivage
            [
                'group' => 'archivage',
                'key' => 'max_upload_size_mb',
                'value' => 20,
                'type' => 'int',
                'is_public' => false,
            ],
            [
                'group' => 'archivage',
                'key' => 'allowed_extensions',
                'value' => 'pdf, docx, xlsx, png, jpg, zip',
                'type' => 'string',
                'is_public' => false,
            ],
            [
                'group' => 'archivage',
                'key' => 'retention_period_years',
                'value' => 10,
                'type' => 'int',
                'is_public' => false,
            ],

            // Groupe Sécurité
            [
                'group' => 'securite',
                'key' => 'max_login_attempts',
                'value' => 5,
                'type' => 'int',
                'is_public' => false,
            ],
            [
                'group' => 'securite',
                'key' => 'lockout_duration_minutes',
                'value' => 15,
                'type' => 'int',
                'is_public' => false,
            ],
            [
                'group' => 'securite',
                'key' => 'session_timeout_minutes',
                'value' => 120,
                'type' => 'int',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                array_merge($setting, ['updated_by' => $adminId])
            );
        }

        app(SettingService::class)->clearCache();
    }
}
