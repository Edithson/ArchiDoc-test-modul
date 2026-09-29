<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\PieceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Piece extends Model
{
    /** @use HasFactory<PieceFactory> */
    use Auditable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'obligatory',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'obligatory' => 'boolean',
    ];

    /**
     * Files associated with this piece type across all personnel.
     */
    public function files(): HasMany
    {
        return $this->hasMany(PersonnelFiles::class, 'pieces_id');
    }
}
