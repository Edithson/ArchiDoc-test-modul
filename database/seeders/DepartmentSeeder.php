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
        $departments = [
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

        foreach ($departments as $department) {
            Department::firstOrCreate(['name' => $department['name']], $department);
        }
    }
}
