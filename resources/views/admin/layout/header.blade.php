<header class="sticky top-0 z-30 border-b border-gray-200 bg-white shadow-xs">
  <div class="mx-auto flex h-16 max-w-[1600px] items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">

    <div class="flex min-w-0 items-center gap-4">
      <!-- Bouton menu mobile -->
      <button id="mobile-menu-btn" type="button"
        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2 xl:hidden"
        aria-label="Ouvrir le menu de navigation" aria-expanded="false" aria-controls="mobile-drawer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>

      <!-- Logo -->
      <a href="{{ route('archives.index') }}" class="flex shrink-0 items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-700 text-white shadow-sm ring-2 ring-brand-700/20">
          <img src="{{asset('media/img/logo_archidoc_dgb.png')}}" alt="logo ARCHIDOC DGB" class="rounded-full">
        </span>
        <span class="hidden flex-col leading-none sm:flex">
          <span class="text-sm font-extrabold tracking-tight text-gray-900">ARCHIDOC</span>
          <span class="text-[10px] font-bold tracking-widest text-brand-700 uppercase">DGB CAMEROUN</span>
        </span>
      </a>

      <!-- Navigation desktop allégée & regroupée -->
      <nav aria-label="Navigation principale" class="hidden items-center gap-1.5 pl-4 text-sm xl:flex">

        <!-- 1. Accueil -->
        <a href="{{ route('archives.index') }}"
          class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 {{ request()->routeIs('archives.index') ? 'bg-brand-50 font-bold text-brand-700 shadow-xs ring-1 ring-brand-700/20' : 'font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
          <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span>Accueil</span>
        </a>

        <!-- 2. Menu Création -->
        @php
          $isCreationActive = request()->routeIs('archives.create');
        @endphp
        <div class="relative">
          <button id="creations-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="creations-menu"
            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 {{ $isCreationActive ? 'bg-brand-50 font-bold text-brand-700 shadow-xs ring-1 ring-brand-700/20' : 'font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Création</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 opacity-70 transition-transform" data-chevron aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div id="creations-menu" class="absolute left-0 z-40 mt-2 hidden w-72 origin-top-left rounded-xl border border-gray-100 bg-white p-2 shadow-xl">
            <div class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-gray-400">Modules de Saisie</div>
            <ul id="header-creations-list" class="space-y-0.5 text-sm">
              <li>
                <a href="{{ route('archives.create') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('archives.create') ? 'bg-brand-50 font-bold text-brand-700' : 'text-gray-700 hover:bg-gray-50' }}">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Nouvelle Archive</span>
                  </div>
                  <span class="rounded bg-brand-100 px-1.5 py-0.5 text-[10px] font-bold text-brand-800">Actif</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-gray-500 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 11h10M7 15h10"/></svg>
                    <span>Nouveau Type d'archive</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-gray-500 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <span>Nouvel Emplacement</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-gray-500 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Nouveau Groupe d'accès</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-gray-500 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Nouveau Dossier Personnel</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- 3. Menu Consultation -->
        @php
          $isConsultationActive = request()->routeIs('archives.search') || request()->routeIs('archives.show');
        @endphp
        <div class="relative">
          <button id="consultations-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="consultations-menu"
            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 {{ $isConsultationActive ? 'bg-brand-50 font-bold text-brand-700 shadow-xs ring-1 ring-brand-700/20' : 'font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span>Consultation</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 opacity-70 transition-transform" data-chevron aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div id="consultations-menu" class="absolute left-0 z-40 mt-2 hidden w-72 origin-top-left rounded-xl border border-gray-100 bg-white p-2 shadow-xl">
            <div class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-gray-400">Recherche & Historique</div>
            <ul class="space-y-0.5 text-sm">
              <li>
                <a href="{{ route('archives.search') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('archives.search') || request()->routeIs('archives.show') ? 'bg-brand-50 font-bold text-brand-700' : 'text-gray-700 hover:bg-gray-50' }}">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Consulter les archives</span>
                  </div>
                  <span class="rounded bg-brand-100 px-1.5 py-0.5 text-[10px] font-bold text-brand-800">Actif</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-gray-500 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Historique des consultations</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- 4. Menu Documentation -->
        <div class="relative">
          <button id="documentation-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="documentation-menu"
            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition-all hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">
            <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span>Documentation</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 opacity-70 transition-transform" data-chevron aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div id="documentation-menu" class="absolute left-0 z-40 mt-2 hidden w-80 origin-top-left rounded-xl border border-gray-100 bg-white p-2.5 shadow-xl">
            <!-- Groupe 1: Réglementation Archives -->
            <div class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-brand-700">Réglementation Archives</div>
            <ul class="mb-3 space-y-0.5 text-sm">
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    <span>Lois et textes juridiques</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Cybersécurité & Normes</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
            </ul>

            <!-- Groupe 2: Organisation & Gouvernance -->
            <div class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-brand-700 border-t border-gray-100 pt-2">Organisation & Gouvernance</div>
            <ul class="space-y-0.5 text-sm">
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Organigramme DGB</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>Audit & Conformité</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- 5. Menu Administration -->
        <div class="relative">
          <button id="administration-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="administration-menu"
            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition-all hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">
            <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Administration</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 opacity-70 transition-transform" data-chevron aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div id="administration-menu" class="absolute left-0 z-40 mt-2 hidden w-80 origin-top-left rounded-xl border border-gray-100 bg-white p-2.5 shadow-xl">
            <!-- Section 1: Gestion & Sécurité -->
            <div class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-brand-700">Gestion & Sécurité</div>
            <ul class="mb-3 space-y-0.5 text-sm">
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Comptes utilisateurs</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    <span>Connexions suspendues</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span>Mots de passe oubliés</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
            </ul>

            <!-- Section 2: Système & Suivi -->
            <div class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-brand-700 border-t border-gray-100 pt-2">Système & Suivi</div>
            <ul class="space-y-0.5 text-sm">
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                    <span>Sauvegardes de données</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Statistiques & Rapports</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                  <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Journal d'activités</span>
                  </div>
                  <span class="text-[10px] text-gray-400 font-medium">Bientôt</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

      </nav>
    </div>

    <!-- Right Side Actions (Notifications & User Avatar) -->
    <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
      <!-- Notifications -->
      <div class="relative">
        <button id="notif-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="notif-panel"
          class="relative inline-flex h-9 w-9 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2"
          aria-label="Notifications">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
            <path d="M6 8a6 6 0 0 1 12 0c0 4 1.5 5.5 2.5 6.7.3.3.1.8-.3.8H3.8c-.4 0-.6-.5-.3-.8C4.5 13.5 6 12 6 8Z"/><path d="M10 19a2 2 0 0 0 4 0"/>
          </svg>
        </button>
        <div id="notif-panel" class="absolute right-0 z-40 mt-2 hidden w-64 rounded-xl border border-gray-100 bg-white p-4 text-sm text-gray-500 shadow-xl">
          Aucune nouvelle notification pour le moment.
        </div>
      </div>

      <!-- Avatar / Compte -->
      <div class="relative">
        <button id="avatar-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="avatar-menu"
          class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2 sm:pr-3">
          <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-sm font-bold text-brand-800">A</span>
          <span class="hidden text-sm font-semibold text-gray-700 md:inline">Administrateur</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div id="avatar-menu" class="absolute right-0 z-40 mt-2 hidden w-56 rounded-xl border border-gray-100 bg-white p-1.5 shadow-xl">
          <div class="border-b border-gray-100 px-3 py-2.5">
            <p class="text-sm font-bold text-gray-900">Admin ArchiDoc</p>
            <p class="text-xs text-gray-500">DGB · Service Archives</p>
          </div>
          <a href="#" class="block rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Mon profil</a>
          <a href="#" class="block rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Paramètres</a>
          <a href="#" class="block rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50">Déconnexion</a>
        </div>
      </div>
    </div>
  </div>
</header>
