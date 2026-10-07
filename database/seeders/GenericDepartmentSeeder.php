<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class GenericDepartmentSeeder extends Seeder
{
    /**
     * Seed generic enterprise departments and sub-departments.
     */
    public function run(): void
    {
        // 1. Directions Principales (Main Corporate Departments)
        $mainDepartments = [
            'DG' => ['name' => 'DG', 'description' => 'Direction Générale'],
            'DRH' => ['name' => 'DRH', 'description' => 'Direction des Ressources Humaines'],
            'DFC' => ['name' => 'DFC', 'description' => 'Direction Financière et Comptable'],
            'DSI' => ['name' => 'DSI', 'description' => 'Direction des Systèmes d\'Information'],
            'DCM' => ['name' => 'DCM', 'description' => 'Direction Commerciale et Marketing'],
            'DOL' => ['name' => 'DOL', 'description' => 'Direction des Opérations et Logistique'],
        ];

        $createdMains = [];
        foreach ($mainDepartments as $key => $deptData) {
            $createdMains[$key] = Department::firstOrCreate(
                ['name' => $deptData['name']],
                ['description' => $deptData['description'], 'parent_id' => null]
            );
        }

        $dgId = $createdMains['DG']->id;
        $drhId = $createdMains['DRH']->id;
        $dfcId = $createdMains['DFC']->id;
        $dsiId = $createdMains['DSI']->id;
        $dcmId = $createdMains['DCM']->id;
        $dolId = $createdMains['DOL']->id;

        // 2. Sous-départements / Services par Direction
        $subDepartments = [
            // Direction Générale
            ['name' => 'CAB-DG', 'description' => 'Cabinet de la Direction Générale', 'parent_id' => $dgId],
            ['name' => 'AUDIT', 'description' => 'Service Audit Interne & Conformité', 'parent_id' => $dgId],
            ['name' => 'JURIDIQUE', 'description' => 'Service Affaires Juridiques & Contentieux', 'parent_id' => $dgId],

            // Direction des Ressources Humaines
            ['name' => 'RECRUT', 'description' => 'Service Recrutement & GPEC', 'parent_id' => $drhId],
            ['name' => 'PAIE', 'description' => 'Service Paie & Administration du Personnel', 'parent_id' => $drhId],
            ['name' => 'FORM', 'description' => 'Service Formation & Développement des Compétences', 'parent_id' => $drhId],

            // Direction Financière et Comptable
            ['name' => 'COMPTA', 'description' => 'Service Comptabilité Générale & Trésorerie', 'parent_id' => $dfcId],
            ['name' => 'CTRL-GEST', 'description' => 'Service Contrôle de Gestion & Budget', 'parent_id' => $dfcId],
            ['name' => 'FACT', 'description' => 'Service Facturation & Recouvrement', 'parent_id' => $dfcId],

            // Direction des Systèmes d'Information
            ['name' => 'INFRA', 'description' => 'Service Infrastructures, Réseaux & Sécurité', 'parent_id' => $dsiId],
            ['name' => 'DEV', 'description' => 'Service Études & Développement Applicatif', 'parent_id' => $dsiId],
            ['name' => 'SUPPORT', 'description' => 'Service Support & Assistance Utilisateurs', 'parent_id' => $dsiId],

            // Direction Commerciale et Marketing
            ['name' => 'VENTES', 'description' => 'Service Commercial & Grands Comptes', 'parent_id' => $dcmId],
            ['name' => 'MKT-DIGITAL', 'description' => 'Service Communication & Marketing Digital', 'parent_id' => $dcmId],

            // Direction des Opérations et Logistique
            ['name' => 'ACHATS', 'description' => 'Service Achats & Approvisionnements', 'parent_id' => $dolId],
            ['name' => 'LOGISTIQUE', 'description' => 'Service Gestion des Stocks & Logistique', 'parent_id' => $dolId],
        ];

        foreach ($subDepartments as $sub) {
            Department::firstOrCreate(
                ['name' => $sub['name']],
                ['description' => $sub['description'], 'parent_id' => $sub['parent_id']]
            );
        }
    }
}
