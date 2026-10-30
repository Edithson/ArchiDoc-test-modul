<?php

namespace App\Console\Commands;

use App\Models\Archive;
use App\Models\ArchiveType;
use App\Models\Department;
use App\Models\Personnel;
use App\Models\Piece;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;

class SimulateDemoActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:simulate-activity
                            {--count= : Nombre d\'événements à générer (écrase le quota aléatoire)}
                            {--days=0 : Nombre de jours antérieurs à simuler (rétro-remplissage)}
                            {--hourly : Mode incrémental horaire pour le planificateur}
                            {--force : Exécuter même si DEMO_SIMULATION_ENABLED est désactivé}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simule une activité réaliste multi-modèles (consultations archives & personnel, recherches, téléchargements, auth) pour maintenir l\'application vivante en démo.';

    /**
     * Realistic user agents for simulation.
     *
     * @var array<int, string>
     */
    protected array $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_3_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36 Edg/122.0.0.0',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 17_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Mobile/15E148 Safari/604.1',
    ];

    /**
     * Sample realistic search terms for archive discovery.
     *
     * @var array<int, string>
     */
    protected array $sampleSearchTerms = [
        'Rapport financier',
        'Procès-verbal réunion',
        'Contrat prestation',
        'Note de service',
        'Audit interne',
        'Facture équipement',
        'Attestation de service fait',
        'Bordereau de transmission',
        'Convention de partenariat',
        'Dossier de régularisation',
        'Statistiques annuelles',
        'Budget prévisionnel',
        'Demande d\'achat matériel',
    ];

    /**
     * Counters for reporting.
     *
     * @var array<string, int>
     */
    protected array $stats = [
        'archive_consultations' => 0,
        'archive_downloads' => 0,
        'archive_searches' => 0,
        'personnel_consultations' => 0,
        'personnel_downloads' => 0,
        'piece_consultations' => 0,
        'department_consultations' => 0,
        'auth_events' => 0,
        'failures' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $enabled = (bool) config('demo.simulation_enabled', false);

        if (! $enabled && ! $this->option('force')) {
            $this->info("Simulation d'activité démo désactivée (DEMO_SIMULATION_ENABLED=false). Utilisez --force pour exécuter manuellement.");

            return Command::SUCCESS;
        }

        $days = max(0, (int) $this->option('days'));
        $isHourly = (bool) $this->option('hourly');

        // Charger en mémoire les modèles de référence de façon sécurisée
        $users = $this->safeLoad(fn () => User::where('statut', true)->get());
        if ($users->isEmpty()) {
            $this->warn("Aucun compte utilisateur actif n'a été trouvé. Simulation annulée.");

            return Command::SUCCESS;
        }

        $archives = $this->safeLoad(fn () => Archive::with(['department', 'subDepartment', 'archiveType'])->get());
        $personnels = $this->safeLoad(fn () => Personnel::with('department')->get());
        $pieces = $this->safeLoad(fn () => Piece::all());
        $departments = $this->safeLoad(fn () => Department::all());
        $archiveTypes = $this->safeLoad(fn () => ArchiveType::all());

        $this->info("Démarrage de l'automate de simulation d'activité démo...");

        if ($days > 0) {
            // Mode rétro-remplissage sur plusieurs jours
            $this->info("Rétro-génération sur les {$days} derniers jours ouvrés...");

            for ($d = $days; $d >= 0; $d--) {
                $date = Carbon::now()->subDays($d);

                if (config('demo.weekdays_only', true) && $date->isWeekend()) {
                    continue;
                }

                $count = (int) $this->option('count') ?: rand(
                    config('demo.simulation_min', 5),
                    config('demo.simulation_max', 15)
                );

                for ($i = 0; $i < $count; $i++) {
                    $timestamp = $this->generateRandomTimeForDate($date);
                    $this->dispatchRandomActivity($users, $archives, $personnels, $pieces, $departments, $archiveTypes, $timestamp);
                }
            }
        } elseif ($isHourly) {
            // Mode horaire automatique (exécuté par le Scheduler)
            $now = Carbon::now();
            $startHour = (int) config('demo.business_hours.start', 8);
            $endHour = (int) config('demo.business_hours.end', 18);

            // Vérifier si nous sommes dans les heures ouvrées
            if (config('demo.weekdays_only', true) && $now->isWeekend()) {
                $this->info('Week-end : aucune simulation programmée en mode horaire.');

                return Command::SUCCESS;
            }

            if ($now->hour < $startHour || $now->hour > $endHour) {
                $this->info("En dehors des heures de bureau ({$startHour}h-{$endHour}h) : aucune simulation générée.");

                return Command::SUCCESS;
            }

            // Générer 1 à 2 événements pour cette heure
            $eventsCount = (int) $this->option('count') ?: (rand(1, 10) <= 8 ? 1 : 2);

            for ($i = 0; $i < $eventsCount; $i++) {
                $this->dispatchRandomActivity($users, $archives, $personnels, $pieces, $departments, $archiveTypes, $now);
            }
        } else {
            // Mode exécution ponctuelle ou lot quotidien
            $count = (int) $this->option('count') ?: rand(
                config('demo.simulation_min', 5),
                config('demo.simulation_max', 15)
            );

            $this->info("Génération de {$count} activités pour aujourd'hui...");

            for ($i = 0; $i < $count; $i++) {
                $timestamp = $this->generateRandomTimeForDate(Carbon::now());
                $this->dispatchRandomActivity($users, $archives, $personnels, $pieces, $departments, $archiveTypes, $timestamp);
            }
        }

        $totalGenerated = array_sum(array_filter($this->stats, fn ($key) => $key !== 'failures', ARRAY_FILTER_USE_KEY));

        $this->info('------------------------------------------------------------');
        $this->info("Simulation terminée : {$totalGenerated} activités créées avec succès.");
        $this->table(
            ['Type d\'activité', 'Nombre généré'],
            [
                ['Consultations d\'archives', $this->stats['archive_consultations']],
                ['Téléchargements d\'archives', $this->stats['archive_downloads']],
                ['Recherches multicritères d\'archives', $this->stats['archive_searches']],
                ['Consultations dossiers du personnel', $this->stats['personnel_consultations']],
                ['Téléchargements dossiers du personnel', $this->stats['personnel_downloads']],
                ['Consultations de pièces justificatives', $this->stats['piece_consultations']],
                ['Consultations de départements', $this->stats['department_consultations']],
                ['Événements d\'authentification / Sécurité', $this->stats['auth_events']],
                ['Tentatives ignorées (modèles vides / erreurs isolées)', $this->stats['failures']],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Dispatch a randomly selected activity with weighted probabilities and total error isolation.
     */
    protected function dispatchRandomActivity(
        Collection $users,
        Collection $archives,
        Collection $personnels,
        Collection $pieces,
        Collection $departments,
        Collection $archiveTypes,
        Carbon $timestamp
    ): void {
        // Pondération réaliste des actions en milieu professionnel
        $weights = [
            'archive.consultation' => 35,
            'archive.download' => 15,
            'archive.search' => 10,
            'personnel.consultation' => 20,
            'personnel.download' => 5,
            'piece.consultation' => 5,
            'department.consultation' => 5,
            'auth.event' => 5,
        ];

        $action = $this->weightedRandom($weights);

        try {
            $success = match ($action) {
                'archive.consultation' => $this->simulateArchiveConsultation($users, $archives, $timestamp),
                'archive.download' => $this->simulateArchiveDownload($users, $archives, $timestamp),
                'archive.search' => $this->simulateArchiveSearch($users, $departments, $timestamp),
                'personnel.consultation' => $this->simulatePersonnelConsultation($users, $personnels, $timestamp),
                'personnel.download' => $this->simulatePersonnelDownload($users, $personnels, $timestamp),
                'piece.consultation' => $this->simulatePieceConsultation($users, $pieces, $timestamp),
                'department.consultation' => $this->simulateDepartmentConsultation($users, $departments, $timestamp),
                'auth.event' => $this->simulateAuthEvent($users, $timestamp),
                default => false,
            };

            if (! $success) {
                $this->stats['failures']++;
            }
        } catch (\Throwable $e) {
            $this->stats['failures']++;
            Log::warning("Simulation démo : échec isolé sur l'action [{$action}] : {$e->getMessage()}", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * Simulate a realistic Archive Consultation.
     */
    protected function simulateArchiveConsultation(Collection $users, Collection $archives, Carbon $timestamp): bool
    {
        if ($archives->isEmpty() || $users->isEmpty()) {
            return false;
        }

        /** @var User $user */
        $user = $users->random();

        // Si l'utilisateur appartient à un département, privilégier une archive de ce département
        $scopedArchives = $user->department_id
            ? $archives->filter(fn ($a) => $a->department_id === $user->department_id)
            : $archives;

        /** @var Archive $archive */
        $archive = $scopedArchives->isNotEmpty() ? $scopedArchives->random() : $archives->random();

        $ip = $this->randomIp();
        $userAgent = $this->randomUserAgent();

        $activity = activity('archives')
            ->performedOn($archive)
            ->causedBy($user)
            ->event('archive.consultation')
            ->withProperties([
                'archive_id' => $archive->id,
                'archive_type_id' => $archive->archive_type_id,
                'typearchive' => $archive->archiveType?->name ?? 'Document Standard',
                'description' => $archive->description ?? 'Document d\'archive',
                'format' => $archive->format ?? 'pdf',
                'department_id' => $archive->department_id,
                'sub_department_id' => $archive->sub_department_id,
                'departement' => $archive->department?->name ?? 'Département Général',
                'sub_departement' => $archive->subDepartment?->name ?? null,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'simulated' => true,
            ])
            ->log("Consultation de l'archive N°{$archive->id} (« {$archive->description} ») par {$user->name}");

        $this->forceTimestamps($activity, $timestamp);
        $this->stats['archive_consultations']++;

        return true;
    }

    /**
     * Simulate a realistic Archive File Download.
     */
    protected function simulateArchiveDownload(Collection $users, Collection $archives, Carbon $timestamp): bool
    {
        if ($archives->isEmpty() || $users->isEmpty()) {
            return false;
        }

        /** @var User $user */
        $user = $users->random();

        $scopedArchives = $user->department_id
            ? $archives->filter(fn ($a) => $a->department_id === $user->department_id)
            : $archives;

        /** @var Archive $archive */
        $archive = $scopedArchives->isNotEmpty() ? $scopedArchives->random() : $archives->random();

        $ip = $this->randomIp();
        $userAgent = $this->randomUserAgent();

        $activity = activity('archives')
            ->performedOn($archive)
            ->causedBy($user)
            ->event('archive.download')
            ->withProperties([
                'archive_id' => $archive->id,
                'typearchive' => $archive->archiveType?->name ?? 'Document Standard',
                'description' => $archive->description ?? 'Archive numérique',
                'filepath' => $archive->filepath ?? 'media/archives/document.pdf',
                'format' => $archive->format ?? 'pdf',
                'departement' => $archive->department?->name ?? 'Département Général',
                'ip' => $ip,
                'user_agent' => $userAgent,
                'simulated' => true,
            ])
            ->log("Téléchargement du document d'archive N°{$archive->id} (« {$archive->description} ») par {$user->name}");

        $this->forceTimestamps($activity, $timestamp);
        $this->stats['archive_downloads']++;

        return true;
    }

    /**
     * Simulate an Archive Search event.
     */
    protected function simulateArchiveSearch(Collection $users, Collection $departments, Carbon $timestamp): bool
    {
        if ($users->isEmpty()) {
            return false;
        }

        /** @var User $user */
        $user = $users->random();
        $term = $this->sampleSearchTerms[array_rand($this->sampleSearchTerms)];
        $deptName = $departments->isNotEmpty() ? $departments->random()->name : 'Tous départements';

        $ip = $this->randomIp();
        $userAgent = $this->randomUserAgent();

        $activity = activity('archives')
            ->causedBy($user)
            ->event('archive.search')
            ->withProperties([
                'search' => $term,
                'departement' => $deptName,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'simulated' => true,
            ])
            ->log("Recherche d'archives avec critères (« {$term} ») par {$user->name}");

        $this->forceTimestamps($activity, $timestamp);
        $this->stats['archive_searches']++;

        return true;
    }

    /**
     * Simulate a Personnel Dossier Consultation.
     */
    protected function simulatePersonnelConsultation(Collection $users, Collection $personnels, Carbon $timestamp): bool
    {
        if ($personnels->isEmpty() || $users->isEmpty()) {
            return false;
        }

        /** @var User $user */
        $user = $users->random();
        /** @var Personnel $personnel */
        $personnel = $personnels->random();

        $ip = $this->randomIp();
        $userAgent = $this->randomUserAgent();

        $activity = activity('personnel')
            ->performedOn($personnel)
            ->causedBy($user)
            ->event('personnel.consultation')
            ->withProperties([
                'personnel_id' => $personnel->id,
                'matricule' => $personnel->matricule ?? 'N/A',
                'name' => $personnel->name ?? 'Agent Inconnu',
                'departement' => $personnel->department?->name ?? 'Ressources Humaines',
                'ip' => $ip,
                'user_agent' => $userAgent,
                'simulated' => true,
            ])
            ->log("Consultation du dossier de l'agent {$personnel->name} (Matricule: {$personnel->matricule}) par {$user->name}");

        $this->forceTimestamps($activity, $timestamp);
        $this->stats['personnel_consultations']++;

        return true;
    }

    /**
     * Simulate a Personnel Dossier Archive ZIP Download.
     */
    protected function simulatePersonnelDownload(Collection $users, Collection $personnels, Carbon $timestamp): bool
    {
        if ($personnels->isEmpty() || $users->isEmpty()) {
            return false;
        }

        /** @var User $user */
        $user = $users->random();
        /** @var Personnel $personnel */
        $personnel = $personnels->random();

        $ip = $this->randomIp();
        $userAgent = $this->randomUserAgent();

        $activity = activity('personnel')
            ->performedOn($personnel)
            ->causedBy($user)
            ->event('personnel.download')
            ->withProperties([
                'personnel_id' => $personnel->id,
                'matricule' => $personnel->matricule ?? 'N/A',
                'name' => $personnel->name ?? 'Agent Inconnu',
                'departement' => $personnel->department?->name ?? 'Ressources Humaines',
                'ip' => $ip,
                'user_agent' => $userAgent,
                'simulated' => true,
            ])
            ->log("Téléchargement du dossier complet de l'agent {$personnel->name} (Matricule: {$personnel->matricule}) par {$user->name}");

        $this->forceTimestamps($activity, $timestamp);
        $this->stats['personnel_downloads']++;

        return true;
    }

    /**
     * Simulate a Piece / Document Reference Consultation.
     */
    protected function simulatePieceConsultation(Collection $users, Collection $pieces, Carbon $timestamp): bool
    {
        if ($pieces->isEmpty() || $users->isEmpty()) {
            return false;
        }

        /** @var User $user */
        $user = $users->random();
        /** @var Piece $piece */
        $piece = $pieces->random();

        $ip = $this->randomIp();
        $userAgent = $this->randomUserAgent();

        $activity = activity('pieces')
            ->performedOn($piece)
            ->causedBy($user)
            ->event('piece.consultation')
            ->withProperties([
                'piece_id' => $piece->id,
                'name' => $piece->name,
                'code' => $piece->code ?? null,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'simulated' => true,
            ])
            ->log("Consultation du référentiel de pièce « {$piece->name} » par {$user->name}");

        $this->forceTimestamps($activity, $timestamp);
        $this->stats['piece_consultations']++;

        return true;
    }

    /**
     * Simulate a Departmental Directory Consultation.
     */
    protected function simulateDepartmentConsultation(Collection $users, Collection $departments, Carbon $timestamp): bool
    {
        if ($departments->isEmpty() || $users->isEmpty()) {
            return false;
        }

        /** @var User $user */
        $user = $users->random();
        /** @var Department $department */
        $department = $departments->random();

        $ip = $this->randomIp();
        $userAgent = $this->randomUserAgent();

        $activity = activity('departments')
            ->performedOn($department)
            ->causedBy($user)
            ->event('department.consultation')
            ->withProperties([
                'department_id' => $department->id,
                'name' => $department->name,
                'code' => $department->code ?? null,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'simulated' => true,
            ])
            ->log("Consultation de l'annuaire structurel du département « {$department->name} » par {$user->name}");

        $this->forceTimestamps($activity, $timestamp);
        $this->stats['department_consultations']++;

        return true;
    }

    /**
     * Simulate a User Authentication Event (Login / Logout).
     */
    protected function simulateAuthEvent(Collection $users, Carbon $timestamp): bool
    {
        if ($users->isEmpty()) {
            return false;
        }

        /** @var User $user */
        $user = $users->random();
        $isLogin = rand(1, 10) <= 8;
        $event = $isLogin ? 'auth.login' : 'auth.logout';
        $description = $isLogin
            ? "Connexion réussie de l'utilisateur {$user->name} au portail sécurisé"
            : "Déconnexion de l'utilisateur {$user->name}";

        $ip = $this->randomIp();
        $userAgent = $this->randomUserAgent();

        $activity = activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->event($event)
            ->withProperties([
                'user_id' => $user->id,
                'email' => $user->email,
                'matricule' => $user->matricule ?? null,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'simulated' => true,
            ])
            ->log($description);

        $this->forceTimestamps($activity, $timestamp);
        $this->stats['auth_events']++;

        return true;
    }

    /**
     * Force specific created_at and updated_at timestamps on an Activity log.
     */
    protected function forceTimestamps(Activity $activity, Carbon $timestamp): void
    {
        $activity->timestamps = false;
        $activity->created_at = $timestamp;
        $activity->updated_at = $timestamp;
        $activity->saveQuietly();
    }

    /**
     * Generate a realistic timestamp during working hours for a given date.
     */
    protected function generateRandomTimeForDate(Carbon $date): Carbon
    {
        $startHour = (int) config('demo.business_hours.start', 8);
        $endHour = (int) config('demo.business_hours.end', 18);

        // Créneaux horaires avec pic d'activité naturel à 10h et 14h
        $hour = rand($startHour, $endHour - 1);
        $minute = rand(0, 59);
        $second = rand(0, 59);

        return $date->copy()->setTime($hour, $minute, $second);
    }

    /**
     * Generate a realistic intranet IP address.
     */
    protected function randomIp(): string
    {
        $subnets = ['192.168.1.', '192.168.10.', '10.0.1.', '172.16.2.'];
        $subnet = $subnets[array_rand($subnets)];

        return $subnet.rand(10, 250);
    }

    /**
     * Pick a random realistic User-Agent string.
     */
    protected function randomUserAgent(): string
    {
        return $this->userAgents[array_rand($this->userAgents)];
    }

    /**
     * Select a random key from an array according to integer weights.
     *
     * @param  array<string, int>  $weights
     */
    protected function weightedRandom(array $weights): string
    {
        $totalWeight = array_sum($weights);
        $rand = rand(1, $totalWeight);
        $current = 0;

        foreach ($weights as $key => $weight) {
            $current += $weight;
            if ($rand <= $current) {
                return $key;
            }
        }

        return (string) array_key_first($weights);
    }

    /**
     * Safely load Eloquent collections with try/catch to protect against schema or driver issues.
     */
    protected function safeLoad(callable $callback): Collection
    {
        try {
            return $callback();
        } catch (\Throwable $e) {
            Log::warning("Simulation démo : échec du chargement sécurisé : {$e->getMessage()}");

            return collect();
        }
    }
}
