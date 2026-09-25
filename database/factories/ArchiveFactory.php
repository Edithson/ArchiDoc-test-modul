<?php

namespace Database\Factories;

use App\Models\Archive;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Archive>
 */
class ArchiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            'ARRETE',
            'ATTESTATION',
            'DECISIONS',
            'NOTE',
            'PROCES-VERBAL',
            'COURRIERS',
            'CIRCULAIRE',
            'DECRET',
            'LETTRE DE MISSION',
            'MESSAGE-FAX',
            'BORDEREAUX',
            'NOTE DE SERVICE',
        ];

        $formats = ['Document PDF', 'Image', 'Document Papier'];
        $emplacements = ['FOUDA', 'DGB', 'IMPRIMERIE NATIONALE'];
        $departements = [
            'CAB DGB',
            'DCOB',
            'DDPP',
            'DI',
            'DPB',
            'DPC',
            'DREF',
            'PUBLIC',
            'S-DAG',
            'S-DCF',
            'SGCCC',
            'SGDB',
            'SO',
        ];

        $descriptions = [
            'Portant nomination de responsables dans les services centraux du Ministère des Finances',
            'Relatif à la gestion et au suivi du budget de fonctionnement exercice 2026',
            'Procès-verbal de la réunion du comité de pilotage informatique',
            'Attestation de prise de service du personnel administratif',
            'Note d engagement des dépenses relatives aux travaux d équipement',
            'Circulaire d application des nouvelles dispositions sur la dépense publique',
            'Décision fixant l organisation des contrôles financiers régionaux',
            'Bordereau de transmission des pièces comptables et justificatives',
            'Rapport trimestriel d exécution des crédits des chapitres communs',
            'Lettre de mission pour l audit de la chaîne solde et pensions',
        ];

        return [
            'typearchive' => fake()->randomElement($types),
            'description' => fake()->randomElement($descriptions).' '.fake()->unique()->numberBetween(100, 999),
            'date_doc' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'emplacement' => fake()->randomElement($emplacements),
            'emplacement2' => 'Serveur',
            'rayon' => 'R'.fake()->numberBetween(1, 12),
            'travee' => 'T'.fake()->numberBetween(1, 20),
            'cote' => 'COT-'.fake()->numberBetween(1000, 9999),
            'format' => fake()->randomElement($formats),
            'departement' => fake()->randomElement($departements),
            'filepath' => null,
            'user_id' => 1,
        ];
    }
}
