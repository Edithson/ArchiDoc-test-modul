<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use App\Models\Personnel;
use App\Models\PersonnelFiles;
use App\Models\Piece;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PersonnelController extends Controller
{
    /**
     * Display a listing of personnel records.
     */
    public function index(Request $request): View
    {
        $query = Personnel::with(['personnelFiles.piece']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $personnelsList = $query->orderBy('name', 'asc')->get();

        // Application du filtre de statut de complétude
        $statusFilter = $request->input('status');
        if ($statusFilter === 'complete') {
            $personnelsList = $personnelsList->filter(fn ($p) => $p->is_complete);
        } elseif ($statusFilter === 'incomplete') {
            $personnelsList = $personnelsList->filter(fn ($p) => ! $p->is_complete);
        }

        // Statistiques globales
        $totalPersonnel = Personnel::count();
        $allPersonnels = Personnel::with(['personnelFiles.piece'])->get();
        $completeCount = $allPersonnels->filter(fn ($p) => $p->is_complete)->count();
        $incompleteCount = $totalPersonnel - $completeCount;

        // Pagination manuelle après filtrage par accesseur
        $page = (int) $request->input('page', 1);
        $perPage = 12;
        $paginatedItems = new LengthAwarePaginator(
            $personnelsList->forPage($page, $perPage)->values(),
            $personnelsList->count(),
            $perPage,
            $page,
            ['path' => route('personnels.index'), 'query' => $request->query()]
        );

        return view('admin.pages.personnels.index', [
            'personnels' => $paginatedItems,
            'search' => $request->input('search'),
            'statusFilter' => $statusFilter,
            'totalPersonnel' => $totalPersonnel,
            'completeCount' => $completeCount,
            'incompleteCount' => $incompleteCount,
        ]);
    }

    /**
     * Show the form for creating a new personnel record with dossier files.
     */
    public function create(): View
    {
        $obligatoryPieces = Piece::where('obligatory', true)->orderBy('name')->get();
        $optionalPieces = Piece::where('obligatory', false)->orderBy('name')->get();

        return view('admin.pages.personnels.create', [
            'obligatoryPieces' => $obligatoryPieces,
            'optionalPieces' => $optionalPieces,
        ]);
    }

    /**
     * Store a newly created personnel record in storage with attached pieces.
     */
    public function store(StorePersonnelRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $personnel = Personnel::create([
            'name' => $validated['name'],
            'matricule' => strtoupper($validated['matricule']),
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Traitement des pièces jointes déposées
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $pieceId => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store("personnel_files/{$personnel->matricule}", 'public');
                    PersonnelFiles::create([
                        'personnels_id' => $personnel->id,
                        'pieces_id' => $pieceId,
                        'file_paths' => [$path],
                    ]);
                }
            }
        }

        return redirect()->route('personnels.show', $personnel)
            ->with('success', "Le dossier du personnel « {$personnel->name} » a été créé avec succès !");
    }

    /**
     * Display the specified personnel record and full dossier breakdown.
     */
    public function show(Personnel $personnel): View
    {
        $personnel->load(['personnelFiles.piece']);
        $obligatoryPieces = Piece::where('obligatory', true)->orderBy('name')->get();
        $optionalPieces = Piece::where('obligatory', false)->orderBy('name')->get();
        $uploadedFiles = $personnel->personnelFiles->keyBy('pieces_id');

        return view('admin.pages.personnels.show', [
            'personnel' => $personnel,
            'obligatoryPieces' => $obligatoryPieces,
            'optionalPieces' => $optionalPieces,
            'uploadedFiles' => $uploadedFiles,
        ]);
    }

    /**
     * Show the form for editing the specified personnel record.
     */
    public function edit(Personnel $personnel): View
    {
        $personnel->load(['personnelFiles.piece']);
        $obligatoryPieces = Piece::where('obligatory', true)->orderBy('name')->get();
        $optionalPieces = Piece::where('obligatory', false)->orderBy('name')->get();
        $uploadedFiles = $personnel->personnelFiles->keyBy('pieces_id');

        return view('admin.pages.personnels.edit', [
            'personnel' => $personnel,
            'obligatoryPieces' => $obligatoryPieces,
            'optionalPieces' => $optionalPieces,
            'uploadedFiles' => $uploadedFiles,
        ]);
    }

    /**
     * Update the specified personnel record in storage.
     */
    public function update(UpdatePersonnelRequest $request, Personnel $personnel): RedirectResponse
    {
        $validated = $request->validated();

        $personnel->update([
            'name' => $validated['name'],
            'matricule' => strtoupper($validated['matricule']),
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Mise à jour des pièces jointes
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $pieceId => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store("personnel_files/{$personnel->matricule}", 'public');

                    $existingRecord = PersonnelFiles::where('personnels_id', $personnel->id)
                        ->where('pieces_id', $pieceId)
                        ->first();

                    if ($existingRecord) {
                        $existingRecord->update(['file_paths' => [$path]]);
                    } else {
                        PersonnelFiles::create([
                            'personnels_id' => $personnel->id,
                            'pieces_id' => $pieceId,
                            'file_paths' => [$path],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('personnels.show', $personnel)
            ->with('success', "Le dossier de « {$personnel->name} » a été mis à jour avec succès !");
    }

    /**
     * Remove the specified personnel record from storage (Soft Delete).
     */
    public function destroy(Personnel $personnel): RedirectResponse
    {
        $name = $personnel->name;
        $personnel->delete();

        return redirect()->route('personnels.index')
            ->with('success', "Le dossier de l'agent « {$name} » a été supprimé.");
    }

    /**
     * Download the full dossier files of the specified personnel as a ZIP archive.
     */
    public function downloadZip(Personnel $personnel): BinaryFileResponse|RedirectResponse
    {
        $personnel->load(['personnelFiles.piece']);

        $filesToZip = [];

        foreach ($personnel->personnelFiles as $personnelFile) {
            if (empty($personnelFile->file_paths)) {
                continue;
            }

            $pieceName = $personnelFile->piece ? $personnelFile->piece->name : 'Piece';
            $sanitizedPieceName = Str::slug($pieceName, '_');

            foreach ($personnelFile->file_paths as $index => $relativePath) {
                if (Storage::disk('public')->exists($relativePath)) {
                    $absolutePath = Storage::disk('public')->path($relativePath);
                    $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
                    $extension = $extension ? ".{$extension}" : '';

                    $suffix = count($personnelFile->file_paths) > 1 ? '_'.($index + 1) : '';
                    $zipEntryName = "{$sanitizedPieceName}{$suffix}{$extension}";

                    $filesToZip[] = [
                        'path' => $absolutePath,
                        'name' => $zipEntryName,
                    ];
                }
            }
        }

        if (empty($filesToZip)) {
            return redirect()->back()
                ->with('error', "Aucun fichier n'a été téléversé pour le dossier de « {$personnel->name} ».");
        }

        $tempDir = storage_path('app/temp');
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipFileName = "Dossier_{$personnel->matricule}_".time().'.zip';
        $zipFilePath = "{$tempDir}/{$zipFileName}";

        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()
                ->with('error', "Impossible de créer l'archive ZIP.");
        }

        foreach ($filesToZip as $fileInfo) {
            $zip->addFile($fileInfo['path'], $fileInfo['name']);
        }

        $zip->close();

        $downloadName = "Dossier_{$personnel->matricule}_{$personnel->name}.zip";

        return response()->download($zipFilePath, $downloadName)->deleteFileAfterSend(true);
    }
}
