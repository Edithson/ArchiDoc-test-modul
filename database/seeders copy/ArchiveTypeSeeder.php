<?php

namespace Database\Seeders;

use App\Models\ArchiveType;
use Illuminate\Database\Seeder;

class ArchiveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'ARRETE',
            'ATTESTATION',
            'AUTRES TYPES DE DOCUMENTS',
            "BONS D'ENGAGEMENT",
            'BORDEREAUX',
            "CARNETS D'ENGAGEMENT",
            'CERTIFICATS',
            'CIRCULAIRE',
            'COMMUNIQUES',
            'COMPTE ADMINISTRATIF',
            "COMPTE D'EMPLOI",
            'COMPTE-RENDU',
            'CONSTITUTION',
            'CONVOCATIONS',
            'COURRIERS',
            'DECISIONS',
            'DECRET',
            'ETATS DE SOMMES DUES',
            'FONDS DE DOSSIER',
            'INVITATIONS',
            'LETTRE CIRCULAIRE',
            'LETTRE DE MISSION',
            'LOI',
            'MEMO',
            'MEMOIRES DE DEPENSE',
            'MESSAGE-FAX',
            'MESSAGE-PORTE',
            'NOTE',
            'NOTE DE SERVICE',
            'ORDONNANCES',
            'PROCES-VERBAL',
            'SOIT-TRANSMIS',
        ];

        foreach ($types as $type) {
            ArchiveType::firstOrCreate(
                ['name' => $type],
                [
                    'description' => ucfirst(strtolower($type)),
                    'dua' => 99,
                    'created_by' => 1,
                ]
            );
        }
    }
}
