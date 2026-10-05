<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Map of available models and their supported actions for the permission matrix.
     *
     * @var array<string, array<string, mixed>>
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
     * Display a listing of user accounts.
     */
    public function index(Request $request): View
    {
        abort_if(! $request->user()?->hasPermission('User', 'read'), 403, 'Accès non autorisé à la gestion des utilisateurs.');

        $currentUser = $request->user();
        $query = User::with(['department', 'subDepartment', 'role']);

        if ($currentUser && ! $currentUser->isSuper()) {
            if ($currentUser->department_id) {
                $query->where('department_id', $currentUser->department_id);
            }
            if ($currentUser->sub_department_id) {
                $query->where('sub_department_id', $currentUser->sub_department_id);
            }
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($dq) use ($search) {
                        $dq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subDepartment', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('role', function ($rq) use ($search) {
                        $rq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->input('role_id'));
        } elseif ($request->filled('roles')) {
            $roleVal = (string) $request->input('roles');
            if (is_numeric($roleVal)) {
                $query->where('role_id', $roleVal);
            } else {
                $roleObj = Role::findByName($roleVal);
                if ($roleObj) {
                    $query->where('role_id', $roleObj->id);
                }
            }
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('sub_department_id')) {
            $query->where('sub_department_id', $request->input('sub_department_id'));
        }

        if ($request->has('statut') && $request->input('statut') !== null && $request->input('statut') !== '') {
            $statutVal = (string) $request->input('statut');
            if ($statutVal === '1' || $statutVal === 'true') {
                $query->where('statut', true);
            } elseif ($statutVal === '0' || $statutVal === 'false') {
                $query->where('statut', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $mainDeptQuery = Department::whereNull('parent_id')->with('children')->orderBy('name');
        if ($currentUser && ! $currentUser->isSuper() && $currentUser->department_id) {
            $mainDeptQuery->where('id', $currentUser->department_id);
        }
        $mainDepartments = $mainDeptQuery->get();

        $rolesQuery = Role::orderBy('name');
        if ($currentUser && ! $currentUser->isSuper()) {
            $rolesQuery->where('name', 'not like', '%Super%');
        }
        $rolesList = $rolesQuery->get();

        return view('admin.pages.users.index', [
            'users' => $users,
            'mainDepartments' => $mainDepartments,
            'departments' => Department::orderBy('name')->get(),
            'rolesList' => $rolesList,
            'roleOptions' => $rolesList,
            'filters' => [
                'search' => (string) $request->input('search', ''),
                'role_id' => (string) $request->input('role_id', ''),
                'department_id' => (string) $request->input('department_id', ''),
                'sub_department_id' => (string) $request->input('sub_department_id', ''),
                'statut' => (string) $request->input('statut', ''),
            ],
        ]);
    }

    /**
     * Show the form for creating a new user account.
     */
    public function create(): View
    {
        $currentUser = request()->user();
        abort_if(! $currentUser?->hasPermission('User', 'create'), 403, 'Accès non autorisé à la création de comptes utilisateurs.');

        $mainDeptQuery = Department::whereNull('parent_id')->with('children')->orderBy('name');
        if ($currentUser && ! $currentUser->isSuper() && $currentUser->department_id) {
            $mainDeptQuery->where('id', $currentUser->department_id);
        }
        $mainDepartments = $mainDeptQuery->get();

        $rolesQuery = Role::orderBy('name');
        if ($currentUser && ! $currentUser->isSuper()) {
            $rolesQuery->where('name', 'not like', '%Super%');
        }
        $rolesList = $rolesQuery->get();

        return view('admin.pages.users.create', [
            'mainDepartments' => $mainDepartments,
            'departments' => Department::orderBy('name')->get(),
            'rolesList' => $rolesList,
            'roleOptions' => $rolesList,
        ]);
    }

    /**
     * Store a newly created user account in database.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        abort_if(! $request->user()?->hasPermission('User', 'create'), 403, 'Accès non autorisé à la création de comptes utilisateurs.');

        $validated = $request->validated();

        $currentUser = $request->user();
        if ($currentUser && ! $currentUser->isSuper()) {
            if ($currentUser->department_id) {
                $validated['department_id'] = $currentUser->department_id;
            }
            if ($currentUser->sub_department_id) {
                $validated['sub_department_id'] = $currentUser->sub_department_id;
            }
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('users.index')->with('success', "Le compte utilisateur « {$user->name} » (Matricule: {$user->matricule}) a été créé avec succès.");
    }

    /**
     * Show the form for editing the specified user account.
     */
    public function edit(User $user): View
    {
        $currentUser = request()->user();
        abort_if(! $currentUser?->hasPermission('User', 'update'), 403, 'Accès non autorisé à la modification des comptes utilisateurs.');

        $this->checkStructuralScope($user);

        $mainDeptQuery = Department::whereNull('parent_id')->with('children')->orderBy('name');
        if ($currentUser && ! $currentUser->isSuper() && $currentUser->department_id) {
            $mainDeptQuery->where('id', $currentUser->department_id);
        }
        $mainDepartments = $mainDeptQuery->get();

        $rolesQuery = Role::orderBy('name');
        if ($currentUser && ! $currentUser->isSuper()) {
            $rolesQuery->where('name', 'not like', '%Super%');
        }
        $rolesList = $rolesQuery->get();

        return view('admin.pages.users.edit', [
            'user' => $user->load(['department', 'subDepartment', 'role']),
            'mainDepartments' => $mainDepartments,
            'departments' => Department::orderBy('name')->get(),
            'rolesList' => $rolesList,
            'roleOptions' => $rolesList,
            'modelDefinitions' => $this->modelDefinitions,
        ]);
    }

    /**
     * Update the specified user account.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $currentUser = $request->user();
        abort_if(! $currentUser?->hasPermission('User', 'update'), 403, 'Accès non autorisé à la modification des comptes utilisateurs.');

        $this->checkStructuralScope($user);

        $validated = $request->validated();

        if ($currentUser && ! $currentUser->isSuper()) {
            if ($currentUser->department_id) {
                $validated['department_id'] = $currentUser->department_id;
            }
            if ($currentUser->sub_department_id) {
                $validated['sub_department_id'] = $currentUser->sub_department_id;
            }
        }

        if (filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Process custom_permissions input matrix (Delta-only logic)
        if ($request->has('custom_permissions')) {
            $inputPermissions = $request->input('custom_permissions', []);
            $roleId = $validated['role_id'] ?? $user->role_id;
            $role = Role::find($roleId);
            $rolePermissions = $role ? ($role->permissions ?? []) : [];

            $deltas = [];

            if (is_array($inputPermissions)) {
                foreach ($this->modelDefinitions as $modelKey => $def) {
                    foreach (array_keys($def['actions']) as $action) {
                        if (! isset($inputPermissions[$modelKey][$action])) {
                            continue;
                        }

                        $val = $inputPermissions[$modelKey][$action];

                        if ($val === 'inherit' || $val === null || $val === '') {
                            continue;
                        }

                        $requestedBool = null;
                        if ($val === '1' || $val === 1 || $val === true || $val === 'true') {
                            $requestedBool = true;
                        } elseif ($val === '0' || $val === 0 || $val === false || $val === 'false') {
                            $requestedBool = false;
                        }

                        if ($requestedBool === null) {
                            continue;
                        }

                        $roleDefaultBool = (bool) ($rolePermissions[$modelKey][$action] ?? false);

                        if ($requestedBool !== $roleDefaultBool) {
                            $deltas[$modelKey][$action] = $requestedBool;
                        }
                    }
                }
            }

            $validated['custom_permissions'] = ! empty($deltas) ? $deltas : null;
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', "Le compte utilisateur de « {$user->name} » a été mis à jour avec succès.");
    }

    /**
     * Revoke all custom permission overrides for a user, restoring 100% role inheritance.
     */
    public function revokeCustomPermissions(User $user): RedirectResponse
    {
        abort_if(! request()->user()?->hasPermission('User', 'update'), 403, 'Accès non autorisé à la révoquation des autorisations.');

        $this->checkStructuralScope($user);

        $user->update(['custom_permissions' => null]);

        return back()->with('success', "Les autorisations personnalisées de « {$user->name} » ont été entièrement révoquées. L'utilisateur réhérite désormais à 100% des droits de son rôle.");
    }

    /**
     * Toggle the specified user account active/suspended status.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        abort_if(! request()->user()?->hasPermission('User', 'update'), 403, 'Accès non autorisé à la modification du statut du compte.');

        $this->checkStructuralScope($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte connecté.');
        }

        $user->statut = ! $user->statut;
        $user->save();

        $statusLabel = $user->statut ? 'réactivé' : 'suspendu';

        return back()->with('success', "Le compte de « {$user->name} » a été {$statusLabel} avec succès.");
    }

    /**
     * Soft delete the specified user account.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if(! request()->user()?->hasPermission('User', 'delete'), 403, 'Accès non autorisé à la suppression de comptes utilisateurs.');

        $this->checkStructuralScope($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "Le compte utilisateur « {$userName} » a été supprimé.");
    }

    /**
     * Ensure the target user falls within the structural scope of the authenticated user.
     */
    protected function checkStructuralScope(User $targetUser): void
    {
        $currentUser = auth()->user();

        if (! $currentUser || $currentUser->isSuper()) {
            return;
        }

        if ((int) $targetUser->department_id !== (int) $currentUser->department_id) {
            abort(403, 'Accès non autorisé : Cet utilisateur appartient à une autre Direction Principale.');
        }

        if ($currentUser->sub_department_id && (int) $targetUser->sub_department_id !== (int) $currentUser->sub_department_id) {
            abort(403, 'Accès non autorisé : Cet utilisateur appartient à un autre service / sous-département.');
        }
    }

    /**
     * Available departments list for select options.
     *
     * @return array<int, array{sigle: string, nom: string}>
     */
    protected function getDepartmentsList(): array
    {
        return [
            ['sigle' => 'CAB DGB', 'nom' => 'Cabinet DGB'],
            ['sigle' => 'DCOB', 'nom' => "Division du Contrôle Budgétaire, de l'Audit et de la Qualité de la Dépense"],
            ['sigle' => 'DDPP', 'nom' => 'Direction de la Dépense du Personnel et des Pensions'],
            ['sigle' => 'DI', 'nom' => 'Division Informatique'],
            ['sigle' => 'DPB', 'nom' => 'Division de la Préparation du Budget'],
            ['sigle' => 'DPC', 'nom' => 'Division de Participation et Contribution'],
            ['sigle' => 'DREF', 'nom' => 'Division de la Réforme Budgétaire'],
            ['sigle' => 'PUBLIC', 'nom' => 'Public'],
            ['sigle' => 'S-DAG', 'nom' => 'Sous-Direction des Affaires Générales'],
            ['sigle' => 'S-DCF', 'nom' => 'Sous-Direction du Contrôle Financier'],
            ['sigle' => 'SGCCC', 'nom' => 'Service de Gestion des Crédits des Chapitres Communs'],
            ['sigle' => 'SGDB', 'nom' => 'Service de Gestion des Documents Budgétaires'],
            ['sigle' => 'SO', 'nom' => "Service d'Ordre"],
        ];
    }

    /**
     * Role options.
     *
     * @return array<string, string>
     */
    protected function getRoleOptions(): array
    {
        return [
            'classique' => 'Classique — Utilisateur standard',
            'privilégié' => 'Privilégié — Gestionnaire d\'archives',
            'super privilégé' => 'Super Privilégié — Administrateur système',
        ];
    }
}
