@extends('admin.layout.app')

@section('title', 'Accueil — ArchiDoc DGB')
@section('meta_description', 'Tableau de bord et gestion des archives — Direction Générale du Budget')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Bannière de bienvenue -->
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-brand-900 via-brand-800 to-brand-700 p-6 sm:p-8 text-white shadow-lg mb-8">
    <div class="relative z-10 max-w-2xl">
      <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-brand-100 backdrop-blur-sm mb-3">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Logiciel d'archivage DGB v1.0
      </span>
      <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Bienvenue sur ArchiDoc</h1>
      <p class="mt-2 text-sm sm:text-base text-brand-100/90 leading-relaxed">
        Plateforme centralisée pour la gestion, la numérisation et la conservation des archives de la Direction Générale du Budget.
      </p>

      <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('archives.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-brand-800 shadow-md hover:bg-brand-50 transition-all focus:outline-none focus:ring-2 focus:ring-white">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Créer une archive
        </a>
        <a href="#" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm hover:bg-white/20 transition-all focus:outline-none focus:ring-2 focus:ring-white">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          Rechercher un document
        </a>
      </div>
    </div>

    <!-- Motif de fond en SVG -->
    <div class="absolute right-0 top-0 -mr-16 -mt-16 h-64 w-64 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
    <div class="absolute right-12 bottom-0 opacity-15 pointer-events-none hidden md:block">
      <svg class="h-48 w-48 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M3 7.2 5 4h14l2 3.2"/><rect x="3" y="7.2" width="18" height="12.3" rx="1.5"/><path d="M8 11.5h8"/>
      </svg>
    </div>
  </div>

  <!-- Grille des statistiques rapides -->
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Archives enregistrées</span>
        <span class="rounded-lg bg-brand-50 p-2 text-brand-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        </span>
      </div>
      <p class="mt-3 text-2xl font-bold text-gray-900">1,248</p>
      <p class="mt-1 text-xs text-emerald-600 font-medium">+12 cette semaine</p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Types d'archives</span>
        <span class="rounded-lg bg-amber-50 p-2 text-amber-600">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </span>
      </div>
      <p class="mt-3 text-2xl font-bold text-gray-900">32</p>
      <p class="mt-1 text-xs text-gray-500">Types répertoriés</p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Groupes d'accès</span>
        <span class="rounded-lg bg-sky-50 p-2 text-sky-600">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </span>
      </div>
      <p class="mt-3 text-2xl font-bold text-gray-900">13</p>
      <p class="mt-1 text-xs text-gray-500">Services DGB configurés</p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Emplacements physiques</span>
        <span class="rounded-lg bg-purple-50 p-2 text-purple-600">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </span>
      </div>
      <p class="mt-3 text-2xl font-bold text-gray-900">3</p>
      <p class="mt-1 text-xs text-gray-500">Centres d'archivage</p>
    </div>
  </div>

  <!-- Cartes d'accès rapide -->
  <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-8">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center gap-3 mb-4">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-700 text-white shadow-sm">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <div>
          <h2 class="text-base font-bold text-gray-900">Nouvelle Archive</h2>
          <p class="text-xs text-gray-500">Formulaire d'enregistrement et de classification</p>
        </div>
      </div>
      <p class="text-sm text-gray-600 mb-5 leading-relaxed">
        Créer une nouvelle fiche d'archive en renseignant le format, le type, la date de signature, l'emplacement physique et les droits d'accès.
      </p>
      <a href="{{ route('archives.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 transition-colors">
        Accéder au formulaire de création
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center gap-3 mb-4">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-800 text-white shadow-sm">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </span>
        <div>
          <h2 class="text-base font-bold text-gray-900">Modules de gestion</h2>
          <p class="text-xs text-gray-500">Types, emplacements & groupes d'accès</p>
        </div>
      </div>
      <p class="text-sm text-gray-600 mb-5 leading-relaxed">
        Configurez la nomenclature des types d'archives, gérez les magasins/rayons/boîtes physiques ou les groupes d'accès administratifs.
      </p>
      <div class="flex gap-2">
        <button type="button" disabled class="inline-flex w-full items-center justify-center gap-1 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-400 cursor-not-allowed">
          Gérer les types
        </button>
        <button type="button" disabled class="inline-flex w-full items-center justify-center gap-1 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-400 cursor-not-allowed">
          Gérer les emplacements
        </button>
      </div>
    </div>
  </div>

</div>
@endsection


