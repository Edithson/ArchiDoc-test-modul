<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Auditable, HasFactory, LogsActivity, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'matricule',
        'email',
        'phone',
        'role_id',
        'roles',
        'custom_permissions',
        'statut',
        'department_id',
        'sub_department_id',
        'avatar',
        'password',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Accessor for legacy 'roles' attribute.
     */
    public function getRolesAttribute(): ?string
    {
        return $this->role?->name;
    }

    /**
     * Mutator for legacy 'roles' attribute to set role_id.
     */
    public function setRolesAttribute($value): void
    {
        if (is_numeric($value)) {
            $this->attributes['role_id'] = (int) $value;

            return;
        }

        if (is_string($value) && filled($value)) {
            $roleObj = Role::findByName($value);

            if (! $roleObj) {
                $normalized = strtolower(trim($value));
                $roleName = 'Classic';
                if (str_contains($normalized, 'super')) {
                    $roleName = 'Super privilégié';
                } elseif (str_contains($normalized, 'privilég') || str_contains($normalized, 'privileg')) {
                    $roleName = 'Privilégié';
                }

                $roleObj = Role::create([
                    'name' => $roleName,
                    'description' => "Rôle {$roleName}",
                    'permissions' => Role::defaultPermissionsFor($roleName),
                ]);
            }

            $this->attributes['role_id'] = $roleObj->id;
        }
    }

    /**
     * Main Department associated with the user.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Sub-Department / Service associated with the user (optional).
     */
    public function subDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'sub_department_id');
    }

    /**
     * Get the user's role record.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Alias for role relationship.
     */
    public function roleModel(): BelongsTo
    {
        return $this->role();
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'statut' => 'boolean',
            'custom_permissions' => 'array',
        ];
    }

    /**
     * Check if user is active and allowed to connect.
     */
    public function isActive(): bool
    {
        return (bool) $this->statut;
    }

    /**
     * Role helper checks.
     */
    public function isSuper(): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->isPrimary() && str_contains(strtolower($this->role->name), 'super');
    }

    public function isPrivileged(): bool
    {
        if ($this->isSuper()) {
            return true;
        }

        if (! $this->role) {
            return false;
        }

        return str_contains(strtolower($this->role->name), 'privilég') || str_contains(strtolower($this->role->name), 'privileg');
    }

    public function isClassique(): bool
    {
        if (! $this->role) {
            return false;
        }

        return str_contains(strtolower($this->role->name), 'classic') || str_contains(strtolower($this->role->name), 'classique');
    }

    /**
     * Runtime cache array for resolved permissions during the request lifecycle.
     *
     * @var array<string, bool>
     */
    protected array $resolvedPermissions = [];

    /**
     * Tracked system permission version for in-memory cache reactivity.
     */
    protected ?int $resolvedPermVersion = null;

    /**
     * Model booted lifecycle hooks for cache invalidation.
     */
    protected static function booted(): void
    {
        static::saved(function (User $user) {
            $user->flushPermissionCache();
            cache()->increment('sys_permission_ver');
        });

        static::deleted(function (User $user) {
            $user->flushPermissionCache();
            cache()->increment('sys_permission_ver');
        });
    }

    /**
     * Check functional permission for a model and action.
     * Explicitly evaluates roles & custom permissions for ALL users (including Super Privileged).
     * Performance: O(1) in-memory runtime memoization + dynamic cache invalidation on DB changes.
     */
    public function hasPermission(string $model, string $action): bool
    {
        $currentSysVer = (int) cache()->get('sys_permission_ver', 1);

        if ($this->resolvedPermVersion !== $currentSysVer) {
            $this->resolvedPermissions = [];
            $this->resolvedPermVersion = $currentSysVer;
        }

        $cacheKey = "{$model}:{$action}";

        if (array_key_exists($cacheKey, $this->resolvedPermissions)) {
            return $this->resolvedPermissions[$cacheKey];
        }

        // 1. Specific custom permission override on the user
        if (isset($this->custom_permissions[$model][$action])) {
            return $this->resolvedPermissions[$cacheKey] = (bool) $this->custom_permissions[$model][$action];
        }

        // 2. Fallback to Role default JSON permissions
        if ($this->role && is_array($this->role->permissions)) {
            if (isset($this->role->permissions[$model][$action])) {
                return $this->resolvedPermissions[$cacheKey] = (bool) $this->role->permissions[$model][$action];
            }
        }

        return $this->resolvedPermissions[$cacheKey] = false;
    }

    /**
     * Flush memory cache for this user instance.
     */
    public function flushPermissionCache(): static
    {
        $this->resolvedPermissions = [];
        $this->resolvedPermVersion = null;

        return $this;
    }

    /**
     * Check if the user has custom permission overrides active.
     */
    public function hasCustomPermissionOverrides(): bool
    {
        return ! empty($this->custom_permissions) && is_array($this->custom_permissions);
    }

    /**
     * Get total count of active custom permission overrides for the user.
     */
    public function customPermissionsCount(): int
    {
        if (! $this->hasCustomPermissionOverrides()) {
            return 0;
        }

        $count = 0;
        foreach ($this->custom_permissions as $actions) {
            if (is_array($actions)) {
                $count += count($actions);
            }
        }

        return $count;
    }
}
