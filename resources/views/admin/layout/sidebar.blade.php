@php
  $isHome = request()->routeIs('archives.index');

  // Détermination dynamique de la section active
  $sidebarTitle = '';
  $sidebarNav = [];

  if (request()->routeIs('archives.create')) {
      $sidebarTitle = 'CRÉATION';
      $sidebarNav = [
          ['title' => 'Nouvelle Archive', 'route' => route('archives.create'), 'active' => request()->routeIs('archives.create'), 'icon' => 'archive', 'badge' => 'Actif'],
          ['title' => 'Nouveau Type d\'archive', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
          ['title' => 'Nouvel Emplacement', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
          ['title' => 'Nouveau Groupe d\'accès', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
          ['title' => 'Nouveau Dossier Personnel', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
      ];
  } elseif (request()->routeIs('archives.search') || request()->routeIs('archives.show')) {
      $sidebarTitle = 'CONSULTATION';
      $sidebarNav = [
          ['title' => 'Consulter les archives', 'route' => route('archives.search'), 'active' => request()->routeIs('archives.search') || request()->routeIs('archives.show'), 'icon' => 'search', 'badge' => 'Actif'],
          ['title' => 'Historique des consultations', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
      ];
  } elseif (request()->routeIs('users.*')) {
      $sidebarTitle = 'ADMINISTRATION';
      $sidebarNav = [
          ['title' => 'Comptes utilisateurs', 'route' => route('users.index'), 'active' => request()->routeIs('users.*'), 'icon' => 'users', 'badge' => 'Actif'],
          ['title' => 'Connexions suspendues', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
          ['title' => 'Mots de passe oubliés', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
          ['title' => 'Sauvegardes de données', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
          ['title' => 'Statistiques & Rapports', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
          ['title' => 'Journal d\'activités', 'route' => '#', 'active' => false, 'icon' => 'default', 'badge' => 'Bientôt'],
      ];
  } elseif (request()->routeIs('profile.*')) {
      $sidebarTitle = 'MON COMPTE';
      $sidebarNav = [
          ['title' => 'Profil & Sécurité', 'route' => route('profile.edit'), 'active' => request()->routeIs('profile.edit'), 'icon' => 'user', 'badge' => 'Actif'],
      ];
  }
@endphp

@if(!$isHome && !empty($sidebarTitle))
<aside class="sticky top-16 hidden h-[calc(100vh-4rem)] w-72 shrink-0 flex-col bg-brand-800 text-white xl:flex shadow-md border-r border-brand-900/30">
  <!-- En-tête dynamique de la barre latérale -->
  <div class="border-b border-white/10 px-6 py-4 flex items-center gap-2.5">
    <span class="h-2 w-2 rounded-full bg-brand-400 animate-pulse"></span>
    <p class="text-xs font-extrabold uppercase tracking-wider text-brand-100">{{ $sidebarTitle }}</p>
  </div>

  <!-- Sous-navigation contextuelle -->
  <nav class="styled-scroll flex-1 overflow-y-auto px-3 py-4" aria-label="Sous-navigation {{ $sidebarTitle }}">
    <ul class="space-y-1">
      @foreach($sidebarNav as $item)
        <li>
          <a href="{{ $item['route'] }}"
            class="flex items-center justify-between gap-2 rounded-xl px-3.5 py-2.5 text-sm transition-all {{ $item['active'] ? 'bg-white font-bold text-brand-800 shadow-sm border-l-4 border-brand-600' : 'text-brand-50 hover:bg-white/10 hover:text-white' }}">
            <div class="flex items-center gap-3 truncate">
              @if($item['icon'] === 'archive')
                <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              @elseif($item['icon'] === 'search')
                <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              @elseif($item['icon'] === 'users')
                <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
              @elseif($item['icon'] === 'user')
                <svg class="h-4 w-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              @else
                <svg class="h-4 w-4 shrink-0 text-current opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              @endif
              <span class="truncate">{{ $item['title'] }}</span>
            </div>
            @if($item['badge'] === 'Actif')
              <span class="rounded bg-brand-100 px-1.5 py-0.5 text-[10px] font-bold text-brand-900 shadow-2xs shrink-0">Actif</span>
            @else
              <span class="text-[10px] text-brand-200/60 font-medium shrink-0">Bientôt</span>
            @endif
          </a>
        </li>
      @endforeach
    </ul>
  </nav>
</aside>
@endif
