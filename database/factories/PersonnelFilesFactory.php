<?php

namespace Database\Factories;

use App\Models\Personnel;
use App\Models\PersonnelFiles;
use App\Models\Piece;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonnelFiles>
 */
class PersonnelFilesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pieces_id' => Piece::factory(),
            'personnels_id' => Personnel::factory(),
            'file_paths' => ['personnel_files/demo_piece.pdf'],
        ];
    }
}
