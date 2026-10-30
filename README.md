# ArchiDoc — Système de Gestion Électronique de Documents (GED) & Archivage Sécurisé

<p align="center">
  <img src="public/media/img/logo_archidoc_dgb.png" width="120" alt="Logo ArchiDoc" style="border-radius: 50%;">
</p>

<p align="center">
  <strong>Solution moderne, neutre et modulaire de dématérialisation, d'archivage physique/numérique, de gestion des dossiers du personnel et de traçabilité opérationnelle.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Database-PostgreSQL%20%7C%20SQLite-4169E1?style=flat-square&logo=postgresql&logoColor=white" alt="Database">
  <img src="https://img.shields.io/badge/Queue-Database-38B2AC?style=flat-square" alt="Queue">
  <img src="https://img.shields.io/badge/TailwindCSS-Vite-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/Tests-Pest%20(219%20passed)-10B981?style=flat-square" alt="Tests">
</p>

---

## 📌 Présentation Générale

**ArchiDoc** est une plateforme professionnelle conçue pour répondre aux impératifs rigoureux de conservation, de consultation, d'audit et de conformité des archives institutionnelles et privées. 

Initialement développée avec un haut niveau d'exigence, l'application est conçue pour être **neutre et entièrement adaptable** à toute organisation (entreprises privées, ministères, banques, universités, hôpitaux, administrations publiques).

---

## 🚀 Fonctionnalités Clés

### 1. Gestion des Archives Physiques & Numériques
- **Cotation & Typologie** : Classification par types d'archives cohérents avec Durée d'Utilité Administrative (DUA) et règle de sort final (archivage définitif, versement, élimination).
- **Localisation Physique Précise** : Gestion multi-sites, bâtiments, salles d'archives, travées, étagères et boîtes.
- **Gestion Numérique** : Téléversement, contrôle d'intégrité, prévisualisation in-app (PDF, images) et téléchargement direct sécurisé.

### 2. Gestion des Dossiers du Personnel
- Fiches individuelles des agents et collaborateurs (matricule, identité, affectation départementale, statut).
- Suivi documentaire des pièces requises (obligatoires et facultatives : CV, contrats, diplômes, actes d'état civil, etc.).
- Génération et téléchargement à la volée de l'ensemble du dossier de l'agent en archive ZIP structurée.

### 3. Contrôle d'Accès Fin & Cloisonnement (RBAC Granulaire)
- **Hiérarchie à 3 Rôles Standards** :
  - `Super privilégié` : Administrateur général, accès transverse, configuration globale et gestion des habilitations.
  - `Privilégié` : Responsable de département / service, accès restreint aux archives et personnels de son périmètre structurel.
  - `Classique` : Agent opérationnel avec droits ciblés de consultation et d'archivage.
- **Matrice de Permissions Granulaires** : Contrôle fin par modèle (`Archive`, `Personnel`, `Piece`, `Department`, `User`, `Role`, `Setting`, `ActivityLog`) et par action (`create`, `read`, `update`, `delete`, `download`).
- **Surcharges Individuelles** : Possibilité d'accorder ou de révoquer des permissions spécifiques à un utilisateur sans modifier son rôle global.

### 4. Boîte Noire & Traçabilité Complète (Audit Trail)
- Journalisation exhaustive de chaque action via **Spatie Activitylog**.
- Traces complètes des consultations et téléchargements d'archives et de dossiers du personnel avec horodatage, adresse IP et User-Agent.
- Tableaux de bord analytiques dédiés aux consultations avec graphiques de tendance sur 14 jours, répartition par département et par type de document.
- Exportations des journaux au format **CSV**, **JSON** et **TXT**.

### 5. Réinitialisation de Mot de Passe par E-mail
- Transmission asynchrone sécurisée par file d'attente (Queue database).
- Politique de tolérance aux pannes réseau avec jusqu'à 5 tentatives et backoff exponentiel (`[5s, 15s, 30s]`).
- Détection des demandes répétées avec bannières informatives pour inviter l'utilisateur à vérifier sa boîte de réception et ses spams sans renvoyer de doublons inutiles.
- Normalisation automatique de la casse et des espaces des adresses e-mail.
- Déconnexion automatique des sessions actives lors du clic sur le lien d'e-mail pour garantir un accès immédiat à l'écran de réinitialisation.

### 6. Automate de Simulation Démo (Arrière-plan)
- Commande Artisan dédiée (`php artisan demo:simulate-activity`) pilotée par le planificateur Laravel (`routes/console.php`).
- Génère en continu des activités réalistes et multi-modèles (consultations, téléchargements, recherches, connexions) pendant les heures de bureau (8h–18h, jours ouvrés).
- **Interrupteur simple dans le `.env`** (`DEMO_SIMULATION_ENABLED=true/false`).
- **Tolérance totale aux pannes** : chaque bloc de simulation est isolé dans son propre gestionnaire d'exception ; l'absence de données sur un modèle ne bloque jamais l'exécution des autres.
- Capacité de **rétro-remplissage immédiat** sur plusieurs jours (`--days=14`) pour peupler instantanément un serveur neuf.

### 7. Personnalisation Dynamique & Identité Graphique
- Paramétrage de la charte graphique en base de données (couleurs primaire, secondaire, d'accentuation, de succès et d'erreur).
- Gestion du logo d'entreprise, favicon, image de fond de connexion, nom d'application et textes de footer.
- Intégration des crédits développeur via les variables d'environnement `DEV_NAME` et `DEV_ADR`.

---

## 🛠️ Stack Technique

- **Langage** : PHP 8.4
- **Framework** : Laravel 11.x
- **Base de données** : PostgreSQL 15+ (Recommandé en production / VPS) ou SQLite 3 (Développement local / Tests)
- **Frontend** : Blade Templates, Tailwind CSS 3, Vite, Alpine.js
- **Files d'attente** : Laravel Database Queue
- **Planificateur** : Laravel Native Scheduler
- **Tests** : Pest PHP 3.x (219 tests fonctionnels et unitaires)
- **Style & Qualité** : Laravel Pint

---

## 📦 Guide d'Installation & Déploiement

### 1. Prérequis
- PHP 8.4+ avec extensions : `pdo`, `pdo_pgsql` (ou `pdo_sqlite`), `mbstring`, `openssl`, `curl`, `zip`, `fileinfo`.
- Composer 2.x
- Node.js 18+ & NPM
- Serveur PostgreSQL ou SQLite

### 2. Cloner le Dépôt
```bash
git clone <url-du-depot> archidoc
cd archidoc
```

### 3. Installer les Dépendances PHP & JavaScript
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
npm install
```

### 4. Configurer l'Environnement
Dupliquez le fichier `.env.example` en `.env` :
```bash
cp .env.example .env
php artisan key:generate
```

Éditez le fichier `.env` selon vos besoins :
- **Base de données** : Configurez votre connexion PostgreSQL ou SQLite.
- **Messagerie** : Configurez vos identifiants SMTP pour l'envoi des e-mails.
- **Crédits Développeur** : Renseignez `DEV_NAME` et `DEV_ADR`.
- **Simulation Démo** : Activez `DEMO_SIMULATION_ENABLED=true` si l'application est en environnement de démonstration.

### 5. Exécuter les Migrations et Seeders Neutres
```bash
# Crée les tables et initialise les données neutres et cohérentes
php artisan migrate --seed
```

Les seeders par défaut injectent :
- Les départements et sous-départements d'entreprise générale (`GenericDepartmentSeeder`).
- 22 types d'archives réalistes avec DUA et sort final (`ArchiveTypeSeeder`).
- Les emplacements de stockage physique (`ArchiveLocationSeeder`).
- Les dossiers du personnel (`PersonnelSeeder`).
- Les paramètres graphiques par défaut (`SettingSeeder`).

### 6. Créer le Lien Symbolique de Stockage
```bash
php artisan storage:link
```

### 7. Compiler les Assets Frontend
```bash
# Pour la production / VPS :
npm run build

# Pour le développement local :
npm run dev
```

### 8. Lancer le Worker de File d'Attente
Les e-mails de réinitialisation et traitements asynchrones utilisent la table `jobs` :
```bash
php artisan queue:work --tries=3 --timeout=90
```

> **Conseil en production** : Supervisez ce processus avec un démon comme **Supervisor** ou **systemd**.

---

## ⏰ Configuration du Planificateur (CRON sur VPS)

Pour que l'automate de simulation démo et les tâches planifiées fonctionnent automatiquement, ajoutez cette ligne à la table crontab du serveur (`crontab -e`) :

```bash
* * * * * cd /chemin/absolu/vers/archidoc && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🎲 Utilisation de l'Automate de Simulation Démo

L'automate peut être activé via `.env` :
```dotenv
DEMO_SIMULATION_ENABLED=true
DEMO_SIMULATION_MIN=5
DEMO_SIMULATION_MAX=15
```

Vous pouvez également l'exécuter manuellement en ligne de commande :

```bash
# Exécution ponctuelle forcée de 10 activités
php artisan demo:simulate-activity --force --count=10

# Rétro-peuplement des 14 derniers jours ouvrés lors d'un déploiement neuf
php artisan demo:simulate-activity --days=14 --force

# Mode incrémental horaire (exécuté automatiquement par le scheduler)
php artisan demo:simulate-activity --hourly --force
```

---

## 🧪 Tests & Assurance Qualité

Le projet est couvert par une suite complète de tests fonctionnels et unitaires Pest :

```bash
# Exécuter l'ensemble de la suite de tests
php artisan test --compact

# Ou directement via Pest
vendor/bin/pest --compact

# Vérifier et corriger le formattage du code selon les conventions Laravel
vendor/bin/pint --dirty --format agent
```

---

## 👨‍💻 Développeur & Auteur

- **Développeur** : MOAFO GAÜS
- **Portfolio / Contact** : [https://moafogaus.abndns.com](https://moafogaus.abndns.com)

---

## 📄 Licence

Ce projet est sous licence propriétaire pour le compte de l'organisation déployeuse. Tous droits réservés.
