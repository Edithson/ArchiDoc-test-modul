<?php

namespace Database\Seeders;

use App\Models\ArchiveLocation;
use Illuminate\Database\Seeder;

class ArchiveLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            // Emplacements Physiques (Type = 1)
            [
                'name' => 'FOUDA',
                'description' => "FOUDA — Centre d'excellence DGB",
                'location' => 'Quartier Fouda, Yaoundé',
                'type' => ArchiveLocation::TYPE_PHYSICAL,
                'created_by' => 1,
            ],
            [
                'name' => 'DGB',
                'description' => 'DGB — Direction Générale du Budget',
                'location' => 'Centre Administratif, Yaoundé',
                'type' => ArchiveLocation::TYPE_PHYSICAL,
                'created_by' => 1,
            ],
            [
                'name' => 'IMPRIMERIE NATIONALE',
                'description' => 'Imprimerie Nationale',
                'location' => 'Quartier Messa, Yaoundé',
                'type' => ArchiveLocation::TYPE_PHYSICAL,
                'created_by' => 1,
            ],
            // Emplacements Virtuels (Type = 2)
            [
                'name' => 'Serveur',
                'description' => 'Serveur de Stockage Central DGB',
                'location' => '192.168.1.100 / NAS-ARCHIDOC',
                'type' => ArchiveLocation::TYPE_VIRTUAL,
                'created_by' => 1,
            ],
            [
                'name' => 'Cloud DGB',
                'description' => 'Serveur de Sauvegarde et d\'Archivage Cloud',
                'location' => 'Cloud DGB / Backup Storage',
                'type' => ArchiveLocation::TYPE_VIRTUAL,
                'created_by' => 1,
            ],
        ];

        foreach ($locations as $loc) {
            ArchiveLocation::firstOrCreate(
                ['name' => $loc['name']],
                $loc
            );
        }
    }
}
