<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

trait Auditable
{
    /**
     * Boot the trait and register Eloquent event listeners.
     */
    protected static function bootAuditable(): void
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                if (empty($model->created_by) && static::hasAuditColumn($model, 'created_by')) {
                    $model->created_by = auth()->id();
                }
                if (empty($model->updated_by) && static::hasAuditColumn($model, 'updated_by')) {
                    $model->updated_by = auth()->id();
                }
            }
        });

        static::updating(function ($model) {
            if (auth()->check() && static::hasAuditColumn($model, 'updated_by')) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function ($model) {
            if (auth()->check() && static::hasAuditColumn($model, 'deleted_by')) {
                if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                    $model->deleted_by = auth()->id();
                    $model->saveQuietly();
                }
            }
        });
    }

    /**
     * Check if model has a specific audit column fillable or in table schema.
     */
    protected static function hasAuditColumn($model, string $column): bool
    {
        return $model->isFillable($column) || Schema::hasColumn($model->getTable(), $column);
    }

    /**
     * User who created the record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * User who soft-deleted the record.
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
