<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création des rôles
        $roles = [
            ['name' => 'Classic', 'description' => 'Utilisateur standard'],
            ['name' => 'Privilégié', 'description' => 'Administrateur du système'],
            ['name' => 'Super privilégié', 'description' => 'Super administrateur du système'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }

        // Création des départements
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

        // Création de l'utilisateur administrateur principal
        User::firstOrCreate(
            ['email' => 'admin@archidoc.cm'],
            [
                'name' => 'Admin ArchiDoc',
                'matricule' => 'MAT-0001',
                'phone' => '+237 699 00 00 01',
                'roles' => 'super privilégé',
                'statut' => true,
                'departement' => 'CAB DGB',
                'password' => 'password',
            ]
        );

        $this->call([
            ArchiveSeeder::class,
        ]);
    }
}
