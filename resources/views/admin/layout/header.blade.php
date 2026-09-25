<header class="sticky top-0 z-30 border-b border-gray-200 bg-white">
  <div class="mx-auto flex h-16 max-w-[1600px] items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">

    <div class="flex min-w-0 items-center gap-3">
      <!-- Bouton menu mobile -->
      <button id="mobile-menu-btn" type="button"
        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2 xl:hidden"
        aria-label="Ouvrir le menu de navigation" aria-expanded="false" aria-controls="mobile-drawer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>

      <!-- Logo -->
      <a href="{{ route('archives.index') }}" class="flex shrink-0 items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-700 text-white shadow-sm">
          <img src="{{asset('media/img/logo_archidoc_dgb.png')}}" alt="logo ARCHIDOC DGB" class="rounded-full">
        </span>
        <span class="hidden flex-col leading-none sm:flex">
          <span class="text-sm font-bold tracking-tight text-gray-900">ARCHIDOC</span>
          <span class="text-[11px] font-medium tracking-wide text-gray-400">DGB</span>
        </span>
      </a>

      <!-- Navigation desktop -->
      <nav aria-label="Navigation principale" class="hidden min-w-0 items-center gap-1 whitespace-nowrap pl-2 text-sm text-gray-500 xl:flex">
        <a href="{{ route('archives.index') }}" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Accueil</a>
        <span class="text-gray-300" aria-hidden="true">/</span>

        <div class="relative">
          <button id="creations-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="creations-menu"
            class="inline-flex items-center gap-1 rounded px-2 py-1.5 font-semibold text-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">
            Créations
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 opacity-70 transition-transform" data-chevron aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div id="creations-menu"
            class="absolute left-0 z-40 mt-2 hidden w-72 origin-top-left rounded-lg border border-gray-100 bg-white p-1.5 shadow-lg">
            <ul id="header-creations-list" class="space-y-0.5 text-sm">
              <li>
                <a href="{{ route('archives.index') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0 text-current" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v9a1 1 0 0 0 1 1h3v-6h6v6h3a1 1 0 0 0 1-1v-9"/></svg>
                  <span>Accueil</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0 text-current" aria-hidden="true"><path d="M4 4h7l9 9-7 7-9-9V4Z"/><circle cx="8.5" cy="8.5" r="1.2"/></svg>
                  <span>Nouveau Type d'archive</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0 text-current" aria-hidden="true"><path d="M12 21s7-6.7 7-12a7 7 0 1 0-14 0c0 5.3 7 12 7 12Z"/><circle cx="12" cy="9" r="2.4"/></svg>
                  <span>Nouvel Emplacement</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0 text-current" aria-hidden="true"><circle cx="9" cy="8" r="3.2"/><path d="M2.3 20c0-3.6 3-6.2 6.7-6.2s6.7 2.6 6.7 6.2"/><path d="M15.8 8.3a3 3 0 1 1 3.5 2.9"/><path d="M21.7 20c0-2.8-1.8-5-4.3-5.8"/></svg>
                  <span>Nouveau Groupe d'accès</span>
                </a>
              </li>
              <li>
                <a href="{{ route('archives.create') }}" class="flex items-center gap-2.5 rounded-md {{ request()->routeIs('archives.create') ? 'bg-brand-50 font-semibold text-brand-700' : 'text-gray-700 hover:bg-gray-50' }} px-3 py-2 text-sm">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0 text-current" aria-hidden="true"><path d="M3 7.2 5 4h14l2 3.2"/><rect x="3" y="7.2" width="18" height="12.3" rx="1.5"/><path d="M12 11v5M9.5 13.5h5"/></svg>
                  <span>Nouvelle Archive</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0 text-current" aria-hidden="true"><circle cx="9" cy="7.5" r="3.5"/><path d="M2 20c0-3.9 3.1-6.3 7-6.3"/><path d="M17.5 12.5v6M14.5 15.5h6"/></svg>
                  <span>Nouveau Dossier du personnel</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="{{ route('archives.search') }}" class="rounded px-2 py-1.5 {{ request()->routeIs('archives.search') ? 'font-semibold text-brand-700' : 'hover:text-gray-900' }} focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Consultations</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Réglementation Archives</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Organigramme DGB</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="max-w-[13rem] truncate rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600" title="Organisation des archives de la DGB">Organisation des archives de la DGB</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Service Archives</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Administration</a>
      </nav>
    </div>

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
        <div id="notif-panel" class="absolute right-0 z-40 mt-2 hidden w-64 rounded-lg border border-gray-100 bg-white p-4 text-sm text-gray-500 shadow-lg">
          Aucune nouvelle notification pour le moment.
        </div>
      </div>

      <!-- Avatar / compte -->
      <div class="relative">
        <button id="avatar-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="avatar-menu"
          class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2 sm:pr-3">
          <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-800">S</span>
          <span class="hidden text-sm font-medium text-gray-700 md:inline">Service</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div id="avatar-menu" class="absolute right-0 z-40 mt-2 hidden w-56 rounded-lg border border-gray-100 bg-white p-1.5 shadow-lg">
          <div class="border-b border-gray-100 px-3 py-2.5">
            <p class="text-sm font-semibold text-gray-900">Service</p>
            <p class="text-xs text-gray-500">DGB · Rôle Super</p>
          </div>
          <a href="#" class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Mon profil</a>
          <a href="#" class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Paramètres</a>
          <a href="#" class="block rounded-md px-3 py-2 text-sm text-red-600 hover:bg-red-50">Déconnexion</a>
        </div>
      </div>
    </div>
  </div>
</header>
