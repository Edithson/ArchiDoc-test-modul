@extends('admin.layout.app')

@section('title', 'Gestion des Départements / Services — ArchiDoc DGB')
@section('meta_description', 'Liste et gestion des départements et services constituant les groupes d\'accès — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <span class="text-gray-500">Création</span>
    <span>/</span>
    <span class="text-gray-900 font-bold">Groupes d'accès / Départements</span>
  </nav>

  <!-- Notification de succès -->
  @if(session('success'))
    <div class="mb-6 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-xs">
      <div class="flex items-center gap-3">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
        </span>
        <span class="font-semibold">{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
  @endif

  <!-- En-tête -->
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Groupes d'accès / Départements</h1>
      <p class="mt-1 text-sm text-gray-500">Définissez et gérez les entités administratives constituant les groupes d'accès d'ArchiDoc DGB.</p>
    </div>
    <div>
      <a href="{{ route('departments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-brand-800 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-700">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau Département
      </a>
    </div>
  </div>

  <!-- Barre de recherche et filtre -->
  <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
    <form method="GET" action="{{ route('departments.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </span>
        <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher par nom ou description..." class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
      </div>

      <div class="flex items-center gap-2">
        <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800 transition-colors">
          Rechercher
        </button>
        @if($search)
          <a href="{{ route('departments.index') }}" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
            Réinitialiser
          </a>
        @endif
      </div>
    </form>
  </div>

  <!-- Liste des départements -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xs">
    @if($departments->isEmpty())
      <div class="p-12 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 mb-3">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </span>
        <h3 class="text-base font-bold text-gray-900">Aucun département trouvé</h3>
        <p class="mt-1 text-sm text-gray-500">Essayez de modifier votre terme de recherche ou ajoutez un nouveau département.</p>
        <div class="mt-4">
          <a href="{{ route('departments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2 text-sm font-bold text-white hover:bg-brand-800">
            Ajouter un Département
          </a>
        </div>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
          <thead class="bg-gray-50/80 text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
            <tr>
              <th scope="col" class="px-6 py-3.5">Intitulé / Sigle</th>
              <th scope="col" class="px-6 py-3.5">Description</th>
              <th scope="col" class="px-6 py-3.5">Date de création</th>
              <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach($departments as $dept)
              <tr class="hover:bg-gray-50/60 transition-colors">
                <td class="px-6 py-4 font-bold text-gray-900">
                  <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 font-extrabold text-xs">
                      {{ Str::substr($dept->name, 0, 3) }}
                    </span>
                    <div>
                      <span class="block font-bold text-gray-900">{{ $dept->name }}</span>
                      <span class="block text-[11px] font-normal text-gray-400">ID: #{{ $dept->id }}</span>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-gray-600 max-w-md">
                  {{ $dept->description ?: 'Aucune description renseignée' }}
                </td>
                <td class="px-6 py-4 text-xs text-gray-500 font-mono">
                  {{ $dept->created_at ? $dept->created_at->format('d/m/Y H:i') : '—' }}
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('departments.edit', $dept) }}" class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition-colors">
                      <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                      Modifier
                    </a>
                    <form method="POST" action="{{ route('departments.destroy', $dept) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-600 hover:text-white transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="border-t border-gray-100 px-6 py-4">
        {{ $departments->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
