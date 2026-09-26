<div id="mobile-drawer-backdrop" class="fixed inset-0 z-40 hidden bg-gray-900/50 xl:hidden" aria-hidden="true"></div>

<div id="mobile-drawer"
  class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] -translate-x-full bg-brand-800 text-white shadow-2xl transition-transform duration-200 ease-out xl:hidden"
  role="dialog" aria-modal="true" aria-label="Menu de navigation">

  <!-- En-tête Tiroir Mobile -->
  <div class="flex h-16 items-center justify-between border-b border-white/15 px-4">
    <span class="flex items-center gap-2.5 font-extrabold tracking-tight text-white">
      <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-brand-800 shadow-sm">
        <img src="{{asset('media/img/logo_archidoc_dgb.png')}}" alt="logo ARCHIDOC DGB" class="rounded-full">
      </span>
      ARCHIDOC DGB
    </span>
    <button id="mobile-drawer-close" type="button" class="rounded-lg p-2 hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Fermer le menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>

  <nav class="styled-scroll h-[calc(100%-4rem)] overflow-y-auto px-3 py-4 space-y-6">

    <!-- 1. Accueil -->
    <div>
      <a href="{{ route('archives.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('archives.index') ? 'bg-white text-brand-800 shadow-sm font-bold' : 'text-brand-50 hover:bg-white/10 hover:text-white' }}">
        <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>Accueil</span>
      </a>
    </div>

    <!-- 2. Création -->
    <div>
      <div class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-brand-200/80">Création</div>
      <ul class="space-y-1">
        <li>
          <a href="{{ route('archives.create') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('archives.create') ? 'bg-white font-bold text-brand-800 shadow-sm' : 'text-brand-50 hover:bg-white/10 hover:text-white' }}">
            <div class="flex items-center gap-2.5">
              <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span>Nouvelle Archive</span>
            </div>
            <span class="rounded bg-brand-100 px-1.5 py-0.5 text-[10px] font-bold text-brand-900">Actif</span>
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-200/70 hover:bg-white/5">
            <span>Nouveau Type d'archive</span>
            <span class="text-[10px]">Bientôt</span>
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-200/70 hover:bg-white/5">
            <span>Nouvel Emplacement</span>
            <span class="text-[10px]">Bientôt</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- 3. Consultation -->
    <div>
      <div class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-brand-200/80">Consultation</div>
      <ul class="space-y-1">
        <li>
          <a href="{{ route('archives.search') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('archives.search') || request()->routeIs('archives.show') ? 'bg-white font-bold text-brand-800 shadow-sm' : 'text-brand-50 hover:bg-white/10 hover:text-white' }}">
            <div class="flex items-center gap-2.5">
              <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              <span>Consulter les archives</span>
            </div>
            <span class="rounded bg-brand-100 px-1.5 py-0.5 text-[10px] font-bold text-brand-900">Actif</span>
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-200/70 hover:bg-white/5">
            <span>Historique des consultations</span>
            <span class="text-[10px]">Bientôt</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- 4. Documentation -->
    <div>
      <div class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-brand-200/80">Documentation</div>
      <ul class="space-y-1">
        <li>
          <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-200/70 hover:bg-white/5">
            <span>Réglementation Archives</span>
            <span class="text-[10px]">Bientôt</span>
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-200/70 hover:bg-white/5">
            <span>Organisation & Gouvernance</span>
            <span class="text-[10px]">Bientôt</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- 5. Administration -->
    <div>
      <div class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-brand-200/80">Administration</div>
      <ul class="space-y-1">
        <li>
          <a href="{{ route('users.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm {{ request()->routeIs('users.*') ? 'bg-white font-bold text-brand-800 shadow-sm' : 'text-brand-50 hover:bg-white/10 hover:text-white' }}">
            <div class="flex items-center gap-2.5">
              <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              <span>Comptes utilisateurs</span>
            </div>
            <span class="rounded bg-brand-100 px-1.5 py-0.5 text-[10px] font-bold text-brand-900">Actif</span>
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-200/70 hover:bg-white/5">
            <span>Sauvegardes & Système</span>
            <span class="text-[10px]">Bientôt</span>
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-200/70 hover:bg-white/5">
            <span>Journal & Statistiques</span>
            <span class="text-[10px]">Bientôt</span>
          </a>
        </li>
      </ul>
    </div>

  </nav>
</div>
