@extends('admin.layout.app')

@section('title', 'Tableau de bord des Consultations d\'Archives — ' . setting('app_name', 'ArchiDoc'))
@section('meta_description', 'Analyse statistique et journal d\'audit complet des consultations de documents d\'archives.')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page & Navigation -->
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2.5">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-600 to-sky-800 text-white shadow-md shadow-sky-800/20">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
        </span>
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Consultations d'Archives — Analytics & Traçabilité</h1>
          <p class="text-xs text-gray-500 font-medium">Tableau de bord statistique et suivi individuel des accès aux pièces d'archives de la structure.</p>
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

        <div id="export-menu" class="absolute right-0 z-50 mt-2 hidden w-52 origin-top-right rounded-xl border border-gray-100 bg-white p-2 shadow-xl ring-1 ring-black/5">
          <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-gray-400">Format d'exportation</div>
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['event_type' => 'archive.consultation', 'format' => 'csv'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">
            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-extrabold text-emerald-800">CSV</span>
            Export Tableur (Excel)
          </a>
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['event_type' => 'archive.consultation', 'format' => 'json'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-800">
            <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-extrabold text-blue-800">JSON</span>
            Payload API (JSON)
          </a>
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['event_type' => 'archive.consultation', 'format' => 'txt'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-100 hover:text-gray-900">
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
      <a href="{{ route('activity-logs.auth') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-purple-200 bg-purple-50/80 px-3.5 py-2 text-xs font-bold text-purple-800 transition hover:bg-purple-100">
        <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        Sécurité & Accès
      </a>
      <a href="{{ route('activity-logs.system') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50/80 px-3.5 py-2 text-xs font-bold text-amber-800 transition hover:bg-amber-100">
        <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        Diagnostic Erreurs
      </a>
    </div>
  </div>

  <!-- Cartes KPI de Synthèse Analyste -->
  <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Consultations -->
    <div class="rounded-2xl border border-sky-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Total Consultations</p>
        <span class="rounded-xl bg-sky-50 p-2.5 text-sky-700 shadow-2xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-black text-gray-900">{{ number_format($totalConsultations) }}</p>
      <p class="mt-1 text-xs text-sky-600 font-semibold">Lectures de fiches enregistrées</p>
    </div>

    <!-- Aujourd'hui -->
    <div class="rounded-2xl border border-emerald-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Aujourd'hui (24h)</p>
        <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-700 shadow-2xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-black text-gray-900">{{ number_format($todayConsultations) }}</p>
      <p class="mt-1 text-xs text-emerald-600 font-semibold">Accès dans les dernières 24 heures</p>
    </div>

    <!-- Archives Distinctes -->
    <div class="rounded-2xl border border-indigo-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">Archives Uniques</p>
        <span class="rounded-xl bg-indigo-50 p-2.5 text-indigo-700 shadow-2xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-black text-gray-900">{{ number_format($uniqueArchivesCount) }}</p>
      <p class="mt-1 text-xs text-indigo-600 font-semibold">Documents uniques consultés</p>
    </div>

    <!-- Agent n°1 Consultateur -->
    <div class="rounded-2xl border border-brand-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-brand-700">Agent le plus actif</p>
        <span class="rounded-xl bg-brand-50 p-2.5 text-brand-700 shadow-2xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-base font-black text-gray-900 truncate" title="{{ $topUser ? $topUser->name : 'Aucun' }}">
        {{ $topUser ? $topUser->name : 'Aucun' }}
      </p>
      <p class="mt-1 text-xs text-brand-700 font-semibold">
        @if($topUser)
          {{ number_format($topUserCount) }} consultation(s)
        @else
          Pas d'activité recensée
        @endif
      </p>
    </div>
  </div>

  <!-- SECTION GRAPHIQUES STATISTIQUES ET ANALYSES VISUELLES -->
  <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-12">
    
    <!-- Graphique 1 : Évolution Chronologique des Consultations (Line/Bar Chart) -->
    <div class="lg:col-span-7 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-xs">
      <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
        <div>
          <h2 class="text-sm font-extrabold text-gray-900 flex items-center gap-2">
            <svg class="h-4 w-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            Évolution des Consultations (14 derniers jours)
          </h2>
          <p class="text-xs text-gray-500 font-medium">Volume journalier des accès aux archives</p>
        </div>
        <span class="rounded-lg bg-sky-50 px-2.5 py-1 text-[11px] font-bold text-sky-800 border border-sky-200">En direct</span>
      </div>

      <div class="relative h-64 w-full">
        <canvas id="dailyTrendChart"></canvas>
      </div>
    </div>

    <!-- Graphique 2 : Répartition par Direction / Département (Horizontal Bar Chart) -->
    <div class="lg:col-span-5 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-xs">
      <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
        <div>
          <h2 class="text-sm font-extrabold text-gray-900 flex items-center gap-2">
            <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            Consultations par Direction / Département
          </h2>
          <p class="text-xs text-gray-500 font-medium">Répartition des accès par entité de la structure</p>
        </div>
        <span class="rounded-lg bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-800 border border-indigo-200">Top 6</span>
      </div>

      <div class="relative h-64 w-full">
        <canvas id="deptDistributionChart"></canvas>
      </div>
    </div>

  </div>

  <!-- BARRE DE FILTRES MULTI-CRITÈRES -->
  <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-xs">
    <div class="border-b border-gray-100 bg-gray-50/60 px-6 py-3.5 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <svg class="h-4 w-4 text-sky-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Filtres de Recherche & d'Analyse des Consultations</span>
      </div>
      @if($search || $departementFilter !== 'all' || $dateRange !== 'all' || $causerId !== 'all')
        <span class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-bold text-sky-800">
          <span class="h-1.5 w-1.5 rounded-full bg-sky-600"></span>
          Filtres actifs
        </span>
      @endif
    </div>

    <form method="GET" action="{{ route('activity-logs.archives-consultations') }}" class="p-4 sm:p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        
        <!-- Recherche textuelle -->
        <div>
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Recherche textuelle</label>
          <div class="relative">
            <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Archive, agent, cote..."
              class="form-input-styled block w-full pl-9 text-xs">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <svg class="h-4 w-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
          </div>
        </div>

        <!-- Département / Direction -->
        <div>
          <label for="departement" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Direction / Département</label>
          <select name="departement" id="departement" class="form-select-styled block w-full text-xs">
            <option value="all" {{ $departementFilter === 'all' ? 'selected' : '' }}>Toutes les directions</option>
            @foreach($departmentsList as $dept)
              <option value="{{ $dept }}" {{ $departementFilter === $dept ? 'selected' : '' }}>{{ $dept }}</option>
            @endforeach
          </select>
        </div>

        <!-- Plage de Dates -->
        <div>
          <label for="date_range" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Période</label>
          <select name="date_range" id="date_range" class="form-select-styled block w-full text-xs">
            <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>Toutes les dates</option>
            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
            <option value="7days" {{ $dateRange === '7days' ? 'selected' : '' }}>7 derniers jours</option>
            <option value="30days" {{ $dateRange === '30days' ? 'selected' : '' }}>30 derniers jours</option>
          </select>
        </div>

        <!-- Agent Consultateur -->
        <div>
          <label for="causer_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Agent / Consultateur</label>
          <select name="causer_id" id="causer_id" class="form-select-styled block w-full text-xs">
            <option value="all" {{ $causerId === 'all' ? 'selected' : '' }}>Tous les agents</option>
            @foreach($usersList as $user)
              <option value="{{ $user->id }}" {{ (string)$causerId === (string)$user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->matricule }})
              </option>
            @endforeach
          </select>
        </div>

      </div>

      <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-4">
        <div class="flex items-center gap-2">
          <a href="{{ route('activity-logs.archives-consultations') }}" class="rounded-xl px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100">Réinitialiser</a>
          <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-sky-700 px-5 py-2 text-xs font-bold text-white shadow-xs hover:bg-sky-800 focus:ring-2 focus:ring-sky-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Appliquer les filtres
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- TABLEAU REGLEMENTAIRE DES CONSULTATIONS PAR ARCHIVE -->
  <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-xs">
    <div class="border-b border-gray-100 bg-gray-50/60 px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="h-2.5 w-2.5 rounded-full bg-sky-500 animate-pulse"></span>
        <h2 class="text-base font-extrabold text-gray-900">Journal Rôle & Registre des Consultations</h2>
      </div>
      <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-extrabold text-sky-800 border border-sky-200">
        {{ $activities->total() }} résultat(s)
      </span>
    </div>

    @if($activities->isEmpty())
      <div class="py-12 text-center">
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
        <p class="text-base font-bold text-gray-800">Aucune consultation d'archive enregistrée</p>
        <p class="mt-1 text-xs text-gray-500">Ajustez vos filtres ou réinitialisez la recherche.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="border-b border-gray-100 bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold">
            <tr>
              <th scope="col" class="px-5 py-3">Horodatage</th>
              <th scope="col" class="px-5 py-3">Archive Consultée</th>
              <th scope="col" class="px-5 py-3">Direction / Dept.</th>
              <th scope="col" class="px-5 py-3">Agent Consultateur</th>
              <th scope="col" class="px-5 py-3">Adresse IP</th>
              <th scope="col" class="px-5 py-3 text-right">Détails</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-gray-700">
            @foreach($activities as $act)
              <tr class="hover:bg-sky-50/30 transition-colors cursor-pointer" onclick="inspectActivity({{ $act->id }})">
                <td class="px-5 py-3.5 whitespace-nowrap font-medium text-gray-900">
                  <div>{{ $act->created_at->format('d/m/Y H:i:s') }}</div>
                  <div class="text-[10px] text-gray-400 font-normal">{{ $act->created_at->diffForHumans() }}</div>
                </td>
                <td class="px-5 py-3.5 max-w-sm">
                  <div class="flex items-center gap-2">
                    <span class="rounded bg-sky-100 px-1.5 py-0.5 font-mono text-[10px] font-bold text-sky-800 shrink-0">
                      {{ $act->properties['typearchive'] ?? 'ARCHIVE' }}
                    </span>
                    @if($act->subject_id)
                      <a href="{{ route('archives.show', $act->subject_id) }}" onclick="event.stopPropagation()" class="font-bold text-brand-700 hover:underline truncate" title="{{ $act->properties['description'] ?? $act->description }}">
                        {{ $act->properties['description'] ?? $act->description }}
                      </a>
                    @else
                      <span class="font-bold text-gray-800 truncate" title="{{ $act->description }}">
                        {{ $act->description }}
                      </span>
                    @endif
                  </div>
                </td>
                <td class="px-5 py-3.5 whitespace-nowrap">
                  <span class="inline-flex rounded-lg bg-gray-100 px-2.5 py-0.5 text-[11px] font-bold text-gray-700">
                    {{ $act->properties['departement'] ?? 'N/A' }}
                  </span>
                </td>
                <td class="px-5 py-3.5 whitespace-nowrap font-bold text-gray-900">
                  {{ $act->causer ? $act->causer->name : 'Système Automatique' }}
                  @if($act->causer && $act->causer->matricule)
                    <span class="font-mono text-[10px] font-normal text-gray-400 block">({{ $act->causer->matricule }})</span>
                  @endif
                </td>
                <td class="px-5 py-3.5 whitespace-nowrap font-mono text-[11px] text-gray-500">
                  {{ $act->properties['ip'] ?? '-' }}
                </td>
                <td class="px-5 py-3.5 whitespace-nowrap text-right" onclick="event.stopPropagation()">
                  <button type="button" onclick="inspectActivity({{ $act->id }})" class="inline-flex items-center gap-1 rounded-lg border border-sky-200 bg-sky-50 px-2.5 py-1 text-xs font-bold text-sky-800 hover:bg-sky-100 transition shadow-2xs">
                    <svg class="h-3.5 w-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Inspecter
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
        {{ $activities->links() }}
      </div>
    @endif
  </div>

</div>

<!-- Modal Inspecteur d'Événement -->
<div id="inspector-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-xs transition-opacity" aria-modal="true" role="dialog">
  <div class="flex min-h-screen items-center justify-center p-4">
    <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl transition-all">
      
      <div class="flex items-center justify-between border-b border-gray-100 bg-brand-900 px-6 py-4 text-white">
        <div class="flex items-center gap-2.5">
          <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-700 text-brand-200">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
          </span>
          <div>
            <h3 class="text-base font-bold text-white" id="modal-title">Inspection de la Consultation</h3>
            <p class="text-xs text-brand-200" id="modal-subtitle">Données système et traçabilité de l'accès</p>
          </div>
        </div>
        <button type="button" onclick="closeInspectorModal()" class="rounded-lg p-1.5 text-brand-200 hover:bg-brand-800 hover:text-white focus:outline-none">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <div class="p-6 space-y-6">
        <div class="grid grid-cols-2 gap-4 rounded-xl bg-gray-50 p-4 text-xs sm:grid-cols-4">
          <div>
            <span class="block font-bold text-gray-500 uppercase">Consultateur</span>
            <span class="font-bold text-gray-900" id="modal-causer">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Horodatage</span>
            <span class="font-bold text-gray-900" id="modal-date">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Événement</span>
            <span class="font-bold text-sky-800" id="modal-logname">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Archive Cible</span>
            <span class="font-bold text-gray-900" id="modal-subject">—</span>
          </div>
        </div>

        <div class="space-y-2">
          <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700">Payload Complet & Métadonnées Propriétés (JSON)</h4>
          <pre id="modal-properties-json" class="styled-scroll max-h-72 overflow-x-auto rounded-xl bg-gray-900 p-4 font-mono text-xs text-sky-400 shadow-inner">{}</pre>
        </div>
      </div>

      <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-6 py-3">
        <button type="button" onclick="closeInspectorModal()" class="rounded-xl bg-gray-200 px-5 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300">
          Fermer
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Integration de Chart.js pour l'analytique visuelle -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleExportDropdown() {
  const menu = document.getElementById('export-menu');
  menu.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
  const trigger = document.getElementById('export-trigger');
  const menu = document.getElementById('export-menu');
  if (trigger && menu && !trigger.contains(e.target) && !menu.contains(e.target)) {
    menu.classList.add('hidden');
  }
});

function inspectActivity(activityId) {
  fetch(`/activity-logs/${activityId}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById('modal-title').textContent = data.description || 'Inspection de la Consultation';
      document.getElementById('modal-subtitle').textContent = `ID Événement #${data.id} • ${data.event || 'archive.consultation'}`;
      document.getElementById('modal-causer').textContent = data.causer ? `${data.causer.name} (${data.causer.matricule})` : 'Système';
      document.getElementById('modal-date').textContent = `${data.created_at} (${data.created_at_human})`;
      document.getElementById('modal-logname').textContent = data.event || 'archive.consultation';
      document.getElementById('modal-subject').textContent = data.subject ? `${data.subject.type} #${data.subject.id}` : 'Archive';

      document.getElementById('modal-properties-json').textContent = JSON.stringify(data.properties || {}, null, 2);

      const modal = document.getElementById('inspector-modal');
      modal.classList.remove('hidden');
    })
    .catch(error => {
      alert('Erreur lors du chargement des détails de l\'événement.');
      console.error(error);
    });
}

function closeInspectorModal() {
  document.getElementById('inspector-modal').classList.add('hidden');
}

// Initialisation des Graphiques Statistiques Chart.js
document.addEventListener('DOMContentLoaded', function () {
  const dailyData = @json($dailyTrend);
  const topDepts = @json($topDepts);

  // Graphique 1 : Évolution Chronologique (Line Chart)
  const ctxDaily = document.getElementById('dailyTrendChart');
  if (ctxDaily) {
    new Chart(ctxDaily, {
      type: 'line',
      data: {
        labels: dailyData.labels,
        datasets: [{
          label: 'Consultations',
          data: dailyData.data,
          borderColor: '#0284c7',
          backgroundColor: 'rgba(2, 132, 199, 0.08)',
          borderWidth: 2.5,
          fill: true,
          tension: 0.35,
          pointBackgroundColor: '#0284c7',
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { size: 10 } }
          },
          y: {
            beginAtZero: true,
            ticks: { precision: 0, font: { size: 10 } }
          }
        }
      }
    });
  }

  // Graphique 2 : Répartition par Département (Horizontal Bar Chart)
  const ctxDept = document.getElementById('deptDistributionChart');
  if (ctxDept) {
    const deptLabels = Object.keys(topDepts);
    const deptValues = Object.values(topDepts);

    new Chart(ctxDept, {
      type: 'bar',
      data: {
        labels: deptLabels,
        datasets: [{
          label: 'Consultations',
          data: deptValues,
          backgroundColor: '#4f46e5',
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          x: {
            beginAtZero: true,
            ticks: { precision: 0, font: { size: 10 } }
          },
          y: {
            grid: { display: false },
            ticks: { font: { size: 10 } }
          }
        }
      }
    });
  }
});
</script>
@endsection
