<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchiveLocationRequest;
use App\Http\Requests\UpdateArchiveLocationRequest;
use App\Models\ArchiveLocation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArchiveLocationController extends Controller
{
    /**
     * Display a listing of archive locations.
     */
    public function index(Request $request): View
    {
        $query = ArchiveLocation::with('creator')->withCount('archives');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', (int) $request->input('type'));
        }

        $archiveLocations = $query->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.archive_locations.index', [
            'archiveLocations' => $archiveLocations,
            'search' => $request->input('search'),
            'typeFilter' => $request->input('type'),
        ]);
    }

    /**
     * Show the form for creating a new archive location.
     */
    public function create(): View
    {
        return view('admin.pages.archive_locations.create');
    }

    /**
     * Store a newly created archive location in storage.
     */
    public function store(StoreArchiveLocationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id() ?? 1;

        $location = ArchiveLocation::create($validated);

        return redirect()->route('archive-locations.index')
            ->with('success', "L'emplacement « {$location->name} » a été créé avec succès !");
    }

    /**
     * Display the specified archive location.
     */
    public function show(ArchiveLocation $archiveLocation): View
    {
        $archiveLocation->load(['creator', 'archives' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.pages.archive_locations.show', [
            'archiveLocation' => $archiveLocation,
        ]);
    }

    /**
     * Show the form for editing the specified archive location.
     */
    public function edit(ArchiveLocation $archiveLocation): View
    {
        return view('admin.pages.archive_locations.edit', [
            'archiveLocation' => $archiveLocation,
        ]);
    }

    /**
     * Update the specified archive location in storage.
     */
    public function update(UpdateArchiveLocationRequest $request, ArchiveLocation $archiveLocation): RedirectResponse
    {
        $archiveLocation->update($request->validated());

        return redirect()->route('archive-locations.index')
            ->with('success', "L'emplacement « {$archiveLocation->name} » a été mis à jour avec succès !");
    }

    /**
     * Remove the specified archive location from storage (Soft Delete).
     */
    public function destroy(ArchiveLocation $archiveLocation): RedirectResponse
    {
        $name = $archiveLocation->name;
        $archiveLocation->delete();

        return redirect()->route('archive-locations.index')
            ->with('success', "L'emplacement « {$name} » a été supprimé avec succès.");
    }
}
