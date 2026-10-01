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
        $departments = Department::all();
        $archiveTypes = ArchiveType::all();
        $user = User::first();

        Archive::factory(25)->make()->each(function ($archive) use ($departments, $archiveTypes, $user) {
            $archive->department_id = $departments->isNotEmpty() ? $departments->random()->id : null;
            $archive->archive_type_id = $archiveTypes->isNotEmpty() ? $archiveTypes->random()->id : null;
            $archive->user_id = $user?->id;
            $archive->save();
        });
    }
}
