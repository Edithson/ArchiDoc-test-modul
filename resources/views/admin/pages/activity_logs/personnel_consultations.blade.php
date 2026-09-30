@extends('admin.layout.app')

@section('title', 'Tableau de bord des Consultations Dossiers Personnel — ArchiDoc DGB')
@section('meta_description', 'Analyse statistique et journal d\'audit complet des consultations des dossiers d\'agents du personnel — ArchiDoc DGB.')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page & Navigation -->
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2.5">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-indigo-800 text-white shadow-md shadow-indigo-800/20">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </span>
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Consultations Dossiers Personnel — Analytics & Traçabilité</h1>
          <p class="text-xs text-gray-500 font-medium">Tableau de bord statistique et suivi individuel des consultations des dossiers d'agents du personnel DGB.</p>
        </div>
      </div>
    </div>

    <!-- Sub-navigation & Exportation -->
    <div class="flex flex-wrap items-center gap-2.5">
      <!-- Menu d'exportation -->
      <div class="relative inline-block text-left">
        <button id="export-trigger" type="button" onclick="toggleExportDropdown()" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-700/20 hover:from-emerald-700 hover:to-emerald-800 transition-all focus:outline-none">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
          Exporter les Consultations
          <svg class="h-3.5 w-3.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>

        <div id="export-menu" class="absolute right-0 z-50 mt-2 hidden w-56 origin-top-right rounded-xl border border-gray-100 bg-white p-2 shadow-xl ring-1 ring-black/5">
          <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-gray-400">Format d'exportation</div>
          <a href="{{ route('activity-logs.personnel-consultations', array_merge(request()->query(), ['export' => 'csv'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">
            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-extrabold text-emerald-800">CSV</span>
            Export Tableur (Excel)
          </a>
          <a href="{{ route('activity-logs.personnel-consultations', array_merge(request()->query(), ['export' => 'json'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-800">
            <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-extrabold text-blue-800">JSON</span>
            Payload API (JSON)
          </a>
          <a href="{{ route('activity-logs.personnel-consultations', array_merge(request()->query(), ['export' => 'txt'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-100 hover:text-gray-900">
            <span class="rounded bg-gray-200 px-1.5 py-0.5 text-[10px] font-extrabold text-gray-800">TXT</span>
            Journal Texte (Syslog)
          </a>
        </div>
      </div>

      <!-- Liens de navigation Boîte Noire -->
      <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs font-bold text-gray-700 shadow-2xs transition hover:bg-gray-50">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
        Arbre Global
      </a>
      <a href="{{ route('activity-logs.archives-consultations') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-sky-200 bg-sky-50 px-3.5 py-2 text-xs font-bold text-sky-800 transition hover:bg-sky-100">
        <svg class="h-4 w-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Consultations Archives
      </a>
    </div>
  </div>

  <!-- Cartes d'Indicateurs Clés (KPI) -->
  <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Card 1: Total Consultations Dossiers -->
    <div class="relative overflow-hidden rounded-2xl border border-indigo-100 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Consultations</p>
          <h3 class="mt-1 text-2xl font-black tracking-tight text-gray-900">{{ number_format($totalConsultations, 0, ',', ' ') }}</h3>
          <p class="mt-1 text-[11px] font-medium text-indigo-700">Accès enregistrés aux dossiers agents</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-700">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Card 2: Consultations Aujourd'hui -->
    <div class="relative overflow-hidden rounded-2xl border border-emerald-100 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Aujourd'hui</p>
          <h3 class="mt-1 text-2xl font-black tracking-tight text-emerald-600">{{ number_format($todayConsultations, 0, ',', ' ') }}</h3>
          <p class="mt-1 text-[11px] font-medium text-emerald-700">Accès enregistrés ce jour</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Card 3: Dossiers Distincts Consultés -->
    <div class="relative overflow-hidden rounded-2xl border border-amber-100 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Dossiers Distincts</p>
          <h3 class="mt-1 text-2xl font-black tracking-tight text-amber-600">{{ number_format($uniquePersonnelsCount, 0, ',', ' ') }}</h3>
          <p class="mt-1 text-[11px] font-medium text-amber-700">Agents distincts consultés</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Card 4: Top Consultateur -->
    <div class="relative overflow-hidden rounded-2xl border border-purple-100 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Consultateur Majeur</p>
          <h3 class="mt-1 text-base font-extrabold tracking-tight text-gray-900 truncate max-w-[170px]">
            {{ $topUser ? $topUser->name : 'Aucun' }}
          </h3>
          <p class="mt-1 text-[11px] font-medium text-purple-700">
            {{ $topUserCount }} accès ({{ $topUser ? ($topUser->matricule ?? 'SYS') : '—' }})
          </p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-700">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Graphiques de Statistiques Analytiques -->
  <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Graphique 1: Tendance Temporelle (14 Jours) -->
    <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
      <div class="mb-4 flex items-center justify-between">
        <div>
          <h3 class="text-sm font-extrabold tracking-tight text-gray-900">Tendance Quotidienne (14 Derniers Jours)</h3>
          <p class="text-xs text-gray-500 font-medium">Volume des consultations de dossiers d'agents par date.</p>
        </div>
        <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-700">Vue Temporelle</span>
      </div>
      <div class="h-64 w-full">
        <canvas id="dailyTrendChart"></canvas>
      </div>
    </div>

    <!-- Graphique 2: Répartition par Action -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
      <div class="mb-4 flex items-center justify-between">
        <div>
          <h3 class="text-sm font-extrabold tracking-tight text-gray-900">Nature des Actions</h3>
          <p class="text-xs text-gray-500 font-medium">Proportion des types d'opérations sur fiches agents.</p>
        </div>
        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">Actions</span>
      </div>
      <div class="h-64 w-full flex items-center justify-center">
        <canvas id="actionTypesChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Graphique 3: Top Départements Demandeurs -->
  <div class="mb-8 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
    <div class="mb-4 flex items-center justify-between">
      <div>
        <h3 class="text-sm font-extrabold tracking-tight text-gray-900">Consultations par Direction / Département</h3>
        <p class="text-xs text-gray-500 font-medium">Origine des demandes de consultation de dossiers du personnel.</p>
      </div>
      <span class="rounded-full bg-purple-50 px-2.5 py-1 text-[11px] font-bold text-purple-700">Organigramme DGB</span>
    </div>
    <div class="h-56 w-full">
      <canvas id="topDeptsChart"></canvas>
    </div>
  </div>

  <!-- Barre de Recherche & Filtres Multi-critères -->
  <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
    <form method="GET" action="{{ route('activity-logs.personnel-consultations') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5">

      <!-- Recherche Textuelle -->
      <div class="lg:col-span-2">
        <label for="search" class="block text-xs font-bold text-gray-700">Recherche globale</label>
        <div class="relative mt-1">
          <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nom agent, matricule, consultateur..." class="w-full rounded-xl border border-gray-300 py-2 pl-9 pr-3 text-xs focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600">
          <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <!-- Filtre Département -->
      <div>
        <label for="departement" class="block text-xs font-bold text-gray-700">Direction / Département</label>
        <select name="departement" id="departement" class="mt-1 w-full rounded-xl border border-gray-300 py-2 px-3 text-xs font-semibold focus:border-indigo-600 focus:outline-none">
          <option value="all" {{ $departementFilter === 'all' ? 'selected' : '' }}>Tous les départements</option>
          @foreach($departmentsList as $dept)
            <option value="{{ $dept }}" {{ $departementFilter === $dept ? 'selected' : '' }}>{{ $dept }}</option>
          @endforeach
        </select>
      </div>

      <!-- Filtre Période -->
      <div>
        <label for="date_range" class="block text-xs font-bold text-gray-700">Période d'accès</label>
        <select name="date_range" id="date_range" class="mt-1 w-full rounded-xl border border-gray-300 py-2 px-3 text-xs font-semibold focus:border-indigo-600 focus:outline-none">
          <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>Toutes les dates</option>
          <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
          <option value="7days" {{ $dateRange === '7days' ? 'selected' : '' }}>7 derniers jours</option>
          <option value="30days" {{ $dateRange === '30days' ? 'selected' : '' }}>30 derniers jours</option>
        </select>
      </div>

      <!-- Actions Filtrer / Réinitialiser -->
      <div class="flex items-end gap-2">
        <button type="submit" class="w-full rounded-xl bg-indigo-700 py-2 px-4 text-xs font-extrabold text-white shadow-xs hover:bg-indigo-800 transition">
          Filtrer
        </button>
        <a href="{{ route('activity-logs.personnel-consultations') }}" class="rounded-xl border border-gray-300 bg-gray-50 py-2 px-3 text-xs font-bold text-gray-700 hover:bg-gray-100">
          Reset
        </a>
      </div>

    </form>
  </div>

  <!-- Journal des Consultations Paginé -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xs">
    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex items-center justify-between">
      <h3 class="text-sm font-extrabold text-gray-900">Journal d'Audit des Consultations de Dossiers Agent</h3>
      <span class="text-xs text-gray-500 font-medium">Affichage de {{ $activities->firstItem() ?? 0 }} à {{ $activities->lastItem() ?? 0 }} sur {{ $activities->total() }} entrées</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-gray-100/70 text-[11px] font-bold uppercase tracking-wider text-gray-500">
          <tr>
            <th class="px-6 py-3.5">Horodatage</th>
            <th class="px-6 py-3.5">Action</th>
            <th class="px-6 py-3.5">Agent Cible (Dossier)</th>
            <th class="px-6 py-3.5">Consultateur (Auteur)</th>
            <th class="px-6 py-3.5">Département</th>
            <th class="px-6 py-3.5">Adresse IP</th>
            <th class="px-6 py-3.5 text-right">Audit</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($activities as $activity)
            <tr class="hover:bg-indigo-50/30 transition-colors">
              <!-- Horodatage -->
              <td class="px-6 py-4 font-mono font-medium text-gray-600 whitespace-nowrap">
                {{ $activity->created_at ? $activity->created_at->format('d/m/Y H:i:s') : '—' }}
                <span class="block text-[10px] text-gray-400">{{ $activity->created_at ? $activity->created_at->diffForHumans() : '' }}</span>
              </td>

              <!-- Action / Badge -->
              <td class="px-6 py-4 whitespace-nowrap">
                @if($activity->event === 'personnel.download')
                  <span class="inline-flex items-center gap-1.5 rounded-md bg-teal-100 px-2.5 py-1 text-[11px] font-bold text-teal-800">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Téléchargement ZIP
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 rounded-md bg-indigo-100 px-2.5 py-1 text-[11px] font-bold text-indigo-800">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Consultation Fiche
                  </span>
                @endif
              </td>

              <!-- Agent Cible -->
              <td class="px-6 py-4">
                <p class="font-extrabold text-gray-900">{{ $activity->properties['name'] ?? ($activity->subject->name ?? 'Agent Personnel') }}</p>
                <p class="text-[11px] font-mono text-indigo-700 font-bold">
                  Matricule: {{ $activity->properties['matricule'] ?? ($activity->subject->matricule ?? 'N/A') }}
                </p>
              </td>

              <!-- Consultateur -->
              <td class="px-6 py-4">
                @if($activity->causer)
                  <p class="font-bold text-gray-900">{{ $activity->causer->name }}</p>
                  <p class="text-[10px] text-gray-500">{{ $activity->causer->email }} · <span class="font-mono text-indigo-700">{{ $activity->causer->matricule }}</span></p>
                @else
                  <span class="font-bold text-gray-500">Système automatique</span>
                @endif
              </td>

              <!-- Département -->
              <td class="px-6 py-4">
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-bold text-gray-700">
                  {{ $activity->properties['departement'] ?? ($activity->causer->departement ?? 'DGB') }}
                </span>
              </td>

              <!-- Adresse IP -->
              <td class="px-6 py-4 font-mono text-gray-600 whitespace-nowrap">
                {{ $activity->properties['ip'] ?? '127.0.0.1' }}
              </td>

              <!-- Action / Inspection -->
              <td class="px-6 py-4 text-right whitespace-nowrap">
                <button type="button" onclick="inspectActivity({{ $activity->id }})" class="inline-flex items-center gap-1 rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition">
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  Inspecter
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-medium">
                Aucune consultation de dossier du personnel enregistrée selon les critères spécifiés.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
      <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-3">
        {{ $activities->links() }}
      </div>
    @endif
  </div>

</div>

<!-- Modal d'Inspection des Événements -->
<div id="inspector-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-xs transition-opacity" aria-hidden="true">
  <div class="flex min-h-screen items-center justify-center p-4">
    <div class="relative w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl transition-all">
      <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div class="flex items-center gap-2">
          <span id="modal-badge" class="rounded-md bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-800">Inspection</span>
          <h3 class="text-lg font-black text-gray-900" id="modal-title">Détails de l'événement d'accès</h3>
        </div>
        <button type="button" onclick="closeInspectorModal()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <div class="mt-4 space-y-4 text-xs" id="modal-body">
        <div class="flex items-center justify-center py-8">
          <svg class="h-8 w-8 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  function toggleExportDropdown() {
    const menu = document.getElementById('export-menu');
    menu.classList.toggle('hidden');
  }

  document.addEventListener('click', function(event) {
    const menu = document.getElementById('export-menu');
    const trigger = document.getElementById('export-trigger');
    if (menu && trigger && !trigger.contains(event.target) && !menu.contains(event.target)) {
      menu.classList.add('hidden');
    }
  });

  // Chart 1: Tendance sur 14 Jours
  const dailyTrendCtx = document.getElementById('dailyTrendChart').getContext('2d');
  new Chart(dailyTrendCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($dailyTrend['labels']) !!},
      datasets: [{
        label: 'Consultations de dossiers',
        data: {!! json_encode($dailyTrend['data']) !!},
        borderColor: '#4f46e5',
        backgroundColor: 'rgba(79, 70, 229, 0.1)',
        fill: true,
        tension: 0.35,
        borderWidth: 3,
        pointBackgroundColor: '#4f46e5',
        pointRadius: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } },
        x: { grid: { display: false } }
      }
    }
  });

  // Chart 2: Répartition par Action
  const actionTypesCtx = document.getElementById('actionTypesChart').getContext('2d');
  new Chart(actionTypesCtx, {
    type: 'doughnut',
    data: {
      labels: {!! json_encode(array_keys($actionCounts)) !!},
      datasets: [{
        data: {!! json_encode(array_values($actionCounts)) !!},
        backgroundColor: ['#4f46e5', '#0d9488'],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
    }
  });

  // Chart 3: Top Départements
  const topDeptsCtx = document.getElementById('topDeptsChart').getContext('2d');
  new Chart(topDeptsCtx, {
    type: 'bar',
    data: {
      labels: {!! json_encode(array_keys($topDepts)) !!},
      datasets: [{
        label: 'Accès enregistrés',
        data: {!! json_encode(array_values($topDepts)) !!},
        backgroundColor: '#6366f1',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y',
      plugins: { legend: { display: false } },
      scales: {
        x: { beginAtZero: true, ticks: { precision: 0 } },
        y: { grid: { display: false } }
      }
    }
  });

  // AJAX Inspection Modal
  function inspectActivity(id) {
    const modal = document.getElementById('inspector-modal');
    const modalBody = document.getElementById('modal-body');
    modal.classList.remove('hidden');

    fetch(`/activity-logs/${id}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      document.getElementById('modal-title').innerText = data.description || 'Détails de l\'événement';
      
      let propsHtml = '';
      if (data.properties) {
        propsHtml = `<pre class="styled-scroll max-h-48 overflow-y-auto rounded-xl bg-gray-900 p-3 font-mono text-[11px] text-indigo-300">${JSON.stringify(data.properties, null, 2)}</pre>`;
      } else {
        propsHtml = '<p class="text-gray-400 font-medium">Aucune métadonnée enregistrée.</p>';
      }

      modalBody.innerHTML = `
        <div class="grid grid-cols-2 gap-4 rounded-xl bg-gray-50 p-4">
          <div>
            <p class="font-bold text-gray-400 uppercase text-[10px]">Identifiant Log</p>
            <p class="font-mono font-bold text-gray-900">#${data.id}</p>
          </div>
          <div>
            <p class="font-bold text-gray-400 uppercase text-[10px]">Horodatage</p>
            <p class="font-bold text-gray-900">${data.created_at} (${data.created_at_human})</p>
          </div>
          <div>
            <p class="font-bold text-gray-400 uppercase text-[10px]">Consultateur (Auteur)</p>
            <p class="font-bold text-gray-900">${data.causer ? data.causer.name : 'Système'}</p>
            <p class="text-[10px] text-gray-500">${data.causer ? (data.causer.email + ' · ' + data.causer.matricule) : '—'}</p>
          </div>
          <div>
            <p class="font-bold text-gray-400 uppercase text-[10px]">Événement Spécifique</p>
            <p class="font-bold text-indigo-700">${data.event}</p>
          </div>
        </div>
        <div>
          <p class="mb-1 font-bold text-gray-700 uppercase text-[10px]">Métadonnées & Propriétés d'Accès</p>
          ${propsHtml}
        </div>
      `;
    })
    .catch(err => {
      modalBody.innerHTML = '<p class="text-center text-red-600 font-bold">Erreur lors du chargement des détails de l\'événement.</p>';
    });
  }

  function closeInspectorModal() {
    document.getElementById('inspector-modal').classList.add('hidden');
  }
</script>
@endpush
