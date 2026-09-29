<?php

namespace App\Http\Controllers;

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
        $query = Activity::with(['causer', 'subject']);

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

        // Statistiques globales KPI
        $totalEventsCount = Activity::count();
        $todayEventsCount = Activity::whereDate('created_at', now()->today())->count();
        $authEventsCount = Activity::where('log_name', 'auth')->count();
        $systemErrorsCount = Activity::where('event', 'system.error')->count();

        // Liste des utilisateurs ayant généré des logs pour le filtre
        $usersList = User::orderBy('name')->get();

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
     * Return activity log details as JSON.
     */
    public function show(Activity $activity): JsonResponse
    {
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
        $query = Activity::with(['causer', 'subject']);

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
                echo "========================================================================\n";
                echo "           ARCHIDOC DGB — JOURNAL DES ÉVÉNEMENTS (BOÎTE NOIRE)           \n";
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
}
