@php
  $isHome = request()->routeIs('archives.index');

  // Détermination dynamique de la section active
  $sidebarTitle = '';
  $sidebarNav = [];

  if (request()->routeIs('personnels.*') || request()->routeIs('pieces.*') || request()->routeIs('activity-logs.personnel-consultations')) {
      $sidebarTitle = 'GESTION DU PERSONNEL';
      $sidebarNav = [];
      if (auth()->user()?->hasPermission('Personnel', 'read')) {
          $sidebarNav[] = ['title' => 'Dossiers du personnel', 'route' => route('personnels.index'), 'active' => request()->routeIs('personnels.index') || request()->routeIs('personnels.show') || request()->routeIs('personnels.edit'), 'icon' => 'users', 'badge' => 'Actif'];
          $sidebarNav[] = ['title' => 'Historique des consultations', 'route' => route('activity-logs.personnel-consultations'), 'active' => request()->routeIs('activity-logs.personnel-consultations'), 'icon' => 'default', 'badge' => 'Actif'];
      }
      if (auth()->user()?->hasPermission('Piece', 'read')) {
          $sidebarNav[] = ['title' => 'Pièces d\'intégration', 'route' => route('pieces.index'), 'active' => request()->routeIs('pieces.*'), 'icon' => 'default', 'badge' => 'Actif'];
      }
      if (auth()->user()?->hasPermission('Personnel', 'create')) {
          $sidebarNav[] = ['title' => 'Nouveau dossier agent', 'route' => route('personnels.create'), 'active' => request()->routeIs('personnels.create'), 'icon' => 'default', 'badge' => 'Actif'];
      }
  } elseif (request()->routeIs('archives.create') || request()->routeIs('archive-types.*') || request()->routeIs('archive-locations.*') || request()->routeIs('departments.*')) {
      $sidebarTitle = 'CRÉATION';
      $sidebarNav = [];
      $user = auth()->user();
      if ($user?->hasPermission('Archive', 'create')) {
          $sidebarNav[] = ['title' => 'Nouvelle Archive', 'route' => route('archives.create'), 'active' => request()->routeIs('archives.create'), 'icon' => 'archive', 'badge' => 'Actif'];
      }
      if ($user?->hasPermission('ArchiveType', 'read')) {
          $sidebarNav[] = ['title' => 'Types d\'archives', 'route' => route('archive-types.index'), 'active' => request()->routeIs('archive-types.*'), 'icon' => 'default', 'badge' => 'Actif'];
      }
      if ($user?->hasPermission('ArchiveLocation', 'read')) {
          $sidebarNav[] = ['title' => 'Emplacements', 'route' => route('archive-locations.index'), 'active' => request()->routeIs('archive-locations.*'), 'icon' => 'default', 'badge' => 'Actif'];
      }
      if ($user?->hasPermission('Department', 'read')) {
          $sidebarNav[] = ['title' => 'Groupes d\'accès / Départements', 'route' => route('departments.index'), 'active' => request()->routeIs('departments.*'), 'icon' => 'default', 'badge' => 'Actif'];
      }
  } elseif (request()->routeIs('archives.search') || request()->routeIs('archives.show') || request()->routeIs('activity-logs.archives-consultations')) {
      $sidebarTitle = 'CONSULTATION';
      $sidebarNav = [];
      $user = auth()->user();
      if ($user?->hasPermission('Archive', 'read')) {
          $sidebarNav[] = ['title' => 'Consulter les archives', 'route' => route('archives.search'), 'active' => request()->routeIs('archives.search') || request()->routeIs('archives.show'), 'icon' => 'search', 'badge' => 'Actif'];
          $sidebarNav[] = ['title' => 'Historique des consultations', 'route' => route('activity-logs.archives-consultations'), 'active' => request()->routeIs('activity-logs.archives-consultations'), 'icon' => 'default', 'badge' => 'Actif'];
      }
  } elseif (request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('activity-logs.index') || request()->routeIs('activity-logs.auth') || request()->routeIs('activity-logs.system') || request()->routeIs('settings.*')) {
      $sidebarTitle = 'ADMINISTRATION';
      $sidebarNav = [];
      $user = auth()->user();
      if ($user?->hasPermission('User', 'read')) {
          $sidebarNav[] = ['title' => 'Comptes utilisateurs', 'route' => route('users.index'), 'active' => request()->routeIs('users.*'), 'icon' => 'users', 'badge' => 'Actif'];
      }
      if ($user?->hasPermission('Role', 'read')) {
          $sidebarNav[] = ['title' => 'Habilitations & Rôles', 'route' => route('roles.index'), 'active' => request()->routeIs('roles.*'), 'icon' => 'shield', 'badge' => 'Actif'];
      }
      if ($user?->hasPermission('Setting', 'read')) {
          $sidebarNav[] = ['title' => 'Paramètres du système', 'route' => route('settings.index'), 'active' => request()->routeIs('settings.*'), 'icon' => 'default', 'badge' => 'Actif'];
      }
      $sidebarNav[] = ['title' => 'Arbre des événements', 'route' => route('activity-logs.index'), 'active' => request()->routeIs('activity-logs.index'), 'icon' => 'default', 'badge' => 'Actif'];
      if ($user?->hasPermission('User', 'read') || $user?->isSuper()) {
          $sidebarNav[] = ['title' => 'Sécurité & Accès', 'route' => route('activity-logs.auth'), 'active' => request()->routeIs('activity-logs.auth'), 'icon' => 'default', 'badge' => 'Actif'];
      }
      if ($user?->hasPermission('Setting', 'read') || $user?->isSuper()) {
          $sidebarNav[] = ['title' => 'Diagnostic Erreurs', 'route' => route('activity-logs.system'), 'active' => request()->routeIs('activity-logs.system'), 'icon' => 'default', 'badge' => 'Actif'];
      }
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
