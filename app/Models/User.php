<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

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
        'departement',
        'avatar',
        'password',
    ];

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
