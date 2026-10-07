<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Départements Principaux (Directions Générales MINFI)
        $mainDepartments = [
            'DGB' => ['name' => 'DGB', 'description' => 'Direction Générale du Budget — MINFI'],
            'DGI' => ['name' => 'DGI', 'description' => 'Direction Générale des Impôts — MINFI'],
            'DGD' => ['name' => 'DGD', 'description' => 'Direction Générale des Douanes — MINFI'],
            'DGTCFM' => ['name' => 'DGTCFM', 'description' => 'Direction Générale du Trésor, de la Coopération Financière et Monétaire — MINFI'],
            'SG' => ['name' => 'SG', 'description' => 'Secrétariat Général du Ministère des Finances'],
            'CAB MINFI' => ['name' => 'CAB MINFI', 'description' => 'Cabinet du Ministre des Finances'],
        ];

        $createdMains = [];
        foreach ($mainDepartments as $key => $deptData) {
            $createdMains[$key] = Department::firstOrCreate(
                ['name' => $deptData['name']],
                ['description' => $deptData['description'], 'parent_id' => null]
            );
        }

        $dgbId = $createdMains['DGB']->id;
        $dgiId = $createdMains['DGI']->id;
        $dgdId = $createdMains['DGD']->id;
        $dgtcfmId = $createdMains['DGTCFM']->id;

        // 2. Sous-départements rattachés à la DGB
        $dgbSubDepartments = [
            ['name' => 'CAB DGB', 'description' => 'Cabinet DGB'],
            ['name' => 'DCOB', 'description' => "Division du Contrôle Budgétaire, de l'Audit et de la Qualité de la Dépense"],
            ['name' => 'DDPP', 'description' => 'Direction de la Dépense du Personnel et des Pensions'],
            ['name' => 'DI', 'description' => 'Division Informatique'],
            ['name' => 'DPB', 'description' => 'Division de la Préparation du Budget'],
            ['name' => 'DPC', 'description' => 'Division de Participation et Contribution'],
            ['name' => 'DREF', 'description' => 'Division de la Réforme Budgétaire'],
            ['name' => 'PUBLIC', 'description' => 'Public'],
            ['name' => 'S-DAG', 'description' => 'Sous-Direction des Affaires Générales'],
            ['name' => 'S-DCF', 'description' => 'Sous-Direction du Contrôle Financier'],
            ['name' => 'SGCCC', 'description' => 'Service de Gestion des Crédits des Chapitres Communs'],
            ['name' => 'SGDB', 'description' => 'Service de Gestion des Documents Budgétaires'],
            ['name' => 'SO', 'description' => "Service d'Ordre"],
        ];

        foreach ($dgbSubDepartments as $sub) {
            Department::firstOrCreate(
                ['name' => $sub['name']],
                ['description' => $sub['description'], 'parent_id' => $dgbId]
            );
        }

        // 3. Sous-départements rattachés aux autres Directions Générales
        $otherSubDepartments = [
            ['name' => 'DGE', 'description' => 'Direction des Grandes Entreprises (DGI)', 'parent_id' => $dgiId],
            ['name' => 'DV', 'description' => 'Division des Vérifications (DGI)', 'parent_id' => $dgiId],
            ['name' => 'DED', 'description' => 'Direction des Enquêtes Douanières (DGD)', 'parent_id' => $dgdId],
            ['name' => 'DCP', 'description' => 'Direction de la Comptabilité Publique (DGTCFM)', 'parent_id' => $dgtcfmId],
        ];

        foreach ($otherSubDepartments as $sub) {
            Department::firstOrCreate(
                ['name' => $sub['name']],
                ['description' => $sub['description'], 'parent_id' => $sub['parent_id']]
            );
        }
    }
}
