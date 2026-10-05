<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Role extends Model
{
    /** @use HasFactory<RoleFactory> */
    use Auditable, HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'permissions',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    /**
     * Check if the role has a specific permission for a given model and action.
     */
    public function hasPermission(string $model, string $action): bool
    {
        return (bool) ($this->permissions[$model][$action] ?? false);
    }

    /**
     * Helper to return standard default permissions matrix per role.
     *
     * @return array<string, array<string, bool>>
     */
    public static function defaultPermissionsFor(string $roleName): array
    {
        $normalized = strtolower(trim($roleName));

        $models = [
            'User' => ['read', 'create', 'update', 'delete'],
            'Archive' => ['read', 'create', 'update', 'delete', 'download'],
            'ArchiveLocation' => ['read', 'create', 'update', 'delete'],
            'ArchiveType' => ['read', 'create', 'update', 'delete'],
            'Department' => ['read', 'create', 'update', 'delete'],
            'Personnel' => ['read', 'create', 'update', 'delete', 'zip_download'],
            'Piece' => ['read', 'create', 'update', 'delete'],
            'Role' => ['read', 'create', 'update', 'delete'],
            'Setting' => ['read', 'update'],
        ];

        if (str_contains($normalized, 'super')) {
            // Super privilégé: 100% true
            $result = [];
            foreach ($models as $m => $actions) {
                foreach ($actions as $act) {
                    $result[$m][$act] = true;
                }
            }

            return $result;
        }

        if (str_contains($normalized, 'privilég')) {
            // Privilégié (Admin Direction)
            return [
                'User' => ['read' => true, 'create' => true, 'update' => true, 'delete' => false],
                'Archive' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true, 'download' => true],
                'ArchiveLocation' => ['read' => true, 'create' => true, 'update' => true, 'delete' => false],
                'ArchiveType' => ['read' => true, 'create' => true, 'update' => true, 'delete' => false],
                'Department' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
                'Personnel' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true, 'zip_download' => true],
                'Piece' => ['read' => true, 'create' => true, 'update' => true, 'delete' => false],
                'Role' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
                'Setting' => ['read' => true, 'update' => false],
            ];
        }

        // Classic / Classique (Utilisateur standard)
        return [
            'User' => ['read' => false, 'create' => false, 'update' => false, 'delete' => false],
            'Archive' => ['read' => true, 'create' => true, 'update' => true, 'delete' => false, 'download' => true],
            'ArchiveLocation' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
            'ArchiveType' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
            'Department' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
            'Personnel' => ['read' => true, 'create' => true, 'update' => true, 'delete' => false, 'zip_download' => true],
            'Piece' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
            'Role' => ['read' => false, 'create' => false, 'update' => false, 'delete' => false],
            'Setting' => ['read' => false, 'update' => false],
        ];
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
