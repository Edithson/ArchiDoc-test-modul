@extends('admin.layout.app')

@section('title', 'Nomenclature des pièces d\'intégration — ArchiDoc DGB')
@section('meta_description', 'Gestion de la nomenclature et du statut obligatoire ou optionnel des pièces d\'intégration du personnel — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('personnels.index') }}" class="hover:text-brand-700">Gestion du personnel</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Nomenclature des pièces</span>
  </nav>

  <!-- Messages Flash Success / Error -->
  @if(session('success'))
    <div class="mb-6 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800" role="status">
      <div class="flex items-center gap-2.5">
        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 flex items-center gap-3 shadow-xs" role="alert">
      <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
      <span class="text-sm font-semibold">{{ session('error') }}</span>
    </div>
  @endif

  <!-- En-tête -->
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Nomenclature des Pièces</h1>
      <p class="mt-1 text-sm text-gray-500">Définissez et gérez la liste des pièces constitutives des dossiers d'intégration administrative du personnel.</p>
    </div>

    @if(auth()->user()?->hasPermission('Piece', 'create'))
    <a href="{{ route('pieces.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Ajouter une Pièce
    </a>
    @endif
  </div>

  <!-- Carte des Filtres de Recherche et d'Exigence -->
  <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xs">
    <form method="GET" action="{{ route('pieces.index') }}" class="p-4 sm:p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-12 sm:items-end">
        
        <!-- Recherche par libellé ou description -->
        <div class="sm:col-span-6">
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Recherche par libellé ou description
          </label>
          <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
              <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Ex: Acte de naissance, Casier judiciaire, Diplôme..." class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
          </div>
        </div>

        <!-- Filtre par exigence (Obligatoire / Facultatif) -->
        <div class="sm:col-span-4">
          <label for="obligatory" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Statut d'exigence
          </label>
          <select name="obligatory" id="obligatory" class="w-full rounded-xl border border-gray-300 py-2.5 px-3 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
            <option value="">Toutes les pièces (Tous blocs)</option>
            <option value="1" {{ (string)$obligatoryFilter === '1' ? 'selected' : '' }}>Obligatoire (Block 1)</option>
            <option value="0" {{ (string)$obligatoryFilter === '0' ? 'selected' : '' }}>Facultative (Block 2)</option>
          </select>
        </div>

        <!-- Boutons d'action -->
        <div class="sm:col-span-2 flex items-center gap-2">
          <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
            Filtrer
          </button>
          @if($search || $obligatoryFilter !== null)
            <a href="{{ route('pieces.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-3 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50" title="Réinitialiser">
              ✕
            </a>
          @endif
        </div>

      </div>
    </form>
  </div>

  <!-- Tableau des Pièces -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xs">
    <table class="w-full text-left text-sm text-gray-600">
      <thead class="border-b border-gray-200 bg-gray-50/80 text-xs uppercase font-extrabold text-gray-700 tracking-wider">
        <tr>
          <th scope="col" class="px-6 py-4">Intitulé de la Pièce</th>
          <th scope="col" class="px-6 py-4">Exigence / Statut</th>
          <th scope="col" class="px-6 py-4">Description</th>
          <th scope="col" class="px-6 py-4 text-center">Dossiers Associés</th>
          <th scope="col" class="px-6 py-4 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @forelse($pieces as $piece)
          <tr class="hover:bg-gray-50/60 transition-colors">
            
            <td class="px-6 py-4 font-bold text-gray-900">
              {{ $piece->name }}
            </td>

            <td class="px-6 py-4">
              @if($piece->obligatory)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100/80 px-3 py-1 text-xs font-extrabold text-amber-900 border border-amber-200/70">
                  <span class="h-2 w-2 rounded-full bg-amber-600"></span>
                  Obligatoire (Block 1)
                </span>
              @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-700 border border-slate-200">
                  <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                  Facultative (Block 2)
                </span>
              @endif
            </td>

            <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
              {{ $piece->description ?? 'Aucune précision' }}
            </td>

            <td class="px-6 py-4 text-center">
              <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700">
                {{ $piece->files_count }} dossier(s)
              </span>
            </td>

            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                @if(auth()->user()?->hasPermission('Piece', 'update'))
                <!-- Modifier -->
                <a href="{{ route('pieces.edit', $piece) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-brand-700" title="Modifier la pièce">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </a>
                @endif

                @if(auth()->user()?->hasPermission('Piece', 'delete'))
                <!-- Supprimer -->
                <form method="POST" action="{{ route('pieces.destroy', $piece) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la pièce « {{ addslashes($piece->name) }} » ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-700" title="Supprimer la pièce">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </form>
                @endif
              </div>
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
              Aucune pièce d'intégration ne correspond à vos critères de recherche.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($pieces->hasPages())
      <div class="border-t border-gray-200 px-6 py-4 bg-gray-50/50">
        {{ $pieces->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
