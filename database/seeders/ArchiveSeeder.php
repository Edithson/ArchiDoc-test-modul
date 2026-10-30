<?php

namespace Database\Seeders;

use App\Models\Archive;
use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArchiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainDepartments = Department::whereNull('parent_id')->with('children')->get();
        $archiveTypes = ArchiveType::all();
        $user = User::first();

        for ($i = 0; $i < 40; $i++) {
            $main = $mainDepartments->isNotEmpty() ? $mainDepartments->random() : null;
            $subDeptId = ($main && $main->children->isNotEmpty() && fake()->boolean(65))
                ? $main->children->random()->id
                : null;

            Archive::factory()->create([
                'department_id' => $main?->id,
                'sub_department_id' => $subDeptId,
                'archive_type_id' => $archiveTypes->isNotEmpty() ? $archiveTypes->random()->id : null,
                'user_id' => $user?->id,
            ]);
        }
    }
}
