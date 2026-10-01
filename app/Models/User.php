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
        'roles',
        'statut',
        'department_id',
        'avatar',
        'password',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Department associated with the user.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
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
        return strtolower((string) $this->roles) === 'super privilégé' || strtolower((string) $this->roles) === 'super';
    }

    public function isPrivileged(): bool
    {
        return $this->isSuper() || strtolower((string) $this->roles) === 'privilégié';
    }

    public function isClassique(): bool
    {
        return strtolower((string) $this->roles) === 'classique';
    }
}
