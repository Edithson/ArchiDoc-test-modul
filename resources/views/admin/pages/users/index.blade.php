@extends('admin.layout.app')

@section('title', 'Gestion des comptes utilisateurs — ArchiDoc DGB')
@section('meta_description', 'Administration et gestion des comptes utilisateurs, attributions des rôles et contrôle des accès — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
        </span>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Gestion des Comptes Utilisateurs</h1>
      </div>
      <p class="mt-1 text-sm text-gray-500">Consultez, créez, modifiez, suspendez ou réactivez les comptes des utilisateurs de l'application.</p>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau compte utilisateur
      </a>
    </div>
  </div>

  <!-- Messages Flash Success / Error -->
  @if(session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 flex items-center justify-between" role="status">
      <div class="flex items-center gap-2">
        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-800 flex items-center gap-2" role="alert">
      <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <!-- Carte des Filtres de Recherche -->
  <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <form method="GET" action="{{ route('users.index') }}" class="p-4 sm:p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        
        <!-- Recherche par nom / email / matricule -->
        <div>
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Recherche (Nom, Email, Matricule)
          </label>
          <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Ex. MAT-0001, Jean..."
            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
        </div>

        <!-- Filtre Rôle -->
        <div>
          <label for="roles" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Niveau d'accès (Rôle)
          </label>
          <div class="relative">
            <select id="roles" name="roles" onchange="this.form.submit()"
              class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-8 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <option value="">Tous les rôles</option>
              @foreach($roleOptions as $val => $label)
                <option value="{{ $val }}" {{ ($filters['roles'] ?? '') === $val ? 'selected' : '' }}>
                  {{ ucfirst($val) }}
                </option>
              @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>

        <!-- Filtre Département -->
        <div>
          <label for="departement" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Département / Groupe
          </label>
          <div class="relative">
            <select id="departement" name="departement" onchange="this.form.submit()"
              class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-8 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <option value="">Tous les départements</option>
              @foreach($departments as $dept)
                <option value="{{ $dept['sigle'] }}" {{ ($filters['departement'] ?? '') === $dept['sigle'] ? 'selected' : '' }}>
                  {{ $dept['sigle'] }} — {{ $dept['nom'] }}
                </option>
              @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>

        <!-- Filtre Statut -->
        <div>
          <label for="statut" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Statut du compte
          </label>
          <div class="relative">
            <select id="statut" name="statut" onchange="this.form.submit()"
              class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-8 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <option value="">Tous les statuts</option>
              <option value="1" {{ (string)($filters['statut'] ?? '') === '1' ? 'selected' : '' }}>Actifs uniquement</option>
              <option value="0" {{ (string)($filters['statut'] ?? '') === '0' ? 'selected' : '' }}>Suspendus uniquement</option>
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>

      </div>

      <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
        <div class="text-xs text-gray-500">
          <span class="font-bold text-gray-900">{{ $users->total() }}</span> utilisateur(s) trouvé(s)
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
            Réinitialiser
          </a>
          <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-700 px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-brand-800">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Filtrer
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Tableau des Utilisateurs -->
  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
        <thead class="bg-gray-50/80 text-xs font-bold uppercase tracking-wider text-gray-500">
          <tr>
            <th scope="col" class="px-6 py-3.5">Utilisateur</th>
            <th scope="col" class="px-6 py-3.5">Matricule</th>
            <th scope="col" class="px-6 py-3.5">Département</th>
            <th scope="col" class="px-6 py-3.5">Rôle</th>
            <th scope="col" class="px-6 py-3.5">Statut</th>
            <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
          @forelse($users as $userItem)
            <tr class="hover:bg-brand-50/20 transition-colors">

              <!-- Utilisateur (Avatar + Nom + Email) -->
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                  @if($userItem->avatar)
                    <img src="{{ asset('storage/' . $userItem->avatar) }}" alt="{{ $userItem->name }}" class="h-9 w-9 rounded-full object-cover">
                  @else
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-100 font-bold text-xs text-brand-800">
                      {{ strtoupper(substr($userItem->name ?? 'U', 0, 1)) }}
                    </span>
                  @endif
                  <div>
                    <p class="font-bold text-gray-900 leading-tight">
                      {{ $userItem->name }}
                      @if($userItem->id === auth()->id())
                        <span class="ml-1 rounded bg-brand-100 px-1.5 py-0.5 text-[10px] font-bold text-brand-800">(Vous)</span>
                      @endif
                    </p>
                    <p class="text-xs text-gray-500">{{ $userItem->email }}</p>
                    @if($userItem->phone)
                      <p class="text-[11px] text-gray-400">{{ $userItem->phone }}</p>
                    @endif
                  </div>
                </div>
              </td>

              <!-- Matricule -->
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="font-mono text-xs font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded">
                  {{ $userItem->matricule ?? 'N/A' }}
                </span>
              </td>

              <!-- Département -->
              <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-700">
                {{ $userItem->departement }}
              </td>

              <!-- Rôle -->
              <td class="px-6 py-4 whitespace-nowrap">
                @if($userItem->isSuper())
                  <span class="inline-flex items-center rounded-md bg-purple-50 px-2.5 py-1 text-xs font-bold text-purple-700 border border-purple-200">
                    Super Privilégié
                  </span>
                @elseif($userItem->isPrivileged())
                  <span class="inline-flex items-center rounded-md bg-brand-50 px-2.5 py-1 text-xs font-bold text-brand-700 border border-brand-200">
                    Privilégié
                  </span>
                @else
                  <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                    Classique
                  </span>
                @endif
              </td>

              <!-- Statut (Actif / Suspendu) -->
              <td class="px-6 py-4 whitespace-nowrap">
                @if($userItem->statut)
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                    Actif
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700 border border-red-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                    Suspendu
                  </span>
                @endif
              </td>

              <!-- Actions (Modifier, Suspendre/Activer, Supprimer) -->
              <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                <div class="flex items-center justify-end gap-2">
                  
                  <!-- Bouton Modifier -->
                  <a href="{{ route('users.edit', $userItem->id) }}" title="Modifier le compte"
                    class="inline-flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Éditer
                  </a>

                  <!-- Bouton Suspendre / Réactiver -->
                  @if($userItem->id !== auth()->id())
                    <form method="POST" action="{{ route('users.toggle-status', $userItem->id) }}" class="inline">
                      @csrf
                      <button type="submit"
                        title="{{ $userItem->statut ? 'Suspendre ce compte' : 'Réactiver ce compte' }}"
                        class="inline-flex items-center gap-1 rounded-lg border px-2.5 py-1.5 text-xs font-semibold transition-colors {{ $userItem->statut ? 'border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                        @if($userItem->statut)
                          <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                          Suspendre
                        @else
                          <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                          Réactiver
                        @endif
                      </button>
                    </form>

                    <!-- Bouton Supprimer -->
                    <form method="POST" action="{{ route('users.destroy', $userItem->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte utilisateur ?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" title="Supprimer le compte"
                        class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-1.5 text-red-600 hover:bg-red-100 transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </form>
                  @endif

                </div>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                  <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                  </div>
                  <h3 class="text-sm font-bold text-gray-800">Aucun compte utilisateur trouvé</h3>
                  <p class="mt-1 text-xs text-gray-500">Modifiez vos filtres de recherche ou créez un nouveau compte.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
      <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
        {{ $users->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
