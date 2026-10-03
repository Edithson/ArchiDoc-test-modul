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

        Archive::factory(40)->make()->each(function ($archive) use ($mainDepartments, $archiveTypes, $user) {
            if ($mainDepartments->isNotEmpty()) {
                $main = $mainDepartments->random();
                $archive->department_id = $main->id;
                if ($main->children->isNotEmpty() && fake()->boolean(65)) {
                    $archive->sub_department_id = $main->children->random()->id;
                } else {
                    $archive->sub_department_id = null;
                }
            }
            $archive->archive_type_id = $archiveTypes->isNotEmpty() ? $archiveTypes->random()->id : null;
            $archive->user_id = $user?->id;
            $archive->save();
        });
    }
}
