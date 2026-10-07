<?php

namespace Database\Seeders;

use App\Models\Piece;
use Illuminate\Database\Seeder;

class PieceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pieces = [
            // Pièces Obligatoires
            [
                'name' => 'Acte de Naissance',
                'description' => 'Copie certifiée conforme ou légalisée de l\'acte de naissance de l\'agent',
                'obligatory' => true,
            ],
            [
                'name' => 'Diplôme le plus élevé',
                'description' => 'Copie certifiée conforme du diplôme académique ou professionnel le plus élevé',
                'obligatory' => true,
            ],
            [
                'name' => 'Certificat Médical',
                'description' => 'Certificat médical d\'aptitude physique datant de moins de 3 mois',
                'obligatory' => true,
            ],
            [
                'name' => 'Extrait de Casier Judiciaire',
                'description' => 'Bulletin n°3 du casier judiciaire de moins de 3 mois',
                'obligatory' => true,
            ],
            [
                'name' => 'Carte Nationale d\'Identité (CNI)',
                'description' => 'Photocopie lisible de la CNI en cours de validité',
                'obligatory' => true,
            ],
            [
                'name' => 'Curriculum Vitae (CV)',
                'description' => 'CV mis à jour, daté et signé par l\'agent',
                'obligatory' => true,
            ],

            // Pièces Facultatives
            [
                'name' => 'Acte de Mariage',
                'description' => 'Copie certifiée de l\'acte de mariage pour la prise en compte du statut matrimonial',
                'obligatory' => false,
            ],
            [
                'name' => 'Attestation de Travail / Certificat d\'emploi',
                'description' => 'Justificatifs des expériences professionnelles antérieures',
                'obligatory' => false,
            ],
            [
                'name' => 'Photo d\'Identité (4x4)',
                'description' => 'Photos d\'identité récentes sur fond blanc',
                'obligatory' => false,
            ],
            [
                'name' => 'RIB / Attestation de Compte Bancaire',
                'description' => 'Relevé d\'identité bancaire pour le virement du salaire',
                'obligatory' => false,
            ],
            [
                'name' => 'Actes de Naissance des Enfants',
                'description' => 'Actes de naissance des ayants droit pour les prestations sociales',
                'obligatory' => false,
            ],
        ];

        foreach ($pieces as $piece) {
            Piece::firstOrCreate(
                ['name' => $piece['name']],
                $piece
            );
        }
    }
}
