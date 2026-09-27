<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchiveTypeRequest;
use App\Http\Requests\UpdateArchiveTypeRequest;
use App\Models\ArchiveType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArchiveTypeController extends Controller
{
    /**
     * Display a listing of archive types.
     */
    public function index(Request $request): View
    {
        $query = ArchiveType::with('creator')->withCount('archives');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $archiveTypes = $query->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.archive_types.index', [
            'archiveTypes' => $archiveTypes,
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Show the form for creating a new archive type.
     */
    public function create(): View
    {
        return view('admin.pages.archive_types.create');
    }

    /**
     * Store a newly created archive type in storage.
     */
    public function store(StoreArchiveTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id() ?? 1;

        $archiveType = ArchiveType::create($validated);

        return redirect()->route('archive-types.index')
            ->with('success', "Le type d'archive « {$archiveType->name} » a été créé avec succès !");
    }

    /**
     * Display the specified archive type.
     */
    public function show(ArchiveType $archiveType): View
    {
        $archiveType->load(['creator', 'archives' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.pages.archive_types.show', [
            'archiveType' => $archiveType,
        ]);
    }

    /**
     * Show the form for editing the specified archive type.
     */
    public function edit(ArchiveType $archiveType): View
    {
        return view('admin.pages.archive_types.edit', [
            'archiveType' => $archiveType,
        ]);
    }

    /**
     * Update the specified archive type in storage.
     */
    public function update(UpdateArchiveTypeRequest $request, ArchiveType $archiveType): RedirectResponse
    {
        $archiveType->update($request->validated());

        return redirect()->route('archive-types.index')
            ->with('success', "Le type d'archive « {$archiveType->name} » a été mis à jour avec succès !");
    }

    /**
     * Remove the specified archive type from storage (Soft Delete).
     */
    public function destroy(ArchiveType $archiveType): RedirectResponse
    {
        $name = $archiveType->name;
        $archiveType->delete();

        return redirect()->route('archive-types.index')
            ->with('success', "Le type d'archive « {$name} » a été supprimé avec succès.");
    }
}
