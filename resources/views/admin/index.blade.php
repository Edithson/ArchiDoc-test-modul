@extends('admin.layout.app')

@section('title', 'Tableau de bord Général — ' . setting('app_name', 'ArchiDoc') . ' ' . setting('structure_acronym', 'DGB'))
@section('meta_description', 'Tableau de bord exécutif et suivi en temps réel des archives et dossiers du personnel — ' . setting('structure_name', 'Direction Générale du Budget'))

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Bannière Exécutive de Bienvenue -->
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-900 via-brand-800 to-brand-700 p-6 sm:p-8 text-white shadow-xl mb-8">
    <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
      <div class="max-w-3xl">
        <div class="flex items-center gap-3 mb-3">
          <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-brand-100 backdrop-blur-md ring-1 ring-white/20">
            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Boîte Noire Systèmes & Surveillance : En Direct
          </span>
          <span class="hidden sm:inline-block text-xs font-semibold text-brand-200/80">
            {{ now()->translatedFormat('l, d F Y') }}
          </span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
          Bienvenue sur {{ setting('app_name', 'ArchiDoc') }}, <span class="text-brand-200">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
        </h1>
        <p class="mt-2 text-sm sm:text-base text-brand-100/90 leading-relaxed font-medium">
          Plateforme centrale de gestion, de numérisation, d'archivage rigoureux et de suivi analytique de la {{ setting('structure_name', 'Direction Générale du Budget') }}.
        </p>

        <!-- Recherche Rapide Integrée -->
        <form method="GET" action="{{ route('archives.search') }}" class="mt-6 flex max-w-xl items-center gap-2">
          <div class="relative w-full">
            <input type="text" name="search" placeholder="Rechercher une archive, un arrêté, une note, un matricule agent..." 
              class="w-full rounded-xl border border-white/20 bg-white/15 py-3 pl-10 pr-4 text-xs font-medium text-white placeholder-brand-200/70 shadow-inner backdrop-blur-md focus:bg-white focus:text-gray-900 focus:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
            <svg class="absolute left-3 top-3.5 h-4 w-4 text-brand-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
          <button type="submit" class="shrink-0 rounded-xl bg-white px-4 py-3 text-xs font-extrabold text-brand-800 shadow-md hover:bg-brand-50 transition-all focus:outline-none">
            Rechercher
          </button>
        </form>
      </div>

      <!-- Actions Rapides Bannières -->
      <div class="flex flex-col gap-2.5 sm:flex-row lg:flex-col shrink-0">
        <a href="{{ route('archives.create') }}" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-white px-5 py-3 text-xs font-extrabold text-brand-800 shadow-md hover:bg-brand-50 hover:shadow-lg transition-all">
          <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Nouvelle Archive
        </a>
        <a href="{{ route('personnels.create') }}" class="inline-flex items-center justify-center gap-2.5 rounded-xl border border-white/30 bg-white/10 px-5 py-3 text-xs font-extrabold text-white backdrop-blur-md hover:bg-white/20 transition-all">
          <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
          Nouveau Dossier Agent
        </a>
      </div>
    </div>

    <!-- Graphique SVG Flou en Fond -->
    <div class="absolute right-0 top-0 -mr-20 -mt-20 h-72 w-72 rounded-full bg-brand-400/20 blur-3xl pointer-events-none"></div>
  </div>

  <!-- Cartes d'Indicateurs Clés Dynamiques (KPI Cards) -->
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    
    <!-- 1. Archives Numérisées -->
    <a href="{{ route('archives.search') }}" class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-xs transition-all hover:border-brand-300 hover:shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Archives Numérisées</span>
          <h3 class="mt-1 text-2xl font-black tracking-tight text-gray-900 group-hover:text-brand-700 transition-colors">
            {{ number_format($totalArchives, 0, ',', ' ') }}
          </h3>
          <p class="mt-1 text-[11px] font-medium text-emerald-600 flex items-center gap-1">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            Documents répertoriés
          </p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-700 transition-transform group-hover:scale-110">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        </div>
      </div>
    </a>

    <!-- 2. Dossiers Personnel -->
    <a href="{{ route('personnels.index') }}" class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-xs transition-all hover:border-indigo-300 hover:shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Dossiers du Personnel</span>
          <h3 class="mt-1 text-2xl font-black tracking-tight text-gray-900 group-hover:text-indigo-700 transition-colors">
            {{ number_format($totalPersonnel, 0, ',', ' ') }}
          </h3>
          <p class="mt-1 text-[11px] font-medium text-indigo-600">Agents DGB enregistrés</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-700 transition-transform group-hover:scale-110">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
      </div>
    </a>

    <!-- 3. Consultations Cumulées -->
    <a href="{{ route('activity-logs.archives-consultations') }}" class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-xs transition-all hover:border-sky-300 hover:shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Consultations Globale</span>
          <h3 class="mt-1 text-2xl font-black tracking-tight text-gray-900 group-hover:text-sky-700 transition-colors">
            {{ number_format($totalConsultations, 0, ',', ' ') }}
          </h3>
          <p class="mt-1 text-[11px] font-medium text-sky-600">Accès traacés par la Boîte Noire</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-700 transition-transform group-hover:scale-110">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
      </div>
    </a>

    <!-- 4. Comptes Utilisateurs -->
    <a href="{{ route('users.index') }}" class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-xs transition-all hover:border-purple-300 hover:shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Comptes Utilisateurs</span>
          <h3 class="mt-1 text-2xl font-black tracking-tight text-gray-900 group-hover:text-purple-700 transition-colors">
            {{ number_format($totalUsers, 0, ',', ' ') }}
          </h3>
          <p class="mt-1 text-[11px] font-medium text-purple-600">Agents connectés actifs</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-700 transition-transform group-hover:scale-110">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
      </div>
    </a>
  </div>

  <!-- Section Graphiques Statistiques Interactifs -->
  <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Graphique 1: Tendance Comparative sur 14 Jours -->
    <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
      <div class="mb-4 flex items-center justify-between">
        <div>
          <h3 class="text-sm font-extrabold tracking-tight text-gray-900">Activité Comparative des Consultations (14 Derniers Jours)</h3>
          <p class="text-xs text-gray-500 font-medium">Flux quotidien des accès aux Archives vs Dossiers du Personnel.</p>
        </div>
        <span class="rounded-full bg-brand-50 px-2.5 py-1 text-[11px] font-bold text-brand-700">Analytics Temps Réel</span>
      </div>
      <div class="h-64 w-full">
        <canvas id="globalTrendChart"></canvas>
      </div>
    </div>

    <!-- Graphique 2: Répartition Archives par Département -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
      <div class="mb-4 flex items-center justify-between">
        <div>
          <h3 class="text-sm font-extrabold tracking-tight text-gray-900">Conservation par Direction</h3>
          <p class="text-xs text-gray-500 font-medium">Répartition des archives numérisées par département.</p>
        </div>
        <span class="rounded-full bg-purple-50 px-2.5 py-1 text-[11px] font-bold text-purple-700">Services DGB</span>
      </div>
      <div class="h-64 w-full flex items-center justify-center">
        <canvas id="deptDistributionChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Grille 3 Colonnes: Flux en direct & Dernières Entrées -->
  <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
    
    <!-- Colonne 1: Dernières Archives Numérisées -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
      <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
        <div class="flex items-center gap-2">
          <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-50 text-brand-700 font-bold text-xs">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </span>
          <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-900">Dernières Archives</h3>
        </div>
        <a href="{{ route('archives.search') }}" class="text-[11px] font-bold text-brand-700 hover:underline">Tout voir</a>
      </div>

      <div class="space-y-3">
        @forelse($recentArchives as $arch)
          <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 p-3 hover:bg-gray-50 transition-colors">
            <div class="min-w-0 flex-1">
              <p class="font-extrabold text-xs text-gray-900 truncate">{{ $arch->description }}</p>
              <p class="text-[10px] text-gray-500 font-medium">
                {{ $arch->archiveType?->name ?? 'Document' }} · <span class="font-bold text-brand-700">{{ $arch->department?->name ?? 'DGB' }}</span>
              </p>
            </div>
            <a href="{{ route('archives.show', $arch) }}" class="shrink-0 rounded-lg bg-gray-100 px-2.5 py-1 text-[11px] font-bold text-gray-700 hover:bg-brand-100 hover:text-brand-800 transition">
              Voir
            </a>
          </div>
        @empty
          <p class="py-4 text-center text-xs text-gray-400">Aucune archive enregistrée.</p>
        @endforelse
      </div>
    </div>

    <!-- Colonne 2: Récents Dossiers du Personnel -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
      <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
        <div class="flex items-center gap-2">
          <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </span>
          <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-900">Récents Dossiers Agents</h3>
        </div>
        <a href="{{ route('personnels.index') }}" class="text-[11px] font-bold text-indigo-700 hover:underline">Tout voir</a>
      </div>

      <div class="space-y-3">
        @forelse($recentPersonnels as $pers)
          <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 p-3 hover:bg-gray-50 transition-colors">
            <div class="min-w-0 flex-1">
              <p class="font-extrabold text-xs text-gray-900 truncate">{{ $pers->name }}</p>
              <p class="text-[10px] text-gray-500 font-medium">
                Matricule: <span class="font-mono font-bold text-indigo-700">{{ $pers->matricule }}</span>
              </p>
            </div>
            @if($pers->is_complete)
              <span class="rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-800 shrink-0">Complet</span>
            @else
              <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800 shrink-0">Incomplet</span>
            @endif
            <a href="{{ route('personnels.show', $pers) }}" class="shrink-0 rounded-lg bg-gray-100 px-2.5 py-1 text-[11px] font-bold text-gray-700 hover:bg-indigo-100 hover:text-indigo-800 transition">
              Fiche
            </a>
          </div>
        @empty
          <p class="py-4 text-center text-xs text-gray-400">Aucun agent répertorié.</p>
        @endforelse
      </div>
    </div>

    <!-- Colonne 3: Flux en Direct Boîte Noire -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
      <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
        <div class="flex items-center gap-2">
          <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-50 text-purple-700 font-bold text-xs">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </span>
          <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-900">Boîte Noire (Direct)</h3>
        </div>
        <a href="{{ route('activity-logs.index') }}" class="text-[11px] font-bold text-purple-700 hover:underline">Journal global</a>
      </div>

      <div class="space-y-2.5 text-xs">
        @forelse($recentActivities as $act)
          <div class="flex items-start gap-2.5 rounded-xl border border-gray-100 p-2.5 hover:bg-purple-50/40 transition-colors">
            <span class="mt-0.5 h-2 w-2 shrink-0 rounded-full bg-brand-600"></span>
            <div class="min-w-0 flex-1">
              <p class="font-bold text-gray-900 text-[11px] truncate">{{ $act->description }}</p>
              <p class="text-[10px] text-gray-500 font-medium">
                {{ $act->causer->name ?? 'Système' }} · {{ $act->created_at ? $act->created_at->diffForHumans() : '' }}
              </p>
            </div>
          </div>
        @empty
          <p class="py-4 text-center text-xs text-gray-400">Aucune activité enregistrée.</p>
        @endforelse
      </div>
    </div>

  </div>

  <!-- Modules de Configuration & Référentiels Actifs -->
  <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
    <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
      <div>
        <h3 class="text-sm font-extrabold text-gray-900">Modules de Nomenclature & Administration</h3>
        <p class="text-xs text-gray-500 font-medium">Accès rapide aux tables de référence et à la configuration globale.</p>
      </div>
      <span class="rounded-md bg-gray-100 px-2.5 py-1 text-[10px] font-bold text-gray-700">Infrastructure DGB</span>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
      <!-- Module 1: Types d'archives -->
      <a href="{{ route('archive-types.index') }}" class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50/50 p-4 hover:border-brand-300 hover:bg-brand-50/30 transition-all">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-800 font-bold">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        <div>
          <h4 class="text-xs font-extrabold text-gray-900">Types d'Archives</h4>
          <p class="text-[11px] font-bold text-amber-700">{{ $archiveTypesCount }} nomenclatures</p>
        </div>
      </a>

      <!-- Module 2: Emplacements -->
      <a href="{{ route('archive-locations.index') }}" class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50/50 p-4 hover:border-purple-300 hover:bg-purple-50/30 transition-all">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-800 font-bold">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
          <h4 class="text-xs font-extrabold text-gray-900">Emplacements</h4>
          <p class="text-[11px] font-bold text-purple-700">{{ $locationsCount }} mag. / rayons</p>
        </div>
      </a>

      <!-- Module 3: Groupes d'Accès -->
      <a href="{{ route('departments.index') }}" class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50/50 p-4 hover:border-sky-300 hover:bg-sky-50/30 transition-all">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-800 font-bold">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
          <h4 class="text-xs font-extrabold text-gray-900">Groupes d'Accès</h4>
          <p class="text-[11px] font-bold text-sky-700">{{ $departmentsCount }} directions DGB</p>
        </div>
      </a>

      <!-- Module 4: Paramètres système -->
      <a href="{{ route('settings.index') }}" class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50/50 p-4 hover:border-emerald-300 hover:bg-emerald-50/30 transition-all">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800 font-bold">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
          <h4 class="text-xs font-extrabold text-gray-900">Paramètres Système</h4>
          <p class="text-[11px] font-bold text-emerald-700">Sécurité & Poids</p>
        </div>
      </a>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Chart 1: Tendance Comparative Globale
  const globalTrendCtx = document.getElementById('globalTrendChart').getContext('2d');
  new Chart(globalTrendCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($dailyTrend['labels']) !!},
      datasets: [
        {
          label: 'Consultations Archives',
          data: {!! json_encode($dailyTrend['archives']) !!},
          borderColor: '#0284c7',
          backgroundColor: 'rgba(2, 132, 199, 0.08)',
          fill: true,
          tension: 0.35,
          borderWidth: 3,
          pointBackgroundColor: '#0284c7',
          pointRadius: 4
        },
        {
          label: 'Consultations Personnel',
          data: {!! json_encode($dailyTrend['personnel']) !!},
          borderColor: '#4f46e5',
          backgroundColor: 'rgba(79, 70, 229, 0.08)',
          fill: true,
          tension: 0.35,
          borderWidth: 3,
          pointBackgroundColor: '#4f46e5',
          pointRadius: 4
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
          labels: { boxWidth: 12, font: { size: 11, weight: 'bold' } }
        }
      },
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } },
        x: { grid: { display: false } }
      }
    }
  });

  // Chart 2: Répartition par Département
  const deptDistributionCtx = document.getElementById('deptDistributionChart').getContext('2d');
  new Chart(deptDistributionCtx, {
    type: 'doughnut',
    data: {
      labels: {!! json_encode(array_keys($archivesByDept)) !!},
      datasets: [{
        data: {!! json_encode(array_values($archivesByDept)) !!},
        backgroundColor: ['#0284c7', '#4f46e5', '#8b5cf6', '#d97706', '#059669'],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 10, font: { size: 10 } }
        }
      }
    }
  });
</script>
@endpush
