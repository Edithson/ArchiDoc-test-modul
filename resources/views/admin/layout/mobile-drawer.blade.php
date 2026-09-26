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
          <a href="{{ asset('document/Loi_001_Regissant_Archives_Cameroun_24072024.pdf') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-50 hover:bg-white/10">
            <div class="flex items-center gap-2.5 truncate">
              <svg class="h-4 w-4 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
              <span class="truncate">Loi N° 001/2024 (Archives)</span>
            </div>
            <span class="rounded bg-red-900/40 px-1.5 py-0.5 text-[10px] font-bold text-red-200 shrink-0">PDF</span>
          </a>
        </li>
        <li>
          <a href="{{ asset('document/Loi_2000-010_19-dec_Regissant_Archives_Cameroun.pdf') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-50 hover:bg-white/10">
            <div class="flex items-center gap-2.5 truncate">
              <svg class="h-4 w-4 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
              <span class="truncate">Loi N° 2000/010 (19/12/2000)</span>
            </div>
            <span class="rounded bg-red-900/40 px-1.5 py-0.5 text-[10px] font-bold text-red-200 shrink-0">PDF</span>
          </a>
        </li>
        <li>
          <a href="{{ asset('document/Loi_Cybersecurite_Criminalite.pdf') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-50 hover:bg-white/10">
            <div class="flex items-center gap-2.5 truncate">
              <svg class="h-4 w-4 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <span class="truncate">Cybersécurité & Cybercriminalité</span>
            </div>
            <span class="rounded bg-red-900/40 px-1.5 py-0.5 text-[10px] font-bold text-red-200 shrink-0">PDF</span>
          </a>
        </li>
        <li>
          <a href="{{ asset('document/ORGANIGRAMME_DGB.pdf') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-50 hover:bg-white/10">
            <div class="flex items-center gap-2.5 truncate">
              <svg class="h-4 w-4 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
              <span class="truncate">Organigramme DGB</span>
            </div>
            <span class="rounded bg-red-900/40 px-1.5 py-0.5 text-[10px] font-bold text-red-200 shrink-0">PDF</span>
          </a>
        </li>
        <li>
          <a href="{{ asset('document/Rapport_Audit_Archivage-Novembre-2019.pdf') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-brand-50 hover:bg-white/10">
            <div class="flex items-center gap-2.5 truncate">
              <svg class="h-4 w-4 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
              <span class="truncate">Audit Archivage (2019)</span>
            </div>
            <span class="rounded bg-red-900/40 px-1.5 py-0.5 text-[10px] font-bold text-red-200 shrink-0">PDF</span>
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
