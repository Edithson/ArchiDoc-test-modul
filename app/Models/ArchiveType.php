<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\ArchiveTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArchiveType extends Model
{
    /** @use HasFactory<ArchiveTypeFactory> */
    use Auditable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'dua',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * User who created this archive type.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Archives belonging to this archive type.
     */
    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class, 'typearchive', 'name');
    }
}
