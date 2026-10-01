<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchiveRequest;
use App\Http\Requests\UpdateArchiveRequest;
use App\Models\Archive;
use App\Models\ArchiveLocation;
use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource (Main Dynamic Dashboard).
     */
    public function index(): View
    {
        // Statistiques globales dynamiques
        $totalArchives = Archive::count();
        $totalPersonnel = Personnel::count();
        $totalConsultations = Activity::whereIn('event', [
            'archive.consultation',
            'archive.download',
            'personnel.consultation',
            'personnel.download',
        ])->count();
        $totalUsers = User::where('statut', true)->count();

        $archiveTypesCount = ArchiveType::count();
        $locationsCount = ArchiveLocation::count();
        $departmentsCount = Department::count();

        // Récentes archives numérisées
        $recentArchives = Archive::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Récents dossiers agents personnel
        $recentPersonnels = Personnel::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Flux en direct de la Boîte Noire
        $recentActivities = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Tendance sur les 14 derniers jours pour le graphique
        $dailyTrend = [
            'labels' => [],
            'archives' => [],
            'personnel' => [],
        ];

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d/m');

            $dailyTrend['labels'][] = $label;
            $dailyTrend['archives'][] = Activity::whereIn('event', ['archive.consultation', 'archive.download'])
                ->whereDate('created_at', $date)
                ->count();
            $dailyTrend['personnel'][] = Activity::whereIn('event', ['personnel.consultation', 'personnel.download'])
                ->whereDate('created_at', $date)
                ->count();
        }

        // Répartition des archives par département
        $archivesByDept = Archive::selectRaw('departement, COUNT(*) as count')
            ->whereNotNull('departement')
            ->groupBy('departement')
            ->orderByDesc('count')
            ->take(5)
            ->pluck('count', 'departement')
            ->toArray();

        return view('admin.index', [
            'totalArchives' => $totalArchives,
            'totalPersonnel' => $totalPersonnel,
            'totalConsultations' => $totalConsultations,
            'totalUsers' => $totalUsers,
            'archiveTypesCount' => $archiveTypesCount,
            'locationsCount' => $locationsCount,
            'departmentsCount' => $departmentsCount,
            'recentArchives' => $recentArchives,
            'recentPersonnels' => $recentPersonnels,
            'recentActivities' => $recentActivities,
            'dailyTrend' => $dailyTrend,
            'archivesByDept' => $archivesByDept,
        ]);
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
            'user_id' => auth()->id() ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => "L'archive « {$archive->description} » a été enregistrée avec succès !",
            'archive' => $archive,
        ], 201);
    }

    /**
     * Display the specified resource and log consultation without latency.
     */
    public function show(Archive $archive): View
    {
        $archive->load(['user', 'creator', 'updater']);
        $user = auth()->user();
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        // Enregistrement asynchrone ultra-rapide sans latence HTTP (defer)
        defer(function () use ($archive, $user, $ip, $userAgent) {
            activity('archives')
                ->performedOn($archive)
                ->causedBy($user)
                ->event('archive.consultation')
                ->withProperties([
                    'archive_id' => $archive->id,
                    'typearchive' => $archive->typearchive,
                    'description' => $archive->description,
                    'format' => $archive->format,
                    'departement' => $archive->departement,
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                ])
                ->log("Consultation de l'archive N°{$archive->id} (« {$archive->description} ») par ".($user->name ?? 'Utilisateur'));
        });

        // Compteurs et historique d'activités pour cette archive
        $activityQuery = Activity::forSubject($archive);

        $stats = [
            'consultations' => (clone $activityQuery)->where('event', 'like', '%consultation%')->count(),
            'downloads' => (clone $activityQuery)->where('event', 'like', '%download%')->count(),
            'updates' => (clone $activityQuery)->whereIn('event', ['created', 'updated'])->count(),
            'total' => (clone $activityQuery)->count(),
        ];

        $activities = Activity::forSubject($archive)
            ->with('causer')
            ->latest()
            ->paginate(6, ['*'], 'activity_page');

        return view('admin.pages.archives.show', [
            'archive' => $archive,
            'stats' => $stats,
            'activities' => $activities,
        ]);
    }

    /**
     * Download the specified archive document file and log activity without latency.
     */
    public function download(Archive $archive): BinaryFileResponse|RedirectResponse
    {
        if (empty($archive->filepath) || ! Storage::disk('public')->exists($archive->filepath)) {
            return redirect()->back()
                ->with('error', "Le fichier lié à cette archive n'est pas disponible sur le stockage.");
        }

        $user = auth()->user();
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        // Enregistrement asynchrone ultra-rapide sans latence HTTP (defer)
        defer(function () use ($archive, $user, $ip, $userAgent) {
            activity('archives')
                ->performedOn($archive)
                ->causedBy($user)
                ->event('archive.download')
                ->withProperties([
                    'archive_id' => $archive->id,
                    'typearchive' => $archive->typearchive,
                    'description' => $archive->description,
                    'filepath' => $archive->filepath,
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                ])
                ->log("Téléchargement du document d'archive N°{$archive->id} (« {$archive->description} ») par ".($user->name ?? 'Utilisateur'));
        });

        $extension = pathinfo($archive->filepath, PATHINFO_EXTENSION);
        $fileName = Str::slug($archive->description ?: 'archive', '_').($extension ? ".{$extension}" : '');

        return response()->download(Storage::disk('public')->path($archive->filepath), $fileName);
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
     * Get the list of archive types from the dedicated table.
     *
     * @return array<int, string>
     */
    protected function getArchiveTypes(): array
    {
        $types = ArchiveType::orderBy('name')->pluck('name')->toArray();

        if (empty($types)) {
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

        return $types;
    }

    /**
     * Get the list of physical locations from database.
     *
     * @return array<string, string>
     */
    protected function getEmplacementsPhysiques(): array
    {
        $locations = ArchiveLocation::where('type', ArchiveLocation::TYPE_PHYSICAL)
            ->orderBy('name')
            ->get();

        if ($locations->isEmpty()) {
            return [
                'FOUDA' => "FOUDA — Centre d'excellence DGB",
                'DGB' => 'DGB — Direction Générale du Budget',
                'IMPRIMERIE NATIONALE' => 'Imprimerie Nationale',
            ];
        }

        $result = [];
        foreach ($locations as $loc) {
            $result[$loc->name] = $loc->description ? "{$loc->name} — {$loc->description}" : $loc->name;
        }

        return $result;
    }

    /**
     * Get the list of virtual locations from database.
     *
     * @return array<string, string>
     */
    protected function getEmplacementsVirtuels(): array
    {
        $locations = ArchiveLocation::where('type', ArchiveLocation::TYPE_VIRTUAL)
            ->orderBy('name')
            ->pluck('name', 'name')
            ->toArray();

        if (empty($locations)) {
            return [
                'Serveur' => 'Serveur',
            ];
        }

        return $locations;
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
