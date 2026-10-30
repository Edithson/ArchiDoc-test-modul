<?php

namespace Database\Factories;

use App\Models\ArchiveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArchiveType>
 */
class ArchiveTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sampleTypes = [
            'CONTRAT' => 'Contrats commerciaux, conventions et accords de partenariat',
            'FACTURE' => 'Factures fournisseurs, bordereaux de règlement et pièces comptables',
            'NOTE DE SERVICE' => 'Notes de service internes, instructions de direction et mémos',
            'PROCES-VERBAL' => 'Procès-verbaux de réunions, comités et assemblées générales',
            'RAPPORT D\'ACTIVITE' => 'Rapports d\'activité annuels, synthèses et bilans périodiques',
            'DECISION' => 'Décisions de direction générale et actes d\'engagement',
            'COURRIER' => 'Courriers officiels entrants et sortants, correspondances institutionnelles',
            'AUDIT' => 'Rapports d\'audit interne, contrôles de conformité et revues qualité',
            'CIRCULAIRE' => 'Circulaires administratives, procédures internes et directives',
            'FICHE DE PAIE' => 'Bulletins de salaire, états des rémunérations et déclarations',
            'DOSSIER TECHNIQUE' => 'Spécifications techniques, plans d\'ingénierie et livrables de projet',
            'BON DE COMMANDE' => 'Bons de commande signés et bons de livraison de marchandises',
        ];

        $baseName = fake()->randomElement(array_keys($sampleTypes));
        $suffix = fake()->unique()->numberBetween(1, 9999);
        $name = $baseName.' '.$suffix;

        return [
            'name' => $name,
            'description' => $sampleTypes[$baseName],
            'dua' => fake()->randomElement([3, 5, 10, 20, 30]),
            'created_by' => User::factory(),
        ];
    }
}
