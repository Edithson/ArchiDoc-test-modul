<div id="mobile-drawer-backdrop" class="fixed inset-0 z-40 hidden bg-gray-900/50 xl:hidden" aria-hidden="true"></div>

<div id="mobile-drawer"
  class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] -translate-x-full bg-brand-700 text-white shadow-xl transition-transform duration-200 ease-out xl:hidden"
  role="dialog" aria-modal="true" aria-label="Menu de navigation">

  <div class="flex h-16 items-center justify-between border-b border-white/15 px-4">
    <span class="flex items-center gap-2 font-semibold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
        <path d="M3 7.2 5 4h14l2 3.2"/><rect x="3" y="7.2" width="18" height="12.3" rx="1.5"/><path d="M8 11.5h8"/>
      </svg>
      ArchiDoc
    </span>
    <button id="mobile-drawer-close" type="button" class="rounded-md p-2 hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Fermer le menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>

  <nav class="styled-scroll h-[calc(100%-4rem)] overflow-y-auto px-3 py-4">
    <ul id="mobile-toplevel-list" class="space-y-0.5 text-sm">
      <li><a href="{{ route('archives.index') }}" class="block rounded-lg px-3 py-2.5 text-brand-50 hover:bg-white/10 hover:text-white">Accueil</a></li>
      <li><a href="#" class="block rounded-lg px-3 py-2.5 text-brand-50 hover:bg-white/10 hover:text-white">Consultations</a></li>
      <li><a href="#" class="block rounded-lg px-3 py-2.5 text-brand-50 hover:bg-white/10 hover:text-white">Réglementation Archives</a></li>
      <li><a href="#" class="block rounded-lg px-3 py-2.5 text-brand-50 hover:bg-white/10 hover:text-white">Organigramme DGB</a></li>
      <li><a href="#" class="block rounded-lg px-3 py-2.5 text-brand-50 hover:bg-white/10 hover:text-white">Organisation des archives de la DGB</a></li>
      <li><a href="#" class="block rounded-lg px-3 py-2.5 text-brand-50 hover:bg-white/10 hover:text-white">Service Archives</a></li>
      <li><a href="#" class="block rounded-lg px-3 py-2.5 text-brand-50 hover:bg-white/10 hover:text-white">Administration</a></li>
    </ul>

    <div class="mb-2 mt-5 px-3 text-xs font-semibold uppercase tracking-wider text-brand-100/80">Créations</div>
    <ul id="mobile-creations-list" class="space-y-1">
      <li>
        <a href="{{ route('archives.index') }}" class="flex items-center gap-3 rounded-lg border-l-4 {{ request()->routeIs('archives.index') ? 'border-brand-600 bg-white font-semibold text-brand-800 shadow-sm' : 'border-transparent text-brand-50 hover:bg-white/10 hover:text-white' }} px-3 py-2.5 text-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v9a1 1 0 0 0 1 1h3v-6h6v6h3a1 1 0 0 0 1-1v-9"/></svg>
          <span class="truncate">Accueil</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center gap-3 rounded-lg border-l-4 border-transparent px-3 py-2.5 text-sm text-brand-50 transition-colors hover:bg-white/10 hover:text-white">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0" aria-hidden="true"><path d="M4 4h7l9 9-7 7-9-9V4Z"/><circle cx="8.5" cy="8.5" r="1.2"/></svg>
          <span class="truncate">Nouveau Type d'archive</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center gap-3 rounded-lg border-l-4 border-transparent px-3 py-2.5 text-sm text-brand-50 transition-colors hover:bg-white/10 hover:text-white">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0" aria-hidden="true"><path d="M12 21s7-6.7 7-12a7 7 0 1 0-14 0c0 5.3 7 12 7 12Z"/><circle cx="12" cy="9" r="2.4"/></svg>
          <span class="truncate">Nouvel Emplacement</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center gap-3 rounded-lg border-l-4 border-transparent px-3 py-2.5 text-sm text-brand-50 transition-colors hover:bg-white/10 hover:text-white">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0" aria-hidden="true"><circle cx="9" cy="8" r="3.2"/><path d="M2.3 20c0-3.6 3-6.2 6.7-6.2s6.7 2.6 6.7 6.2"/><path d="M15.8 8.3a3 3 0 1 1 3.5 2.9"/><path d="M21.7 20c0-2.8-1.8-5-4.3-5.8"/></svg>
          <span class="truncate">Nouveau Groupe d'accès</span>
        </a>
      </li>
      <li>
        <a href="{{ route('archives.create') }}" class="flex items-center gap-3 rounded-lg border-l-4 {{ request()->routeIs('archives.create') ? 'border-brand-600 bg-white font-semibold text-brand-800 shadow-sm' : 'border-transparent text-brand-50 hover:bg-white/10 hover:text-white' }} px-3 py-2.5 text-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0" aria-hidden="true"><path d="M3 7.2 5 4h14l2 3.2"/><rect x="3" y="7.2" width="18" height="12.3" rx="1.5"/><path d="M12 11v5M9.5 13.5h5"/></svg>
          <span class="truncate">Nouvelle Archive</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center gap-3 rounded-lg border-l-4 border-transparent px-3 py-2.5 text-sm text-brand-50 transition-colors hover:bg-white/10 hover:text-white">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0" aria-hidden="true"><circle cx="9" cy="7.5" r="3.5"/><path d="M2 20c0-3.9 3.1-6.3 7-6.3"/><path d="M17.5 12.5v6M14.5 15.5h6"/></svg>
          <span class="truncate">Nouveau Dossier du personnel</span>
        </a>
      </li>
    </ul>
  </nav>
</div>
