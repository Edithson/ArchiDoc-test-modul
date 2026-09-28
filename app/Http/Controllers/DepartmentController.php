<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments (Groupes d'accès).
     */
    public function index(Request $request): View
    {
        $query = Department::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $departments = $query->orderBy('name', 'asc')->paginate(12)->withQueryString();
        $totalDepartments = Department::count();

        return view('admin.pages.departments.index', [
            'departments' => $departments,
            'search' => $request->input('search'),
            'totalDepartments' => $totalDepartments,
        ]);
    }

    /**
     * Show the form for creating a new department.
     */
    public function create(): View
    {
        return view('admin.pages.departments.create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = Department::create($request->validated());

        return redirect()->route('departments.index')
            ->with('success', "Le département « {$department->name} » a été créé avec succès !");
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department): RedirectResponse
    {
        return redirect()->route('departments.edit', $department);
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department): View
    {
        return view('admin.pages.departments.edit', [
            'department' => $department,
        ]);
    }

    /**
     * Update the specified department in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()->route('departments.index')
            ->with('success', "Le département « {$department->name} » a été mis à jour avec succès !");
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        $name = $department->name;
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', "Le département « {$name} » a été supprimé avec succès !");
    }
}
