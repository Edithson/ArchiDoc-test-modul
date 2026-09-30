<?php

namespace App\Models;

use App\Services\SettingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'is_public',
        'updated_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'value' => 'json',
    ];

    /**
     * Boot model events to clear setting cache upon any mutation.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            app(SettingService::class)->clearCache();
        });

        static::deleted(function () {
            app(SettingService::class)->clearCache();
        });
    }

    /**
     * Relation vers l'utilisateur qui a mis à jour ce paramètre.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
