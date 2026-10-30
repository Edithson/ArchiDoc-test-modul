<?php

namespace Database\Factories;

use App\Models\Archive;
use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\User;
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
        $formats = ['Document PDF', 'Image', 'Document Papier'];
        $emplacements = ['MAGASIN-CENTRAL', 'SALLE-ARCHIVES-01', 'DEPOT-PRINCIPAL'];

        $descriptions = [
            'Portant nomination de responsables de département et chefs de service',
            'Relatif à la gestion et au suivi du budget d exploitation exercice 2026',
            'Procès-verbal de la réunion du comité de pilotage stratégique',
            'Attestation de prise de service du personnel administratif',
            'Note d engagement des dépenses relatives aux travaux d équipement',
            'Circulaire d application des nouvelles procédures opérationnelles',
            'Décision fixant l organisation des contrôles et audits internes',
            'Bordereau de transmission des pièces comptables et justificatives',
            'Rapport trimestriel d exécution des objectifs et indicateurs clés',
            'Lettre de mission pour l audit des processus opérationnels',
        ];

        return [
            'archive_type_id' => ArchiveType::query()->inRandomOrder()->value('id') ?? ArchiveType::factory(),
            'description' => fake()->randomElement($descriptions).' '.fake()->unique()->numberBetween(100, 999),
            'date_doc' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'emplacement' => fake()->randomElement($emplacements),
            'emplacement2' => 'Serveur',
            'rayon' => 'R'.fake()->numberBetween(1, 12),
            'travee' => 'T'.fake()->numberBetween(1, 20),
            'cote' => 'COT-'.fake()->numberBetween(1000, 9999),
            'format' => fake()->randomElement($formats),
            'department_id' => function () {
                $main = Department::whereNull('parent_id')->first();

                return $main ? $main->id : Department::factory()->create(['parent_id' => null])->id;
            },
            'sub_department_id' => null,
            'filepath' => null,
            'user_id' => User::factory(),
        ];
    }
}
