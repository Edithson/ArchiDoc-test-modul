<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of user accounts.
     */
    public function index(Request $request): View
    {
        $query = User::with(['department', 'subDepartment']);

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
                    });
            });
        }

        if ($request->filled('roles')) {
            $roleVal = (string) $request->input('roles');
            if (str_contains(strtolower($roleVal), 'super')) {
                $query->where('roles', 'like', '%super%');
            } elseif (str_contains(strtolower($roleVal), 'privilég') || str_contains(strtolower($roleVal), 'privileg')) {
                $query->where('roles', 'like', '%privil%')
                    ->where('roles', 'not like', '%super%');
            } elseif (str_contains(strtolower($roleVal), 'class')) {
                $query->where('roles', 'like', '%class%');
            } else {
                $query->where('roles', 'like', "%{$roleVal}%");
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

        $mainDepartments = Department::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.pages.users.index', [
            'users' => $users,
            'mainDepartments' => $mainDepartments,
            'departments' => Department::orderBy('name')->get(),
            'roleOptions' => $this->getRoleOptions(),
            'filters' => [
                'search' => (string) $request->input('search', ''),
                'roles' => (string) $request->input('roles', ''),
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
        $mainDepartments = Department::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.pages.users.create', [
            'mainDepartments' => $mainDepartments,
            'departments' => Department::orderBy('name')->get(),
            'roleOptions' => $this->getRoleOptions(),
        ]);
    }

    /**
     * Store a newly created user account in database.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('users.index')->with('success', "Le compte utilisateur « {$user->name} » (Matricule: {$user->matricule}) a été créé avec succès.");
    }

    /**
     * Show the form for editing the specified user account.
     */
    public function edit(User $user): View
    {
        $mainDepartments = Department::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('admin.pages.users.edit', [
            'user' => $user->load(['department', 'subDepartment']),
            'mainDepartments' => $mainDepartments,
            'departments' => Department::orderBy('name')->get(),
            'roleOptions' => $this->getRoleOptions(),
        ]);
    }

    /**
     * Update the specified user account.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if (filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', "Le compte utilisateur de « {$user->name} » a été mis à jour avec succès.");
    }

    /**
     * Toggle the specified user account active/suspended status.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
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
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "Le compte utilisateur « {$userName} » a été supprimé.");
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
