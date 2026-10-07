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

        $createdRoles = [];
        foreach ($roles as $roleData) {
            $roleData['permissions'] = Role::defaultPermissionsFor($roleData['name']);
            $r = Role::updateOrCreate(['name' => $roleData['name']], $roleData);
            $createdRoles[$roleData['name']] = $r;
        }

        // Création des départements (Hiérarchie MINFI)
        $this->call(DepartmentSeeder::class);

        // Création de l'utilisateur administrateur principal
        $dgbMain = Department::where('name', 'DGB')->whereNull('parent_id')->first();
        $cabDept = Department::where('name', 'CAB DGB')->first();

        User::firstOrCreate(
            ['email' => 'admin@archidoc.cm'],
            [
                'name' => 'Admin ArchiDoc',
                'matricule' => 'MAT-0001',
                'phone' => '+237 699 00 00 01',
                'role_id' => $createdRoles['Super privilégié']->id,
                'statut' => true,
                'department_id' => $dgbMain?->id,
                'sub_department_id' => $cabDept?->id,
                'password' => 'c@rabine21',
            ]
        );

        // Création des types d'archives et emplacements
        $this->call([
            ArchiveTypeSeeder::class,
            ArchiveLocationSeeder::class,
            ArchiveSeeder::class,
            PieceSeeder::class,
            PersonnelSeeder::class,
            ActivityLogSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
