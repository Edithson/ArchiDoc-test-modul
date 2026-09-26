<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchiveRequest;
use App\Http\Requests\UpdateArchiveRequest;
use App\Models\Archive;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.index');
    }

    /**
     * Search archives based on multi-criteria filter, pagination and sorting.
     */
    public function search(Request $request): View
    {
        $query = Archive::query();

        if ($request->filled('typearchive')) {
            $query->where('typearchive', $request->input('typearchive'));
        }

        if ($request->filled('date_doc')) {
            $query->where('date_doc', 'like', '%'.$request->input('date_doc').'%');
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', '%'.$request->input('description').'%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $allowedSorts = ['typearchive', 'description', 'date_doc', 'created_at'];
        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        $sortOrder = strtolower((string) $request->input('sort_order', 'desc')) === 'asc' ? 'asc' : 'desc';

        $archives = $query->orderBy($sortBy, $sortOrder)
            ->paginate(10)
            ->withQueryString();

        return view('admin.pages.archives.search', [
            'archives' => $archives,
            'archiveTypes' => $this->getArchiveTypes(),
            'users' => User::all(['id', 'name']),
            'filters' => $request->only(['typearchive', 'date_doc', 'description', 'user_id', 'sort_by', 'sort_order']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.pages.archives.create', [
            'formats' => $this->getFormats(),
            'archiveTypes' => $this->getArchiveTypes(),
            'emplacementsPhysiques' => $this->getEmplacementsPhysiques(),
            'emplacementsVirtuels' => $this->getEmplacementsVirtuels(),
            'groupesAcces' => $this->getGroupesAcces(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArchiveRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('archives', 'public');
        }

        $archive = Archive::create([
            'typearchive' => $validated['typearchive'] ?? null,
            'description' => $validated['description'] ?? null,
            'date_doc' => $validated['date_doc'] ?? null,
            'emplacement' => $validated['emplacement'] ?? null,
            'emplacement2' => $validated['emplacement2'] ?? null,
            'rayon' => $validated['rayon'] ?? null,
            'travee' => $validated['travee'] ?? null,
            'cote' => $validated['cote'] ?? null,
            'format' => $validated['format'] ?? null,
            'departement' => $validated['departement'] ?? null,
            'filepath' => $filePath,
            'user_id' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => "L'archive « {$archive->description} » a été enregistrée avec succès !",
            'archive' => $archive,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Archive $archive): View
    {
        return view('admin.pages.archives.show', [
            'archive' => $archive,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Archive $archive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArchiveRequest $request, Archive $archive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Archive $archive)
    {
        //
    }

    /**
     * Get the list of document formats.
     *
     * @return array<int, string>
     */
    protected function getFormats(): array
    {
        return [
            'Document PDF',
            'Image',
            'Document Papier',
        ];
    }

    /**
     * Get the list of archive types.
     *
     * @return array<int, string>
     */
    protected function getArchiveTypes(): array
    {
        return [
            'ARRETE',
            'ATTESTATION',
            'AUTRES TYPES DE DOCUMENTS',
            "BONS D'ENGAGEMENT",
            'BORDEREAUX',
            "CARNETS D'ENGAGEMENT",
            'CERTIFICATS',
            'CIRCULAIRE',
            'COMMUNIQUES',
            'COMPTE ADMINISTRATIF',
            "COMPTE D'EMPLOI",
            'COMPTE-RENDU',
            'CONSTITUTION',
            'CONVOCATIONS',
            'COURRIERS',
            'DECISIONS',
            'DECRET',
            'ETATS DE SOMMES DUES',
            'FONDS DE DOSSIER',
            'INVITATIONS',
            'LETTRE CIRCULAIRE',
            'LETTRE DE MISSION',
            'LOI',
            'MEMO',
            'MEMOIRES DE DEPENSE',
            'MESSAGE-FAX',
            'MESSAGE-PORTE',
            'NOTE',
            'NOTE DE SERVICE',
            'ORDONNANCES',
            'PROCES-VERBAL',
            'SOIT-TRANSMIS',
        ];
    }

    /**
     * Get the list of physical locations.
     *
     * @return array<string, string>
     */
    protected function getEmplacementsPhysiques(): array
    {
        return [
            'FOUDA' => "FOUDA — Centre d'excellence DGB",
            'DGB' => 'DGB — Direction Générale du Budget',
            'IMPRIMERIE NATIONALE' => 'Imprimerie Nationale',
        ];
    }

    /**
     * Get the list of virtual locations.
     *
     * @return array<string, string>
     */
    protected function getEmplacementsVirtuels(): array
    {
        return [
            'Serveur' => 'Serveur',
        ];
    }

    /**
     * Get access groups.
     *
     * @return array<int, array{sigle: string, nom: string}>
     */
    protected function getGroupesAcces(): array
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
}
