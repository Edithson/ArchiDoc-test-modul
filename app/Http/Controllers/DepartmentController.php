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
        $query = Department::with(['parent', 'children'])->withCount('children');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('parent', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('type')) {
            $type = $request->input('type');
            if ($type === 'main') {
                $query->whereNull('parent_id');
            } elseif ($type === 'sub') {
                $query->whereNotNull('parent_id');
            }
        }

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->input('parent_id'));
        }

        $departments = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();
        $totalDepartments = Department::count();
        $mainCount = Department::whereNull('parent_id')->count();
        $subCount = Department::whereNotNull('parent_id')->count();
        $mainDepartments = Department::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.pages.departments.index', [
            'departments' => $departments,
            'search' => $request->input('search'),
            'typeFilter' => $request->input('type', 'all'),
            'parentIdFilter' => $request->input('parent_id', ''),
            'totalDepartments' => $totalDepartments,
            'mainCount' => $mainCount,
            'subCount' => $subCount,
            'mainDepartments' => $mainDepartments,
        ]);
    }

    /**
     * Show the form for creating a new department.
     */
    public function create(): View
    {
        $parentDepartments = Department::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.pages.departments.create', [
            'parentDepartments' => $parentDepartments,
        ]);
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
        $parentDepartments = Department::whereNull('parent_id')
            ->where('id', '!=', $department->id)
            ->orderBy('name')
            ->get();

        return view('admin.pages.departments.edit', [
            'department' => $department,
            'parentDepartments' => $parentDepartments,
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
