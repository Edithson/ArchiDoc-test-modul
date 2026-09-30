<?php

namespace Database\Seeders;

use App\Models\Archive;
use App\Models\Department;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds for Activity Log / Black Box demo.
     */
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->create([
            'name' => 'Admin ArchiDoc',
            'email' => 'admin@archidoc.cm',
            'matricule' => 'MAT-0001',
            'roles' => 'super privilégé',
        ]);

        $users = User::all();
        if ($users->count() < 3) {
            $users = User::factory(5)->create();
        }

        $departments = Department::all();
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64; rv:129.0) Gecko/20100101 Firefox/129.0',
        ];
        $ips = ['192.168.1.10', '192.168.1.45', '10.0.2.15', '172.16.4.102', '192.168.1.100'];

        // 1. Événements Authentification & Sécurité (auth)
        $authScenarios = [
            ['event' => 'auth.login', 'desc' => 'Connexion réussie de l\'utilisateur au système', 'status' => 'success'],
            ['event' => 'auth.login', 'desc' => 'Connexion récurrente de l\'agent sur le portail', 'status' => 'success'],
            ['event' => 'auth.failed_login', 'desc' => 'Échec d\'authentification : mot de passe incorrect', 'status' => 'failed', 'attempted' => 'agent.invalide@minfi.cm'],
            ['event' => 'auth.failed_login', 'desc' => 'Échec d\'authentification : matricule introuvable', 'status' => 'failed', 'attempted' => 'hacker@externe.cm'],
            ['event' => 'auth.logout', 'desc' => 'Déconnexion volontaire de la session utilisateur', 'status' => 'logout'],
            ['event' => 'auth.lockout', 'desc' => 'Verrouillage temporaire IP : trop de tentatives infructueuses', 'status' => 'lockout'],
        ];

        for ($i = 0; $i < 20; $i++) {
            $scenario = $authScenarios[array_rand($authScenarios)];
            $user = $users->random();
            $daysAgo = rand(0, 25);
            $hoursAgo = rand(0, 23);
            $createdAt = now()->subDays($daysAgo)->subHours($hoursAgo);

            Activity::create([
                'log_name' => 'auth',
                'description' => $scenario['desc'],
                'event' => $scenario['event'],
                'causer_type' => User::class,
                'causer_id' => $user->id,
                'properties' => [
                    'ip' => $ips[array_rand($ips)],
                    'user_agent' => $userAgents[array_rand($userAgents)],
                    'email' => $scenario['attempted'] ?? $user->email,
                    'matricule' => $user->matricule,
                ],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // 2. Événements Métier & CRUD Modèles (default)
        $departmentsList = $departments->take(5);
        foreach ($departmentsList as $dept) {
            $createdAt = now()->subDays(rand(10, 28));
            Activity::create([
                'log_name' => 'default',
                'description' => "Création du département : {$dept->name}",
                'event' => 'created',
                'subject_type' => Department::class,
                'subject_id' => $dept->id,
                'causer_type' => User::class,
                'causer_id' => $admin->id,
                'attribute_changes' => [
                    'attributes' => [
                        'name' => $dept->name,
                        'description' => $dept->description,
                    ],
                ],
                'properties' => [
                    'attributes' => [
                        'name' => $dept->name,
                        'description' => $dept->description,
                    ],
                ],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Simulation d'une mise à jour ultérieure
            $updateDate = (clone $createdAt)->addDays(rand(1, 5));
            Activity::create([
                'log_name' => 'default',
                'description' => "Mise à jour des informations du groupe d'accès : {$dept->name}",
                'event' => 'updated',
                'subject_type' => Department::class,
                'subject_id' => $dept->id,
                'causer_type' => User::class,
                'causer_id' => $users->random()->id,
                'attribute_changes' => [
                    'old' => ['description' => 'Description initiale du service'],
                    'attributes' => ['description' => $dept->description ?? 'Mise à jour officielle du cabinet'],
                ],
                'properties' => [
                    'old' => ['description' => 'Description initiale du service'],
                    'attributes' => ['description' => $dept->description ?? 'Mise à jour officielle du cabinet'],
                ],
                'created_at' => $updateDate,
                'updated_at' => $updateDate,
            ]);
        }

        // Simuler suppression logique d'archive (Soft Delete audit)
        for ($k = 1; $k <= 4; $k++) {
            $deleteDate = now()->subDays(rand(1, 14));
            Activity::create([
                'log_name' => 'default',
                'description' => "Suppression logique d'un document d'archive #DOC-2026-00{$k}",
                'event' => 'deleted',
                'subject_type' => Archive::class,
                'subject_id' => 100 + $k,
                'causer_type' => User::class,
                'causer_id' => $admin->id,
                'properties' => [
                    'old' => [
                        'title' => "Rapport Annuel Audit DGB {$k}",
                        'reference' => "REF-DGB-2026-00{$k}",
                        'status' => 'archived',
                    ],
                    'deleted_by' => $admin->name,
                    'purge_notice' => 'Le fichier numérique a été supprimé physiquement du disque mais l\'enregistrement persiste pour traçabilité.',
                ],
                'created_at' => $deleteDate,
                'updated_at' => $deleteDate,
            ]);
        }

        // 3. Événements Diagnostic Système & Exceptions (system)
        $systemExceptions = [
            [
                'desc' => 'Erreur d\'unicité de la base de données (Constraint Violation)',
                'class' => 'Illuminate\Database\QueryException',
                'file' => '/app/Http/Controllers/UserController.php',
                'line' => 64,
                'message' => 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'MAT-2026-099\' for key \'users_matricule_unique\'',
                'trace' => [
                    '#0 /vendor/laravel/framework/src/Illuminate/Database/Connection.php(760): Illuminate\Database\Connection->run()',
                    '#1 /app/Http/Controllers/UserController.php(64): App\Models\User::create()',
                    '#2 /vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php(48): App\Http\Controllers\UserController->store()',
                ],
            ],
            [
                'desc' => 'Fichier numérique d\'archive introuvable sur le stockage local',
                'class' => 'Illuminate\Contracts\Filesystem\FileNotFoundException',
                'file' => '/app/Http/Controllers/ArchiveController.php',
                'line' => 112,
                'message' => 'Fichier /storage/app/archives/2026/DOC-ARCHIVE-99.pdf introuvable sur le volume',
                'trace' => [
                    '#0 /app/Http/Controllers/ArchiveController.php(112): Illuminate\Support\Facades\Storage::download()',
                    '#1 /vendor/laravel/framework/src/Illuminate/Routing/Controller.php(54): App\Http\Controllers\ArchiveController->download()',
                ],
            ],
            [
                'desc' => 'Seuil maximal de requêtes dépassé (Rate Limit Throttle)',
                'class' => 'Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException',
                'file' => '/vendor/laravel/framework/src/Illuminate/Routing/Middleware/ThrottleRequests.php',
                'line' => 88,
                'message' => 'Trop de requêtes HTTP simultanées depuis l\'adresse IP 192.168.1.100',
                'trace' => [
                    '#0 /vendor/laravel/framework/src/Illuminate/Routing/Middleware/ThrottleRequests.php(88): buildException()',
                ],
            ],
        ];

        foreach ($systemExceptions as $exc) {
            $errDate = now()->subDays(rand(0, 10))->subHours(rand(1, 12));
            Activity::create([
                'log_name' => 'system',
                'description' => $exc['desc'],
                'event' => 'system.error',
                'causer_type' => null,
                'causer_id' => null,
                'properties' => [
                    'exception_class' => $exc['class'],
                    'file' => $exc['file'],
                    'line' => $exc['line'],
                    'message' => $exc['message'],
                    'trace' => $exc['trace'],
                ],
                'created_at' => $errDate,
                'updated_at' => $errDate,
            ]);
        }

        // 4. Événements Consultations & Téléchargements d'Archives (archive.consultation, archive.download)
        $archives = Archive::all();
        if ($archives->isEmpty()) {
            $archives = Archive::factory(10)->create();
        }

        $departmentsListNames = [
            'CAB DGB', 'DCOB', 'DDPP', 'DI', 'DPB', 'DPC',
            'DREF', 'S-DAG', 'SGDB', 'SO', 'CABINET MINFI',
        ];

        $documentTypes = [
            'Rapport d\'Exécution Budgétaire',
            'Arrêté Ministériel',
            'Fiche de Solde & Traitement',
            'Bordereau d\'Expédition',
            'Note de Service Organique',
            'Décret d\'Habilitation DGB',
            'Procès-Verbal de Recette',
            'Dossier de Cadre Budgétaire',
        ];

        $formats = ['pdf', 'pdf', 'pdf', 'docx', 'scan', 'xlsx'];

        for ($j = 0; $j < 60; $j++) {
            $user = $users->random();
            $archive = $archives->random();
            $dept = $departmentsListNames[array_rand($departmentsListNames)];
            $docType = $documentTypes[array_rand($documentTypes)];
            $fmt = $formats[array_rand($formats)];

            // Répartition réaliste sur les 14 derniers jours
            $daysAgo = rand(0, 13);
            $hoursAgo = rand(1, 23);
            $createdAt = now()->subDays($daysAgo)->subHours($hoursAgo);

            $isDownload = rand(1, 5) === 5;
            $eventType = $isDownload ? 'archive.download' : 'archive.consultation';
            $logAction = $isDownload ? 'Téléchargement archive' : 'Consultation archive';

            Activity::create([
                'log_name' => 'archive',
                'description' => "{$logAction} ".strtoupper($fmt)." : {$docType} — {$archive->description}",
                'event' => $eventType,
                'subject_type' => Archive::class,
                'subject_id' => $archive->id,
                'causer_type' => User::class,
                'causer_id' => $user->id,
                'properties' => [
                    'archive_id' => $archive->id,
                    'typearchive' => $docType,
                    'description' => $archive->description,
                    'format' => $fmt,
                    'departement' => $dept,
                    'ip' => $ips[array_rand($ips)],
                    'user_agent' => $userAgents[array_rand($userAgents)],
                ],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // 5. Événements Consultations & Téléchargements de Dossiers Personnel (personnel.consultation, personnel.download)
        $personnels = Personnel::all();
        if ($personnels->isEmpty()) {
            $personnels = Personnel::factory(10)->create();
        }

        for ($p = 0; $p < 60; $p++) {
            $user = $users->random();
            $personnel = $personnels->random();
            $dept = $departmentsListNames[array_rand($departmentsListNames)];

            $daysAgo = rand(0, 13);
            $hoursAgo = rand(1, 23);
            $createdAt = now()->subDays($daysAgo)->subHours($hoursAgo);

            $isDownload = rand(1, 4) === 4;
            $eventType = $isDownload ? 'personnel.download' : 'personnel.consultation';
            $logAction = $isDownload ? 'Téléchargement ZIP dossier agent' : 'Consultation fiche agent';

            Activity::create([
                'log_name' => 'personnel',
                'description' => "{$logAction} : {$personnel->name} ({$personnel->matricule})",
                'event' => $eventType,
                'subject_type' => Personnel::class,
                'subject_id' => $personnel->id,
                'causer_type' => User::class,
                'causer_id' => $user->id,
                'properties' => [
                    'personnel_id' => $personnel->id,
                    'name' => $personnel->name,
                    'matricule' => $personnel->matricule,
                    'departement' => $dept,
                    'ip' => $ips[array_rand($ips)],
                    'user_agent' => $userAgents[array_rand($userAgents)],
                ],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
