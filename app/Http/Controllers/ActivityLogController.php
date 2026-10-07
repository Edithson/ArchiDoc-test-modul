<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Department;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display the main Event Tree and unified Activity Log journal.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = auth()->user();

        $baseQuery = Activity::with(['causer', 'subject']);
        $this->applyConsultationScopeFilter($baseQuery, $user, 'general');

        $query = (clone $baseQuery);

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%")
                    ->orWhereHasMorph('causer', [User::class], function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('matricule', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par catégorie de log
        if ($request->filled('log_name') && $request->input('log_name') !== 'all') {
            $query->where('log_name', $request->input('log_name'));
        }

        // Filtre par type d'événement
        if ($request->filled('event_type') && $request->input('event_type') !== 'all') {
            $query->where('event', $request->input('event_type'));
        }

        // Filtre par plage de dates
        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
            if ($range === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        // Filtre par auteur (causer_id)
        if ($request->filled('causer_id') && $request->input('causer_id') !== 'all') {
            $query->where('causer_id', $request->input('causer_id'));
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistiques globales KPI (filtrées par le périmètre autorisé)
        $totalEventsCount = (clone $baseQuery)->count();
        $todayEventsCount = (clone $baseQuery)->whereDate('created_at', now()->today())->count();
        $authEventsCount = (clone $baseQuery)->where('log_name', 'auth')->count();
        $systemErrorsCount = (clone $baseQuery)->where('event', 'system.error')->count();

        // Liste des utilisateurs accessibles pour le filtre
        $usersList = $this->getScopedUsersList($user);

        return view('admin.pages.activity_logs.index', [
            'activities' => $activities,
            'search' => $request->input('search'),
            'logName' => $request->input('log_name', 'all'),
            'eventType' => $request->input('event_type', 'all'),
            'dateRange' => $request->input('date_range', 'all'),
            'causerId' => $request->input('causer_id', 'all'),
            'totalEventsCount' => $totalEventsCount,
            'todayEventsCount' => $todayEventsCount,
            'authEventsCount' => $authEventsCount,
            'systemErrorsCount' => $systemErrorsCount,
            'usersList' => $usersList,
        ]);
    }

    /**
     * Display Security & Authentication journal.
     */
    public function auth(Request $request): View
    {
        abort_if(! ($request->user()?->isSuper() || $request->user()?->hasPermission('User', 'read')), 403, 'Accès non autorisé au journal de sécurité et des accès.');

        $query = Activity::with(['causer', 'subject'])
            ->where('log_name', 'auth');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhereHasMorph('causer', [User::class], function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('matricule', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
            if ($range === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalAuthCount = Activity::where('log_name', 'auth')->count();
        $successfulLoginsCount = Activity::where('event', 'auth.login')->count();
        $failedLoginsCount = Activity::where('event', 'auth.failed_login')->count();
        $lockoutsCount = Activity::where('event', 'auth.lockout')->count();

        return view('admin.pages.activity_logs.auth', [
            'activities' => $activities,
            'search' => $request->input('search'),
            'dateRange' => $request->input('date_range', 'all'),
            'totalAuthCount' => $totalAuthCount,
            'successfulLoginsCount' => $successfulLoginsCount,
            'failedLoginsCount' => $failedLoginsCount,
            'lockoutsCount' => $lockoutsCount,
        ]);
    }

    /**
     * Display System Errors & Diagnostics journal.
     */
    public function system(Request $request): View
    {
        abort_if(! ($request->user()?->isSuper() || $request->user()?->hasPermission('Setting', 'read')), 403, "Accès non autorisé au journal d'erreurs système.");

        $query = Activity::with(['causer', 'subject'])
            ->where(function ($q) {
                $q->where('log_name', 'system')
                    ->orWhere('event', 'system.error');
            });

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('description', 'like', "%{$search}%");
        }

        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
            if ($range === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalSystemErrors = Activity::where('event', 'system.error')->count();
        $todayErrorsCount = Activity::where('event', 'system.error')
            ->whereDate('created_at', now()->today())->count();

        return view('admin.pages.activity_logs.system', [
            'activities' => $activities,
            'search' => $request->input('search'),
            'dateRange' => $request->input('date_range', 'all'),
            'totalSystemErrors' => $totalSystemErrors,
            'todayErrorsCount' => $todayErrorsCount,
        ]);
    }

    /**
     * Display Archive Consultation Analytics and Audit Dashboard.
     */
    public function archivesConsultations(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $baseQuery = Activity::with(['causer', 'subject'])
            ->where('event', 'archive.consultation');

        $this->applyConsultationScopeFilter($baseQuery, $user, 'archive');

        $query = (clone $baseQuery);

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHasMorph('causer', [User::class], function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('matricule', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par département
        if ($request->filled('departement') && $request->input('departement') !== 'all') {
            $dept = $request->input('departement');
            $query->where('properties->departement', $dept);
        }

        // Filtre par période
        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
            if ($range === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        // Filtre par auteur
        if ($request->filled('causer_id') && $request->input('causer_id') !== 'all') {
            $query->where('causer_id', $request->input('causer_id'));
        }

        // Traitement de l'exportation (CSV, JSON, TXT)
        if ($request->filled('export')) {
            $exportItems = (clone $query)->orderBy('created_at', 'desc')->get();
            $format = strtolower((string) $request->input('export'));
            $timestamp = now()->format('Y-m-d_H-i-s');

            if ($format === 'json') {
                $data = $exportItems->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'description' => $activity->description,
                        'causer' => $activity->causer ? [
                            'name' => $activity->causer->name,
                            'email' => $activity->causer->email,
                            'matricule' => $activity->causer->matricule,
                        ] : 'Système',
                        'departement' => $activity->properties['departement'] ?? 'N/A',
                        'format' => $activity->properties['format'] ?? 'N/A',
                        'ip' => $activity->properties['ip'] ?? 'N/A',
                        'created_at' => $activity->created_at ? $activity->created_at->toIso8601String() : null,
                    ];
                });

                return response()->streamDownload(function () use ($data) {
                    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }, "consultations_archives_{$timestamp}.json", ['Content-Type' => 'application/json; charset=UTF-8']);
            }

            if ($format === 'txt') {
                return response()->streamDownload(function () use ($exportItems) {
                    $title = strtoupper(setting('app_name', 'ARCHIDOC')).' — HISTORIQUE DES CONSULTATIONS D\'ARCHIVES';
                    echo "========================================================================\n";
                    echo "      {$title}           \n";
                    echo '      Généré le : '.now()->format('d/m/Y H:i:s')."\n";
                    echo "========================================================================\n\n";

                    foreach ($exportItems as $activity) {
                        $causerStr = $activity->causer ? "{$activity->causer->name} ({$activity->causer->matricule})" : 'Système';
                        $dateStr = $activity->created_at ? $activity->created_at->format('d/m/Y H:i:s') : 'N/A';
                        $deptStr = $activity->properties['departement'] ?? 'N/A';
                        $fmtStr = strtoupper((string) ($activity->properties['format'] ?? 'DOC'));

                        echo "[{$dateStr}] Auteur: {$causerStr} | Dept: {$deptStr} | Format: {$fmtStr}\n";
                        echo "Description : {$activity->description}\n";
                        echo 'IP Client   : '.($activity->properties['ip'] ?? 'N/A')."\n";
                        echo "------------------------------------------------------------------------\n";
                    }
                }, "consultations_archives_{$timestamp}.txt", ['Content-Type' => 'text/plain; charset=UTF-8']);
            }

            // Défaut CSV
            return response()->streamDownload(function () use ($exportItems) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, ['ID', 'Horodatage', 'Auteur', 'Matricule', 'Département', 'Description Archive', 'Format Document', 'Adresse IP']);

                foreach ($exportItems as $activity) {
                    fputcsv($handle, [
                        $activity->id,
                        $activity->created_at ? $activity->created_at->format('Y-m-d H:i:s') : '',
                        $activity->causer ? $activity->causer->name : 'Système',
                        $activity->causer ? $activity->causer->matricule : 'SYS',
                        $activity->properties['departement'] ?? 'N/A',
                        $activity->description,
                        strtoupper((string) ($activity->properties['format'] ?? 'DOC')),
                        $activity->properties['ip'] ?? 'N/A',
                    ]);
                }

                fclose($handle);
            }, "consultations_archives_{$timestamp}.csv", [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"consultations_archives_{$timestamp}.csv\"",
            ]);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistiques globales KPI
        $totalConsultations = (clone $baseQuery)->count();
        $todayConsultations = (clone $baseQuery)->whereDate('created_at', now()->today())->count();
        $uniqueArchivesCount = (clone $baseQuery)->whereNotNull('subject_id')->distinct('subject_id')->count('subject_id');

        // Utilisateur le plus actif
        $topUserActivity = (clone $baseQuery)
            ->whereNotNull('causer_id')
            ->selectRaw('causer_id, COUNT(*) as aggregate')
            ->groupBy('causer_id')
            ->orderByDesc('aggregate')
            ->first();
        $topUser = $topUserActivity ? User::find($topUserActivity->causer_id) : null;
        $topUserCount = $topUserActivity ? $topUserActivity->aggregate : 0;

        // Tendance sur les 14 derniers jours (Graphique 1)
        $dailyTrend = [
            'labels' => [],
            'data' => [],
        ];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d/m');
            $count = (clone $baseQuery)->whereDate('created_at', $date)->count();
            $dailyTrend['labels'][] = $label;
            $dailyTrend['data'][] = $count;
        }

        // Répartition par Département & Type (Graphiques 2 & 3)
        $allConsultations = (clone $baseQuery)->get();
        $deptCounts = [];
        $typeCounts = [];

        foreach ($allConsultations as $act) {
            $d = $act->properties['departement'] ?? 'Non Spécifié';
            $deptCounts[$d] = ($deptCounts[$d] ?? 0) + 1;

            $t = $act->properties['typearchive'] ?? 'Document Standard';
            $typeCounts[$t] = ($typeCounts[$t] ?? 0) + 1;
        }

        arsort($deptCounts);
        arsort($typeCounts);

        $topDepts = array_slice($deptCounts, 0, 6, true);
        $topTypes = array_slice($typeCounts, 0, 6, true);

        $usersList = $this->getScopedUsersList($user);
        $departmentsList = $this->getScopedDepartmentsList($user);

        return view('admin.pages.activity_logs.archives_consultations', [
            'activities' => $activities,
            'search' => $request->input('search'),
            'departementFilter' => $request->input('departement', 'all'),
            'dateRange' => $request->input('date_range', 'all'),
            'causerId' => $request->input('causer_id', 'all'),
            'totalConsultations' => $totalConsultations,
            'todayConsultations' => $todayConsultations,
            'uniqueArchivesCount' => $uniqueArchivesCount,
            'topUser' => $topUser,
            'topUserCount' => $topUserCount,
            'dailyTrend' => $dailyTrend,
            'topDepts' => $topDepts,
            'topTypes' => $topTypes,
            'usersList' => $usersList,
            'departmentsList' => $departmentsList,
        ]);
    }

    /**
     * Display Personnel Dossier Consultation Analytics and Audit Dashboard.
     */
    public function personnelConsultations(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $baseQuery = Activity::with(['causer', 'subject'])
            ->whereIn('event', ['personnel.consultation', 'personnel.download']);

        $this->applyConsultationScopeFilter($baseQuery, $user, 'personnel');

        $query = (clone $baseQuery);

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('properties->name', 'like', "%{$search}%")
                    ->orWhere('properties->matricule', 'like', "%{$search}%")
                    ->orWhereHasMorph('causer', [User::class], function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('matricule', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par département
        if ($request->filled('departement') && $request->input('departement') !== 'all') {
            $dept = $request->input('departement');
            $query->where('properties->departement', $dept);
        }

        // Filtre par période
        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
            if ($range === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        // Filtre par auteur
        if ($request->filled('causer_id') && $request->input('causer_id') !== 'all') {
            $query->where('causer_id', $request->input('causer_id'));
        }

        // Traitement de l'exportation (CSV, JSON, TXT)
        if ($request->filled('export')) {
            $exportItems = (clone $query)->orderBy('created_at', 'desc')->get();
            $format = strtolower((string) $request->input('export'));
            $timestamp = now()->format('Y-m-d_H-i-s');

            if ($format === 'json') {
                $data = $exportItems->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'event' => $activity->event,
                        'description' => $activity->description,
                        'causer' => $activity->causer ? [
                            'name' => $activity->causer->name,
                            'email' => $activity->causer->email,
                            'matricule' => $activity->causer->matricule,
                        ] : 'Système',
                        'personnel_nom' => $activity->properties['name'] ?? 'N/A',
                        'personnel_matricule' => $activity->properties['matricule'] ?? 'N/A',
                        'departement' => $activity->properties['departement'] ?? 'N/A',
                        'ip' => $activity->properties['ip'] ?? 'N/A',
                        'created_at' => $activity->created_at ? $activity->created_at->toIso8601String() : null,
                    ];
                });

                return response()->streamDownload(function () use ($data) {
                    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }, "consultations_personnel_{$timestamp}.json", ['Content-Type' => 'application/json; charset=UTF-8']);
            }

            if ($format === 'txt') {
                return response()->streamDownload(function () use ($exportItems) {
                    $title = strtoupper(setting('app_name', 'ARCHIDOC')).' — HISTORIQUE DES CONSULTATIONS DOSSIERS PERSONNEL';
                    echo "========================================================================\n";
                    echo "    {$title}     \n";
                    echo '      Généré le : '.now()->format('d/m/Y H:i:s')."\n";
                    echo "========================================================================\n\n";

                    foreach ($exportItems as $activity) {
                        $causerStr = $activity->causer ? "{$activity->causer->name} ({$activity->causer->matricule})" : 'Système';
                        $dateStr = $activity->created_at ? $activity->created_at->format('d/m/Y H:i:s') : 'N/A';
                        $agentStr = ($activity->properties['name'] ?? 'Agent').' ['.($activity->properties['matricule'] ?? 'MAT-N/A').']';
                        $actionStr = $activity->event === 'personnel.download' ? 'TÉLÉCHARGEMENT ZIP' : 'CONSULTATION FICHE';

                        echo "[{$dateStr}] Action: {$actionStr} | Consultateur: {$causerStr}\n";
                        echo "Dossier Agent: {$agentStr}\n";
                        echo "Description  : {$activity->description}\n";
                        echo 'IP Client    : '.($activity->properties['ip'] ?? 'N/A')."\n";
                        echo "------------------------------------------------------------------------\n";
                    }
                }, "consultations_personnel_{$timestamp}.txt", ['Content-Type' => 'text/plain; charset=UTF-8']);
            }

            // Défaut CSV
            return response()->streamDownload(function () use ($exportItems) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($handle, ['ID', 'Horodatage', 'Action', 'Consultateur', 'Matricule Consultateur', 'Nom Agent', 'Matricule Agent', 'Département', 'Adresse IP']);

                foreach ($exportItems as $activity) {
                    fputcsv($handle, [
                        $activity->id,
                        $activity->created_at ? $activity->created_at->format('Y-m-d H:i:s') : '',
                        $activity->event === 'personnel.download' ? 'Téléchargement ZIP' : 'Consultation Fiche',
                        $activity->causer ? $activity->causer->name : 'Système',
                        $activity->causer ? $activity->causer->matricule : 'SYS',
                        $activity->properties['name'] ?? 'N/A',
                        $activity->properties['matricule'] ?? 'N/A',
                        $activity->properties['departement'] ?? 'N/A',
                        $activity->properties['ip'] ?? 'N/A',
                    ]);
                }

                fclose($handle);
            }, "consultations_personnel_{$timestamp}.csv", [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"consultations_personnel_{$timestamp}.csv\"",
            ]);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistiques globales KPI
        $totalConsultations = (clone $baseQuery)->count();
        $todayConsultations = (clone $baseQuery)->whereDate('created_at', now()->today())->count();
        $uniquePersonnelsCount = (clone $baseQuery)->whereNotNull('subject_id')->distinct('subject_id')->count('subject_id');

        // Utilisateur le plus actif
        $topUserActivity = (clone $baseQuery)
            ->whereNotNull('causer_id')
            ->selectRaw('causer_id, COUNT(*) as aggregate')
            ->groupBy('causer_id')
            ->orderByDesc('aggregate')
            ->first();
        $topUser = $topUserActivity ? User::find($topUserActivity->causer_id) : null;
        $topUserCount = $topUserActivity ? $topUserActivity->aggregate : 0;

        // Tendance sur les 14 derniers jours (Graphique 1)
        $dailyTrend = [
            'labels' => [],
            'data' => [],
        ];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d/m');
            $count = (clone $baseQuery)->whereDate('created_at', $date)->count();
            $dailyTrend['labels'][] = $label;
            $dailyTrend['data'][] = $count;
        }

        // Répartition par Département & Type d'action (Graphiques 2 & 3)
        $allConsultations = (clone $baseQuery)->get();
        $deptCounts = [];
        $actionCounts = [
            'Consultation Fiche Agent' => 0,
            'Téléchargement ZIP Dossier' => 0,
        ];

        foreach ($allConsultations as $act) {
            $d = $act->properties['departement'] ?? ($act->causer?->department?->name ?? 'Non Spécifié');
            $deptCounts[$d] = ($deptCounts[$d] ?? 0) + 1;

            if ($act->event === 'personnel.download') {
                $actionCounts['Téléchargement ZIP Dossier']++;
            } else {
                $actionCounts['Consultation Fiche Agent']++;
            }
        }

        arsort($deptCounts);

        $topDepts = array_slice($deptCounts, 0, 6, true);

        $usersList = $this->getScopedUsersList($user);
        $departmentsList = $this->getScopedDepartmentsList($user);

        return view('admin.pages.activity_logs.personnel_consultations', [
            'activities' => $activities,
            'search' => $request->input('search'),
            'departementFilter' => $request->input('departement', 'all'),
            'dateRange' => $request->input('date_range', 'all'),
            'causerId' => $request->input('causer_id', 'all'),
            'totalConsultations' => $totalConsultations,
            'todayConsultations' => $todayConsultations,
            'uniquePersonnelsCount' => $uniquePersonnelsCount,
            'topUser' => $topUser,
            'topUserCount' => $topUserCount,
            'dailyTrend' => $dailyTrend,
            'topDepts' => $topDepts,
            'actionCounts' => $actionCounts,
            'usersList' => $usersList,
            'departmentsList' => $departmentsList,
        ]);
    }

    /**
     * Return activity log details as JSON.
     */
    public function show(Activity $activity): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $this->checkActivityScope($user, $activity);

        $activity->load(['causer', 'subject']);

        return response()->json([
            'id' => $activity->id,
            'log_name' => $activity->log_name,
            'description' => $activity->description,
            'event' => $activity->event,
            'created_at' => $activity->created_at ? $activity->created_at->format('d/m/Y H:i:s') : '—',
            'created_at_human' => $activity->created_at ? $activity->created_at->diffForHumans() : '—',
            'causer' => $activity->causer ? [
                'id' => $activity->causer->id,
                'name' => $activity->causer->name ?? 'Système',
                'email' => $activity->causer->email ?? '—',
                'matricule' => $activity->causer->matricule ?? '—',
            ] : null,
            'subject' => $activity->subject ? [
                'type' => class_basename($activity->subject_type),
                'id' => $activity->subject_id,
            ] : null,
            'attribute_changes' => $activity->attribute_changes ?? null,
            'properties' => $activity->properties ?? null,
        ]);
    }

    /**
     * Export activity logs in CSV, JSON, or TXT format respecting active filters.
     */
    public function export(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $query = Activity::with(['causer', 'subject']);
        $this->applyConsultationScopeFilter($query, $user, 'export');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%")
                    ->orWhereHasMorph('causer', [User::class], function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('matricule', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('log_name') && $request->input('log_name') !== 'all') {
            $query->where('log_name', $request->input('log_name'));
        }

        if ($request->filled('event_type') && $request->input('event_type') !== 'all') {
            $query->where('event', $request->input('event_type'));
        }

        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
            if ($range === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        if ($request->filled('causer_id') && $request->input('causer_id') !== 'all') {
            $query->where('causer_id', $request->input('causer_id'));
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->input('subject_type'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->input('subject_id'));
        }

        $activities = $query->orderBy('created_at', 'desc')->get();

        $format = strtolower($request->input('format', 'csv'));
        $timestamp = now()->format('Y-m-d_H-i-s');

        if ($format === 'json') {
            $data = $activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'log_name' => $activity->log_name,
                    'event' => $activity->event,
                    'description' => $activity->description,
                    'causer' => $activity->causer ? [
                        'id' => $activity->causer->id,
                        'name' => $activity->causer->name,
                        'email' => $activity->causer->email,
                        'matricule' => $activity->causer->matricule,
                    ] : 'System',
                    'subject_type' => $activity->subject_type,
                    'subject_id' => $activity->subject_id,
                    'properties' => $activity->properties,
                    'created_at' => $activity->created_at ? $activity->created_at->toIso8601String() : null,
                ];
            });

            return response()->streamDownload(function () use ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }, "activity_logs_{$timestamp}.json", ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        if ($format === 'txt') {
            return response()->streamDownload(function () use ($activities) {
                $title = strtoupper(setting('app_name', 'ARCHIDOC')).' — JOURNAL DES ÉVÉNEMENTS (BOÎTE NOIRE)';
                echo "========================================================================\n";
                echo "           {$title}           \n";
                echo '           Généré le : '.now()->format('d/m/Y H:i:s')."\n";
                echo "========================================================================\n\n";

                foreach ($activities as $activity) {
                    $causerStr = $activity->causer ? "{$activity->causer->name} ({$activity->causer->matricule})" : 'Système';
                    $dateStr = $activity->created_at ? $activity->created_at->format('d/m/Y H:i:s') : 'N/A';
                    $subjectStr = $activity->subject_type ? class_basename($activity->subject_type)." #{$activity->subject_id}" : 'N/A';

                    echo "[{$dateStr}] [{$activity->log_name}.{$activity->event}] Auteur: {$causerStr}\n";
                    echo "Description : {$activity->description}\n";
                    echo "Cible       : {$subjectStr}\n";
                    if ($activity->properties && count($activity->properties) > 0) {
                        echo 'Propriétés  : '.json_encode($activity->properties, JSON_UNESCAPED_UNICODE)."\n";
                    }
                    echo "------------------------------------------------------------------------\n";
                }
            }, "activity_logs_{$timestamp}.txt", ['Content-Type' => 'text/plain; charset=UTF-8']);
        }

        // Defaut: CSV Export avec BOM UTF-8
        return response()->streamDownload(function () use ($activities) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['ID', 'Horodatage', 'Catégorie (Log)', 'Type Événement', 'Description', 'Auteur / Agent', 'Matricule Auteur', 'Entité Ciblée', 'ID Entité', 'Propriétés / Métadonnées']);

            foreach ($activities as $activity) {
                fputcsv($handle, [
                    $activity->id,
                    $activity->created_at ? $activity->created_at->format('Y-m-d H:i:s') : '',
                    $activity->log_name,
                    $activity->event,
                    $activity->description,
                    $activity->causer ? $activity->causer->name : 'Système',
                    $activity->causer ? $activity->causer->matricule : 'SYS',
                    $activity->subject_type ? class_basename($activity->subject_type) : 'N/A',
                    $activity->subject_id ?? 'N/A',
                    json_encode($activity->properties, JSON_UNESCAPED_UNICODE),
                ]);
            }

            fclose($handle);
        }, "activity_logs_{$timestamp}.csv", [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"activity_logs_{$timestamp}.csv\"",
        ]);
    }

    /**
     * Apply consultation history scope filters based on user role and department hierarchy:
     * - Super Privilégié: Full access to all history.
     * - Privilégié: History of sub-departments / users belonging to their main department.
     * - Classique: Individual consultation history (causer_id == auth()->id()) only.
     */
    protected function applyConsultationScopeFilter($query, User $user, string $logType = 'general'): void
    {
        if ($user->isSuper()) {
            return;
        }

        if ($user->isPrivileged() || ($user->department_id && ! $user->sub_department_id)) {
            $deptId = $user->department_id;
            $deptName = $user->department?->name;

            if ($deptId) {
                $query->where(function ($q) use ($deptId, $deptName) {
                    $q->whereHasMorph('causer', [User::class], function ($userQuery) use ($deptId) {
                        $userQuery->where('department_id', $deptId);
                    })
                        ->orWhereHasMorph('subject', [Archive::class], function ($archiveQuery) use ($deptId) {
                            $archiveQuery->where('department_id', $deptId);
                        })
                        ->orWhereHasMorph('subject', [Personnel::class], function ($personnelQuery) use ($deptId) {
                            $personnelQuery->where('department_id', $deptId);
                        });

                    if ($deptName) {
                        $q->orWhere('properties->departement', $deptName);
                    }
                });
            } else {
                $query->where('causer_id', $user->id);
            }

            return;
        }

        // Classique / Agent avec sous-département : Historique individuel
        $query->where('causer_id', $user->id);
    }

    /**
     * Verify single activity record view permission based on user scope.
     */
    protected function checkActivityScope(User $user, Activity $activity): void
    {
        if ($user->isSuper()) {
            return;
        }

        if ($user->isPrivileged() || ($user->department_id && ! $user->sub_department_id)) {
            $deptId = $user->department_id;
            $causerDeptId = $activity->causer?->department_id;

            $subjectDeptId = null;
            if ($activity->subject instanceof Archive || $activity->subject instanceof Personnel) {
                $subjectDeptId = $activity->subject->department_id;
            }

            $propertyDept = $activity->properties['departement'] ?? null;
            $deptName = $user->department?->name;

            if (
                ($deptId && (int) $causerDeptId === (int) $deptId) ||
                ($deptId && (int) $subjectDeptId === (int) $deptId) ||
                ($deptName && $propertyDept === $deptName) ||
                (int) $activity->causer_id === (int) $user->id
            ) {
                return;
            }

            abort(403, 'Accès refusé : Cet événement ne concerne pas votre département.');
        }

        if ((int) $activity->causer_id !== (int) $user->id) {
            abort(403, 'Accès refusé : Vous ne pouvez consulter que votre propre historique.');
        }
    }

    /**
     * Get user list for filters scoped to current user permission.
     */
    protected function getScopedUsersList(User $user)
    {
        if ($user->isSuper()) {
            return User::orderBy('name')->get();
        }

        if ($user->isPrivileged() || ($user->department_id && ! $user->sub_department_id)) {
            if ($user->department_id) {
                return User::where('department_id', $user->department_id)->orderBy('name')->get();
            }
        }

        return User::where('id', $user->id)->get();
    }

    /**
     * Get department list for filters scoped to current user permission.
     */
    protected function getScopedDepartmentsList(User $user): array
    {
        if ($user->isSuper()) {
            return Department::orderBy('name')->pluck('name')->toArray();
        }

        if ($user->department?->name) {
            return [$user->department->name];
        }

        return [];
    }
}
