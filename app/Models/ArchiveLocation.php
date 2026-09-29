<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\ArchiveLocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ArchiveLocation extends Model
{
    /** @use HasFactory<ArchiveLocationFactory> */
    use Auditable, HasFactory, LogsActivity, SoftDeletes;

    public const TYPE_PHYSICAL = 1;

    public const TYPE_VIRTUAL = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'location',
        'type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Options for activity logging.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /**
     * Get the human-readable label for location type.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === self::TYPE_VIRTUAL ? 'Virtuel (Serveur / Cloud)' : 'Physique (Magasin / Site)';
    }

    /**
     * Check if location is physical.
     */
    public function isPhysical(): bool
    {
        return $this->type === self::TYPE_PHYSICAL;
    }

    /**
     * Check if location is virtual.
     */
    public function isVirtual(): bool
    {
        return $this->type === self::TYPE_VIRTUAL;
    }

    /**
     * User who created this location.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Archives associated with this location.
     */
    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class, 'emplacement', 'name');
    }
}
