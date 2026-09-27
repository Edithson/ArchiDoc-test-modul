<?php

namespace App\Models;

use Database\Factories\PersonnelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    /** @use HasFactory<PersonnelFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'matricule',
        'phone',
        'address',
    ];

    /**
     * Personnel files uploaded.
     */
    public function personnelFiles(): HasMany
    {
        return $this->hasMany(PersonnelFiles::class, 'personnels_id');
    }

    /**
     * Pieces attached to this personnel.
     */
    public function pieces(): BelongsToMany
    {
        return $this->belongsToMany(Piece::class, 'personnel_files', 'personnels_id', 'pieces_id')
            ->withPivot('file_paths')
            ->withTimestamps();
    }

    /**
     * Calculate completion percentage for obligatory integration pieces.
     */
    public function getTauxAchevementAttribute(): int
    {
        $totalObligatory = Piece::where('obligatory', true)->count();

        if ($totalObligatory === 0) {
            return 100;
        }

        $filledObligatoryCount = $this->personnelFiles()
            ->whereHas('piece', fn ($q) => $q->where('obligatory', true))
            ->whereNotNull('file_paths')
            ->count();

        return (int) round(($filledObligatoryCount / $totalObligatory) * 100);
    }

    /**
     * Count missing obligatory pieces.
     */
    public function getMissingObligatoryPiecesCountAttribute(): int
    {
        $totalObligatory = Piece::where('obligatory', true)->count();

        $filledObligatoryCount = $this->personnelFiles()
            ->whereHas('piece', fn ($q) => $q->where('obligatory', true))
            ->whereNotNull('file_paths')
            ->count();

        return max(0, $totalObligatory - $filledObligatoryCount);
    }

    /**
     * Check if the personnel dossier is complete.
     */
    public function getIsCompleteAttribute(): bool
    {
        return $this->missing_obligatory_pieces_count === 0;
    }
}
