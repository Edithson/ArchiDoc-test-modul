<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Map of available models and their supported actions for the permission matrix.
     *
     * @var array<string, array<string, string>>
     */
    protected array $modelDefinitions = [
        'User' => [
            'label' => 'Comptes Utilisateurs',
            'icon' => 'users',
            'actions' => ['read' => 'Consulter les comptes', 'create' => 'Créer des comptes', 'update' => 'Modifier les comptes', 'delete' => 'Supprimer des comptes'],
        ],
        'Archive' => [
            'label' => 'Archives Numérisées',
            'icon' => 'archive',
            'actions' => ['read' => 'Consulter les archives', 'create' => 'Verser des archives', 'update' => 'Modifier les fiches', 'delete' => 'Supprimer les archives', 'download' => 'Télécharger les fichiers'],
        ],
        'ArchiveLocation' => [
            'label' => 'Emplacements Physiques & Virtuels',
            'icon' => 'location',
            'actions' => ['read' => 'Consulter les emplacements', 'create' => 'Créer des emplacements', 'update' => 'Modifier les emplacements', 'delete' => 'Supprimer des emplacements'],
        ],
        'ArchiveType' => [
            'label' => 'Types d\'Archives',
            'icon' => 'tag',
            'actions' => ['read' => 'Consulter les types', 'create' => 'Créer des types', 'update' => 'Modifier les types', 'delete' => 'Supprimer des types'],
        ],
        'Department' => [
            'label' => 'Groupes d\'Accès & Structures MINFI',
            'icon' => 'building',
            'actions' => ['read' => 'Consulter les structures', 'create' => 'Créer des structures', 'update' => 'Modifier les structures', 'delete' => 'Supprimer des structures'],
        ],
        'Personnel' => [
            'label' => 'Dossiers du Personnel',
            'icon' => 'user-check',
            'actions' => ['read' => 'Consulter les dossiers', 'create' => 'Créer des dossiers', 'update' => 'Modifier les dossiers', 'delete' => 'Supprimer des dossiers', 'zip_download' => 'Télécharger les archives ZIP'],
        ],
        'Piece' => [
            'label' => 'Référentiel des Pièces',
            'icon' => 'file-text',
            'actions' => ['read' => 'Consulter les pièces', 'create' => 'Créer des pièces', 'update' => 'Modifier les pièces', 'delete' => 'Supprimer des pièces'],
        ],
        'Role' => [
            'label' => 'Habilitations & Rôles',
            'icon' => 'shield',
            'actions' => ['read' => 'Consulter les rôles', 'create' => 'Créer des rôles', 'update' => 'Modifier les habilitations', 'delete' => 'Supprimer des rôles'],
        ],
        'Setting' => [
            'label' => 'Paramètres Système & Sécurité',
            'icon' => 'settings',
            'actions' => ['read' => 'Consulter les paramètres', 'update' => 'Modifier la configuration'],
        ],
    ];

    /**
     * Display a listing of all roles and their permission summary.
     */
    public function index(Request $request): View
    {
        abort_if(! $request->user()?->hasPermission('Role', 'read'), 403, 'Accès non autorisé à la consultation des rôles.');

        $roles = Role::orderBy('name', 'asc')->get();

        // Calculate assigned user counts per role and protection status
        $rolesWithStats = $roles->map(function ($role) {
            $userCount = User::where('role_id', $role->id)->count();

            return [
                'model' => $role,
                'user_count' => $userCount,
                'is_protected' => $role->isPrimary(),
            ];
        });

        return view('admin.pages.roles.index', [
            'roles' => $rolesWithStats,
            'modelDefinitions' => $this->modelDefinitions,
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        abort_if(! request()->user()?->hasPermission('Role', 'create'), 403, 'Accès non autorisé à la création de rôles.');

        return view('admin.pages.roles.create', [
            'modelDefinitions' => $this->modelDefinitions,
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        abort_if(! $request->user()?->hasPermission('Role', 'create'), 403, 'Accès non autorisé à la création de rôles.');

        $validated = $request->validated();

        $permissionsInput = $request->input('permissions', []);
        $formattedPermissions = $this->buildPermissionsMatrix($permissionsInput);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'permissions' => $formattedPermissions,
        ]);

        return redirect()->route('roles.index')
            ->with('success', "Le rôle « {$role->name} » et ses habilitations ont été créés avec succès.");
    }

    /**
     * Show the form for editing the specified role permissions.
     */
    public function edit(Role $role): View
    {
        abort_if(! request()->user()?->hasPermission('Role', 'update'), 403, 'Accès non autorisé à la modification des rôles.');

        return view('admin.pages.roles.edit', [
            'role' => $role,
            'modelDefinitions' => $this->modelDefinitions,
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        abort_if(! $request->user()?->hasPermission('Role', 'update'), 403, 'Accès non autorisé à la modification des rôles.');

        $validated = $request->validated();

        // Protect primary system role names from being altered
        $nameToUse = $role->isPrimary() ? $role->name : $validated['name'];

        $permissionsInput = $request->input('permissions', []);
        $formattedPermissions = $this->buildPermissionsMatrix($permissionsInput, $nameToUse);

        $role->update([
            'name' => $nameToUse,
            'description' => $validated['description'] ?? null,
            'permissions' => $formattedPermissions,
        ]);

        return redirect()->route('roles.index')
            ->with('success', "Les habilitations du rôle « {$nameToUse} » ont été mises à jour avec succès.");
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        abort_if(! request()->user()?->hasPermission('Role', 'delete'), 403, 'Accès non autorisé à la suppression des rôles.');

        if ($role->isPrimary()) {
            return redirect()->back()->with('error', "Le rôle système primaire « {$role->name} » est un rôle fondamental du système et ne peut en aucun cas être supprimé.");
        }

        $assignedUsersCount = User::where('role_id', $role->id)->count();

        if ($assignedUsersCount > 0) {
            return redirect()->back()->with('error', "Impossible de supprimer le rôle « {$role->name} » (ID: {$role->id}) car {$assignedUsersCount} utilisateur(s) détienne(nt) actuellement ce rôle. Réaffectez d'abord ces utilisateurs avant toute suppression.");
        }

        $name = $role->name;
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', "Le rôle personnalisé « {$name} » a été supprimé avec succès.");
    }

    /**
     * Format raw form permissions into complete boolean matrix for all 9 models.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, array<string, bool>>
     */
    protected function buildPermissionsMatrix(array $input, ?string $roleName = null): array
    {
        if ($roleName && str_contains(strtolower($roleName), 'super')) {
            return Role::defaultPermissionsFor('Super privilégé');
        }

        $matrix = [];

        foreach ($this->modelDefinitions as $modelKey => $def) {
            $matrix[$modelKey] = [];
            foreach (array_keys($def['actions']) as $action) {
                $matrix[$modelKey][$action] = isset($input[$modelKey][$action]) && (bool) $input[$modelKey][$action];
            }
        }

        return $matrix;
    }
}
