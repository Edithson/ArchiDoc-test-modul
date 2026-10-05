@extends('admin.layout.app')

@section('title', 'Habilitations & Niveaux d\'Accès — ArchiDoc DGB')
@section('meta_description', 'Configuration avancée des rôles et des niveaux d\'accès par modèle — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <span class="text-gray-400">Administration</span>
    <span>/</span>
    <span class="text-gray-900 font-bold">Habilitations & Rôles</span>
  </nav>

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </span>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Configuration des Habilitations par Rôle</h1>
      </div>
      <p class="mt-1 text-sm text-gray-500">Définissez les droits d'accès fonctionnels (lecture, écriture, téléchargement, suppression) sur les 9 modèles clés de l'application.</p>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        Gestion des utilisateurs
      </a>
      <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau rôle
      </a>
    </div>
  </div>

  <!-- Messages Flash -->
  @if(session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 flex items-center justify-between" role="status">
      <div class="flex items-center gap-2">
        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-800 flex items-center gap-2" role="alert">
      <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <!-- Statistiques KPI -->
  <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Rôles configurés</p>
        <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ count($roles) }}</p>
      </div>
      <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-700">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
      </span>
    </div>

    <div class="rounded-2xl border border-purple-200 bg-purple-50/50 p-5 shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-purple-800">Modèles Gérés (Matrice JSON)</p>
        <p class="mt-1 text-2xl font-extrabold text-purple-900">9 Modèles</p>
      </div>
      <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
      </span>
    </div>

    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-emerald-800">Sécurité & Traçabilité</p>
        <p class="mt-1 text-2xl font-extrabold text-emerald-900">Active (Boîte Noire)</p>
      </div>
      <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </span>
    </div>
  </div>

  <!-- Cartes des Rôles et de leurs Habilitations -->
  <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    @foreach($roles as $roleData)
      @php
        $r = $roleData['model'];
        $userCount = $roleData['user_count'];
        $isProtected = $roleData['is_protected'];
        $permissions = $r->permissions ?? [];
      @endphp
      <div class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-xs transition-all hover:shadow-md">
        
        <div>
          <!-- En-tête de la carte Rôle -->
          <div class="flex items-start justify-between gap-3 mb-3">
            <div>
              <div class="flex items-center gap-2">
                <h3 class="text-lg font-black tracking-tight text-gray-900">{{ $r->name }}</h3>
                @if($isProtected)
                  <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-0.5 text-[10px] font-extrabold text-purple-700 border border-purple-200" title="Rôle système prédéfini">
                    Système
                  </span>
                @endif
              </div>
              <p class="mt-1 text-xs text-gray-500 leading-relaxed">{{ $r->description ?? 'Aucune description renseignée.' }}</p>
            </div>
            
            <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-brand-50 px-2.5 py-1 text-xs font-bold text-brand-800 border border-brand-200">
              <svg class="h-3.5 w-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              {{ $userCount }} user{{ $userCount > 1 ? 's' : '' }}
            </span>
          </div>

          <!-- Matrice Synthétique des Modèles (Badge par modèle) -->
          <div class="mt-4 border-t border-gray-100 pt-3.5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Habilitations par Modèle (JSON)</p>
            
            <div class="grid grid-cols-2 gap-1.5 text-xs">
              @foreach($modelDefinitions as $modelKey => $def)
                @php
                  $totalActions = count($def['actions']);
                  $activeActions = 0;
                  if (str_contains(strtolower($r->name), 'super')) {
                      $activeActions = $totalActions;
                  } else {
                      foreach ($def['actions'] as $act => $label) {
                          if (!empty($permissions[$modelKey][$act])) {
                              $activeActions++;
                          }
                      }
                  }
                  $badgeColor = $activeActions === $totalActions ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($activeActions > 0 ? 'bg-indigo-50 text-indigo-800 border-indigo-200' : 'bg-gray-50 text-gray-400 border-gray-200');
                @endphp
                <div class="flex items-center justify-between rounded-lg border px-2 py-1 {{ $badgeColor }}">
                  <span class="font-semibold truncate text-[11px]">{{ $def['label'] }}</span>
                  <span class="font-mono text-[10px] font-bold ml-1">{{ $activeActions }}/{{ $totalActions }}</span>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- Actions du Rôle -->
        <div class="mt-6 border-t border-gray-100 pt-4 flex items-center justify-between gap-2">
          <a href="{{ route('roles.edit', $r) }}" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-brand-700 px-3 py-2 text-xs font-bold text-white shadow-xs hover:bg-brand-800 transition">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Configurer les habilitations
          </a>

          @if($isProtected)
            <span class="inline-flex items-center gap-1 rounded-xl border border-purple-200 bg-purple-50 px-2.5 py-2 text-[11px] font-extrabold text-purple-700" title="Rôle système primaire non supprimable">
              <svg class="h-3.5 w-3.5 text-purple-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              Système
            </span>
          @elseif($userCount > 0)
            <button type="button" disabled class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-gray-100 p-2 text-gray-400 cursor-not-allowed" title="Impossible de supprimer : {{ $userCount }} utilisateur(s) détienne(nt) actuellement ce rôle">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          @else
            <form method="POST" action="{{ route('roles.destroy', $r) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer le rôle personnalisé « {{ $r->name }} » ?');" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 transition" title="Supprimer ce rôle personnalisé">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </form>
          @endif
        </div>

      </div>
    @endforeach
  </div>

</div>
@endsection
