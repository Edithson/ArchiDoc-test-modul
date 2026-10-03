<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Department extends Model
{
    /** @use HasFactory<DepartmentFactory> */
    use Auditable, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Parent department (e.g. Direction Générale MINFI).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Sub-departments belonging to this parent department.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    /**
     * Alias for children relationship.
     */
    public function subDepartments(): HasMany
    {
        return $this->children();
    }

    /**
     * Check if department is a main top-level MINFI department.
     */
    public function isMain(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if department is a sub-department.
     */
    public function isSub(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Users belonging to this department as Main Direction.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'department_id');
    }

    /**
     * Users belonging specifically to this department as Sub-Department.
     */
    public function subDepartmentUsers(): HasMany
    {
        return $this->hasMany(User::class, 'sub_department_id');
    }

    /**
     * Archives belonging to this department as Main Direction.
     */
    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class, 'department_id');
    }

    /**
     * Archives belonging specifically to this department as Sub-Department.
     */
    public function subDepartmentArchives(): HasMany
    {
        return $this->hasMany(Archive::class, 'sub_department_id');
    }

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
}
