<?php

namespace Database\Seeders;

use App\Models\Personnel;
use App\Models\PersonnelFiles;
use App\Models\Piece;
use Illuminate\Database\Seeder;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personnels = [
            [
                'name' => 'FOUDA Joseph Armand',
                'email' => 'joseph.fouda@minfi.cm',
                'matricule' => 'MAT-78901',
                'phone' => '+237 699 12 34 56',
                'address' => 'Bastos, Yaoundé',
            ],
            [
                'name' => 'NGO BONGA Chantal',
                'email' => 'chantal.ngobonga@minfi.cm',
                'matricule' => 'MAT-45210',
                'phone' => '+237 677 89 01 23',
                'address' => 'Mvan, Yaoundé',
            ],
            [
                'name' => 'EBOLE Paulin Eric',
                'email' => 'paulin.ebole@minfi.cm',
                'matricule' => 'MAT-12589',
                'phone' => '+237 694 55 66 77',
                'address' => 'Biyem-Assi, Yaoundé',
            ],
        ];

        $obligatoryPieces = Piece::where('obligatory', true)->get();
        $optionalPieces = Piece::where('obligatory', false)->get();

        foreach ($personnels as $index => $pData) {
            $personnel = Personnel::firstOrCreate(
                ['matricule' => $pData['matricule']],
                $pData
            );

            // Pour le premier agent: dossier complet (100% pièces obligatoires + 2 optionnelles)
            if ($index === 0) {
                foreach ($obligatoryPieces as $piece) {
                    PersonnelFiles::firstOrCreate([
                        'pieces_id' => $piece->id,
                        'personnels_id' => $personnel->id,
                    ], [
                        'file_paths' => ["personnel_files/{$personnel->matricule}/{$piece->id}_demo.pdf"],
                    ]);
                }
                foreach ($optionalPieces->take(2) as $piece) {
                    PersonnelFiles::firstOrCreate([
                        'pieces_id' => $piece->id,
                        'personnels_id' => $personnel->id,
                    ], [
                        'file_paths' => ["personnel_files/{$personnel->matricule}/{$piece->id}_demo.pdf"],
                    ]);
                }
            }

            // Pour le deuxième agent: dossier partiellement complet (4 pièces sur 6 obligatoires)
            if ($index === 1) {
                foreach ($obligatoryPieces->take(4) as $piece) {
                    PersonnelFiles::firstOrCreate([
                        'pieces_id' => $piece->id,
                        'personnels_id' => $personnel->id,
                    ], [
                        'file_paths' => ["personnel_files/{$personnel->matricule}/{$piece->id}_demo.pdf"],
                    ]);
                }
            }

            // Pour le troisième agent: dossier incomplet (2 pièces sur 6 obligatoires)
            if ($index === 2) {
                foreach ($obligatoryPieces->take(2) as $piece) {
                    PersonnelFiles::firstOrCreate([
                        'pieces_id' => $piece->id,
                        'personnels_id' => $personnel->id,
                    ], [
                        'file_paths' => ["personnel_files/{$personnel->matricule}/{$piece->id}_demo.pdf"],
                    ]);
                }
            }
        }
    }
}
