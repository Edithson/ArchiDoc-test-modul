<?php

namespace App\Models;

use Database\Factories\PersonnelFilesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonnelFiles extends Model
{
    /** @use HasFactory<PersonnelFilesFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personnel_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pieces_id',
        'personnels_id',
        'file_paths',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_paths' => 'array',
    ];

    /**
     * Get the associated personnel.
     */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'personnels_id');
    }

    /**
     * Get the associated piece.
     */
    public function piece(): BelongsTo
    {
        return $this->belongsTo(Piece::class, 'pieces_id');
    }
}
