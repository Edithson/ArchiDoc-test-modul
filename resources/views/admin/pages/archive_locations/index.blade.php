@extends('admin.layout.app')

@section('title', 'Gestion des Emplacements — ' . setting('app_name', 'ArchiDoc'))
@section('meta_description', 'Administration et gestion des emplacements physiques (magasins) et virtuels (serveurs) d\'archivage')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Emplacements d'archives</span>
  </nav>

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </span>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Gestion des Emplacements</h1>
      </div>
      <p class="mt-1 text-sm text-gray-500">Gérez les emplacements physiques (magasins, salles) et virtuels (serveurs, stockage NAS) où sont conservées vos archives.</p>
    </div>

    <div class="flex items-center gap-3">
      @can('archivelocation.create')
      <a href="{{ route('archive-locations.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvel emplacement
      </a>
      @endcan
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
    <form method="GET" action="{{ route('archive-locations.index') }}" class="p-4 sm:p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-4 items-end">
        
        <!-- Recherche par nom / description / adresse -->
        <div class="sm:col-span-2">
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Recherche textuelle</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </span>
            <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Ex: Magasin, Serveur, NAS, Salle..." class="w-full rounded-xl border border-gray-300 py-2 pl-9 pr-3 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
          </div>
        </div>

        <!-- Filtre par type (Physique / Virtuel) -->
        <div>
          <label for="type" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Type d'emplacement</label>
          <select name="type" id="type" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
            <option value="">Tous les types</option>
            <option value="1" {{ (string)$typeFilter === '1' ? 'selected' : '' }}>Physique (Magasin / Site)</option>
            <option value="2" {{ (string)$typeFilter === '2' ? 'selected' : '' }}>Virtuel (Serveur / Cloud)</option>
          </select>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center gap-2">
          <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            Filtrer
          </button>
          @if($search || $typeFilter)
            <a href="{{ route('archive-locations.index') }}" class="inline-flex items-center gap-1 rounded-xl border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
              Réinitialiser
            </a>
          @endif
        </div>

      </div>
    </form>
  </div>

  <!-- Tableau des Emplacements -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50/80 text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100">
          <tr>
            <th scope="col" class="px-6 py-4">Nom / Sigle</th>
            <th scope="col" class="px-6 py-4">Type</th>
            <th scope="col" class="px-6 py-4">Description</th>
            <th scope="col" class="px-6 py-4">Localisation / Adresse / IP</th>
            <th scope="col" class="px-6 py-4 text-center">Archives Rattachées</th>
            <th scope="col" class="px-6 py-4">Créé par</th>
            <th scope="col" class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 font-medium">
          @forelse($archiveLocations as $location)
            <tr class="hover:bg-brand-50/30 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">
                <div class="flex items-center gap-2.5">
                  @if($location->type === \App\Models\ArchiveLocation::TYPE_PHYSICAL)
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800 font-bold">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                      </svg>
                    </span>
                  @else
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-100 text-purple-800 font-bold">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                      </svg>
                    </span>
                  @endif
                  <span class="font-extrabold text-brand-900">{{ $location->name }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                @if($location->type === \App\Models\ArchiveLocation::TYPE_PHYSICAL)
                  <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-800 ring-1 ring-amber-700/10">
                    Physique (Magasin)
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2.5 py-1 text-xs font-bold text-purple-800 ring-1 ring-purple-700/10">
                    Virtuel (Serveur)
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                {{ $location->description ?? '—' }}
              </td>
              <td class="px-6 py-4 text-gray-700 font-mono text-xs">
                {{ $location->location ?? '—' }}
              </td>
              <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-700">
                  {{ $location->archives_count }} {{ Str::plural('archive', $location->archives_count) }}
                </span>
              </td>
              <td class="px-6 py-4 text-xs text-gray-500">
                {{ $location->creator->name ?? 'Système' }}
                <div class="text-[10px] text-gray-400">{{ $location->created_at ? $location->created_at->format('d/m/Y') : '' }}</div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  @can('archivelocation.update')
                  <a href="{{ route('archive-locations.edit', $location) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-brand-700" title="Modifier l'emplacement">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                  </a>
                  @endcan
                  @can('archivelocation.delete')
                  <form method="POST" action="{{ route('archive-locations.destroy', $location) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet emplacement ?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600" title="Supprimer l'emplacement">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </form>
                  @endcan
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  </svg>
                </div>
                <p class="mt-3 text-sm font-semibold">Aucun emplacement trouvé.</p>
                <p class="mt-1 text-xs text-gray-400">Essayez de modifier votre recherche ou ajoutez un nouvel emplacement.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($archiveLocations->hasPages())
      <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
        {{ $archiveLocations->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
