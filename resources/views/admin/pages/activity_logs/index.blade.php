@extends('admin.layout.app')

@section('title', 'Journal d\'activités & Arbre des événements — ' . setting('app_name', 'ArchiDoc'))
@section('meta_description', 'Boîte noire et traçabilité avancée de l\'ensemble des opérations, modifications et événements de la plateforme.')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2.5">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-700/20">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </span>
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Boîte Noire — Arbre des Événements</h1>
          <p class="text-xs text-gray-500 font-medium">Supervision en temps réel et historique complet des variations, opérations et accès système.</p>
        </div>
      </div>
    </div>

    <!-- Actions Rapides & Bouton d'Exportation -->
    <div class="flex flex-wrap items-center gap-2.5">
      
      <!-- Bouton d'exportation avec Menu déroulant de Formats -->
      <div class="relative inline-block text-left">
        <button id="export-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="export-menu" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-700/20 hover:from-emerald-700 hover:to-emerald-800 transition-all focus:outline-none">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
          Exporter les données
          <svg class="h-3.5 w-3.5 opacity-80 transition-transform" data-chevron fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>

        <div id="export-menu" class="absolute right-0 z-50 mt-2 hidden w-52 origin-top-right rounded-xl border border-gray-100 bg-white p-2 shadow-xl ring-1 ring-black/5">
          <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-gray-400">Choisir le Format</div>
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">
            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-extrabold text-emerald-800">CSV</span>
            Export Tableur (Excel)
          </a>
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['format' => 'json'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-800">
            <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-extrabold text-blue-800">JSON</span>
            Payload Structuré (API)
          </a>
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['format' => 'txt'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-100 hover:text-gray-900">
            <span class="rounded bg-gray-200 px-1.5 py-0.5 text-[10px] font-extrabold text-gray-800">TXT</span>
            Journal Texte Syslog
          </a>
        </div>
      </div>

      <!-- Navigation sous-sections -->
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

  <!-- Cartes KPI de Synthèse -->
  <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Événements -->
    <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-brand-300">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Événements</p>
        <span class="rounded-xl bg-brand-50 p-2.5 text-brand-700 shadow-xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($totalEventsCount) }}</p>
      <p class="mt-1 text-xs text-gray-500 font-medium">Enregistrés en base de données</p>
    </div>

    <!-- Événements Aujourd'hui -->
    <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-blue-300">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Aujourd'hui</p>
        <span class="rounded-xl bg-blue-50 p-2.5 text-blue-700 shadow-xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($todayEventsCount) }}</p>
      <p class="mt-1 text-xs text-blue-600 font-semibold">Activités des dernières 24h</p>
    </div>

    <!-- Événements de Sécurité -->
    <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-purple-300">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Sécurité & Accès</p>
        <span class="rounded-xl bg-purple-50 p-2.5 text-purple-700 shadow-xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($authEventsCount) }}</p>
      <p class="mt-1 text-xs text-purple-600 font-semibold">Connexions & Authentifications</p>
    </div>

    <!-- Erreurs Système -->
    <div class="rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-amber-300">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Erreurs Système</p>
        <span class="rounded-xl bg-amber-50 p-2.5 text-amber-700 shadow-xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($systemErrorsCount) }}</p>
      <p class="mt-1 text-xs text-amber-600 font-semibold">Exceptions interceptées</p>
    </div>
  </div>

  <!-- Barre de Filtres de Recherche -->
  <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm">
    <div class="border-b border-gray-100 bg-gray-50/60 px-6 py-3.5 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Filtres de Recherche & d'Analyse</span>
      </div>
      @if($search || $logName !== 'all' || $eventType !== 'all' || $dateRange !== 'all' || $causerId !== 'all')
        <span class="inline-flex items-center gap-1 rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-bold text-brand-800">
          <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
          Filtres actifs
        </span>
      @endif
    </div>

    <form method="GET" action="{{ route('activity-logs.index') }}" class="p-4 sm:p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        
        <!-- Recherche textuelle -->
        <div class="lg:col-span-2">
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Recherche globale</label>
          <div class="relative">
            <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Rechercher action, utilisateur, matricule, document..."
              class="form-input-styled block w-full pl-9">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <svg class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
          </div>
        </div>

        <!-- Catégorie de Log -->
        <div>
          <label for="log_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Catégorie</label>
          <select name="log_name" id="log_name" class="form-select-styled block w-full">
            <option value="all" {{ $logName === 'all' ? 'selected' : '' }}>Toutes les catégories</option>
            <option value="default" {{ $logName === 'default' ? 'selected' : '' }}>Modèles & Données (default)</option>
            <option value="auth" {{ $logName === 'auth' ? 'selected' : '' }}>Authentification & Sécurité (auth)</option>
            <option value="system" {{ $logName === 'system' ? 'selected' : '' }}>Système & Erreurs (system)</option>
          </select>
        </div>

        <!-- Plage de Dates -->
        <div>
          <label for="date_range" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Période</label>
          <select name="date_range" id="date_range" class="form-select-styled block w-full">
            <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>Toutes les dates</option>
            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
            <option value="7days" {{ $dateRange === '7days' ? 'selected' : '' }}>7 derniers jours</option>
            <option value="30days" {{ $dateRange === '30days' ? 'selected' : '' }}>30 derniers jours</option>
          </select>
        </div>

        <!-- Utilisateur Initiateur -->
        <div>
          <label for="causer_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Auteur / Agent</label>
          <select name="causer_id" id="causer_id" class="form-select-styled block w-full">
            <option value="all" {{ $causerId === 'all' ? 'selected' : '' }}>Tous les utilisateurs</option>
            @foreach($usersList as $user)
              <option value="{{ $user->id }}" {{ (string)$causerId === (string)$user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->matricule }})
              </option>
            @endforeach
          </select>
        </div>

      </div>

      <!-- Actions du filtre & Téléchargement Direct -->
      <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-4">
        
        <!-- Boutons d'exportation Formats Directs (Native HTML5 formaction) -->
        <div class="flex flex-wrap items-center gap-2">
          <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Télécharger :</span>
          <button type="submit" name="format" value="csv" formaction="{{ route('activity-logs.export') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-800 hover:bg-emerald-100 transition shadow-2xs">
            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Excel (CSV)
          </button>
          <button type="submit" name="format" value="json" formaction="{{ route('activity-logs.export') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-300 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-800 hover:bg-blue-100 transition shadow-2xs">
            <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            JSON API
          </button>
          <button type="submit" name="format" value="txt" formaction="{{ route('activity-logs.export') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-gray-100 px-3 py-1.5 text-xs font-bold text-gray-800 hover:bg-gray-200 transition shadow-2xs">
            <svg class="h-3.5 w-3.5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            TXT Log
          </button>
        </div>

        <div class="flex items-center gap-2">
          <a href="{{ route('activity-logs.index') }}" class="rounded-xl px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100">Réinitialiser</a>
          <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-brand-800 focus:ring-2 focus:ring-brand-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Appliquer les filtres
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Arbre Chronologique des Événements (Event Tree Timeline) -->
  <div class="rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4">
      <div class="flex items-center gap-2">
        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <h2 class="text-base font-extrabold text-gray-900">Arbre Chronologique des Événements</h2>
      </div>
      <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-extrabold text-brand-800 border border-brand-200/60">
        {{ $activities->total() }} événement(s) enregistré(s)
      </span>
    </div>

    @if($activities->isEmpty())
      <div class="py-12 text-center">
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-base font-bold text-gray-800">Aucun événement ne correspond aux critères</p>
        <p class="mt-1 text-xs text-gray-500">Modifiez vos filtres ou réinitialisez la recherche pour afficher l'historique complet.</p>
      </div>
    @else
      <div class="relative pl-6 sm:pl-8 before:absolute before:bottom-0 before:left-3 sm:before:left-4 before:top-2 before:w-0.5 before:bg-gray-200">
        <div class="space-y-6">
          @foreach($activities as $activity)
            @php
              $event = $activity->event ?? 'updated';
              $iconBg = 'bg-blue-100 text-blue-700 border-blue-200';
              $badgeBg = 'bg-blue-50 text-blue-700 border-blue-200';

              if (str_contains($event, 'created') || str_contains($event, 'stored')) {
                  $iconBg = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                  $badgeBg = 'bg-emerald-50 text-emerald-700 border-emerald-200';
              } elseif (str_contains($event, 'deleted') || str_contains($event, 'purged')) {
                  $iconBg = 'bg-rose-100 text-rose-700 border-rose-200';
                  $badgeBg = 'bg-rose-50 text-rose-700 border-rose-200';
              } elseif (str_contains($event, 'login') || str_contains($event, 'logout') || str_contains($event, 'auth')) {
                  $iconBg = 'bg-purple-100 text-purple-700 border-purple-200';
                  $badgeBg = 'bg-purple-50 text-purple-700 border-purple-200';
              } elseif (str_contains($event, 'error') || str_contains($event, 'failed')) {
                  $iconBg = 'bg-amber-100 text-amber-700 border-amber-200';
                  $badgeBg = 'bg-amber-50 text-amber-700 border-amber-200';
              } elseif (str_contains($event, 'consultation')) {
                  $iconBg = 'bg-sky-100 text-sky-700 border-sky-200';
                  $badgeBg = 'bg-sky-50 text-sky-700 border-sky-200';
              } elseif (str_contains($event, 'download')) {
                  $iconBg = 'bg-teal-100 text-teal-700 border-teal-200';
                  $badgeBg = 'bg-teal-50 text-teal-700 border-teal-200';
              }
            @endphp

            <div class="relative group">
              <!-- Point d'ancrage sur l'arbre verticaux -->
              <span class="absolute -left-6 sm:-left-8 top-1.5 flex h-6 w-6 items-center justify-center rounded-full border bg-white shadow-xs {{ $iconBg }}">
                @if(str_contains($event, 'created'))
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                @elseif(str_contains($event, 'deleted'))
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                @elseif(str_contains($event, 'login') || str_contains($event, 'auth'))
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                @elseif(str_contains($event, 'error'))
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @elseif(str_contains($event, 'consultation'))
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                @elseif(str_contains($event, 'download'))
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                @else
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                @endif
              </span>

              <!-- Carte du Noeud d'Événement -->
              <div class="rounded-xl border border-gray-200/90 bg-white p-4 shadow-2xs transition group-hover:border-brand-300 group-hover:shadow-md">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                  <div class="flex items-center gap-3">
                    <span class="inline-flex rounded-md border px-2.5 py-0.5 text-xs font-bold capitalize {{ $badgeBg }}">
                      {{ $activity->event ?? 'opération' }}
                    </span>
                    <p class="text-sm font-bold text-gray-900">{{ $activity->description }}</p>
                  </div>

                  <div class="flex items-center gap-3 text-xs text-gray-500">
                    <div class="flex items-center gap-1.5">
                      <svg class="h-3.5 w-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                      <span class="font-bold text-gray-800">
                        {{ $activity->causer ? $activity->causer->name : 'Système Automatique' }}
                      </span>
                    </div>
                    <span>•</span>
                    <time datetime="{{ $activity->created_at }}" title="{{ $activity->created_at->format('d/m/Y H:i:s') }}" class="font-medium text-gray-600">
                      {{ $activity->created_at->diffForHumans() }}
                    </time>
                  </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between border-t border-gray-100 pt-2.5 text-xs text-gray-500">
                  <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-400">Entité ciblée :</span>
                    <span class="rounded bg-gray-100 px-2 py-0.5 font-mono text-[11px] font-bold text-gray-700">
                      {{ $activity->subject_type ? class_basename($activity->subject_type) . ' #' . $activity->subject_id : 'Système' }}
                    </span>
                  </div>

                  <button type="button" onclick="inspectActivity({{ $activity->id }})" class="inline-flex items-center gap-1 text-xs font-extrabold text-brand-700 hover:text-brand-900 focus:outline-none">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Inspecter la variation (JSON / Diff)
                  </button>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-8 border-t border-gray-100 pt-4">
        {{ $activities->links() }}
      </div>
    @endif
  </div>

</div>

<!-- Modal Inspecteur d'Événement Expert (Diff & JSON) -->
<div id="inspector-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-xs transition-opacity" aria-modal="true" role="dialog">
  <div class="flex min-h-screen items-center justify-center p-4">
    <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl transition-all">
      
      <!-- En-tête du Modal -->
      <div class="flex items-center justify-between border-b border-gray-100 bg-brand-900 px-6 py-4 text-white">
        <div class="flex items-center gap-2.5">
          <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-700 text-brand-200">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
          </span>
          <div>
            <h3 class="text-base font-bold text-white" id="modal-title">Inspection de l'Événement</h3>
            <p class="text-xs text-brand-200" id="modal-subtitle">Variations détaillées et données système</p>
          </div>
        </div>
        <button type="button" onclick="closeInspectorModal()" class="rounded-lg p-1.5 text-brand-200 hover:bg-brand-800 hover:text-white focus:outline-none">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <!-- Corps du Modal -->
      <div class="p-6 space-y-6">
        
        <div class="grid grid-cols-2 gap-4 rounded-xl bg-gray-50 p-4 text-xs sm:grid-cols-4">
          <div>
            <span class="block font-bold text-gray-500 uppercase">Auteur</span>
            <span class="font-bold text-gray-900" id="modal-causer">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Horodatage</span>
            <span class="font-bold text-gray-900" id="modal-date">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Catégorie</span>
            <span class="font-bold text-gray-900" id="modal-logname">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Entité</span>
            <span class="font-bold text-gray-900" id="modal-subject">—</span>
          </div>
        </div>

        <!-- Section Diff de variation -->
        <div id="diff-section" class="hidden space-y-3">
          <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
            <svg class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Différence des Données (Anciennes vs Nouvelles Valeurs)
          </h4>
          <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-rose-200 bg-rose-50/50 p-4">
              <span class="block mb-2 text-xs font-bold uppercase text-rose-800">Ancienne Valeur (Avant)</span>
              <pre id="modal-old-json" class="styled-scroll max-h-60 overflow-x-auto rounded-lg bg-white p-3 font-mono text-xs text-rose-900 shadow-xs border border-rose-100">{}</pre>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-4">
              <span class="block mb-2 text-xs font-bold uppercase text-emerald-800">Nouvelle Valeur (Après)</span>
              <pre id="modal-new-json" class="styled-scroll max-h-60 overflow-x-auto rounded-lg bg-white p-3 font-mono text-xs text-emerald-900 shadow-xs border border-emerald-100">{}</pre>
            </div>
          </div>
        </div>

        <div class="space-y-2">
          <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700">Payload Complet & Métadonnées Propriétés (JSON)</h4>
          <pre id="modal-properties-json" class="styled-scroll max-h-72 overflow-x-auto rounded-xl bg-gray-900 p-4 font-mono text-xs text-emerald-400 shadow-inner">{}</pre>
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

<script>
function inspectActivity(activityId) {
  fetch(`/activity-logs/${activityId}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById('modal-title').textContent = data.description || 'Inspection de l\'Événement';
      document.getElementById('modal-subtitle').textContent = `ID Événement #${data.id} • ${data.event || 'action'}`;
      document.getElementById('modal-causer').textContent = data.causer ? `${data.causer.name} (${data.causer.matricule})` : 'Système';
      document.getElementById('modal-date').textContent = `${data.created_at} (${data.created_at_human})`;
      document.getElementById('modal-logname').textContent = data.log_name || 'default';
      document.getElementById('modal-subject').textContent = data.subject ? `${data.subject.type} #${data.subject.id}` : 'Système';

      const properties = data.properties || {};
      const diffSection = document.getElementById('diff-section');
      
      if (properties.old || properties.attributes) {
        diffSection.classList.remove('hidden');
        document.getElementById('modal-old-json').textContent = JSON.stringify(properties.old || {}, null, 2);
        document.getElementById('modal-new-json').textContent = JSON.stringify(properties.attributes || {}, null, 2);
      } else {
        diffSection.classList.add('hidden');
      }

      document.getElementById('modal-properties-json').textContent = JSON.stringify(properties, null, 2);

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
</script>
@endsection
