@extends('admin.layout.app')

@section('title', 'Gestion des Types d\'Archives — ArchiDoc DGB')
@section('meta_description', 'Administration et gestion des types d\'archives réglementaires et de leur DUA — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 11h10M7 15h10"/>
          </svg>
        </span>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Gestion des Types d'Archives</h1>
      </div>
      <p class="mt-1 text-sm text-gray-500">Gérez le référentiel des types d'archives, leur description et leur Durée d'Utilisation Administrative (DUA).</p>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('archive-types.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau type d'archive
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
    <form method="GET" action="{{ route('archive-types.index') }}" class="p-4 sm:p-6">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        
        <!-- Recherche par nom ou description -->
        <div class="flex-1 max-w-md">
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Recherche par libellé ou description</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </span>
            <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Ex: ARRETE, NOTE, COURRIER..." class="w-full rounded-xl border border-gray-300 py-2 pl-9 pr-3 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
          </div>
        </div>

        <div class="flex items-end gap-2">
          <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            Filtrer
          </button>
          @if($search)
            <a href="{{ route('archive-types.index') }}" class="inline-flex items-center gap-1 rounded-xl border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
              Réinitialiser
            </a>
          @endif
        </div>

      </div>
    </form>
  </div>

  <!-- Tableau des Types d'Archives -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50/80 text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100">
          <tr>
            <th scope="col" class="px-6 py-4">Nom du Type</th>
            <th scope="col" class="px-6 py-4">Description</th>
            <th scope="col" class="px-6 py-4 text-center">DUA (Années)</th>
            <th scope="col" class="px-6 py-4 text-center">Archives Liées</th>
            <th scope="col" class="px-6 py-4">Créé par</th>
            <th scope="col" class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 font-medium">
          @forelse($archiveTypes as $type)
            <tr class="hover:bg-brand-50/30 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">
                <div class="flex items-center gap-2.5">
                  <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-100 text-brand-800 font-extrabold text-xs">
                    {{ strtoupper(substr($type->name, 0, 2)) }}
                  </span>
                  <span class="font-extrabold text-brand-900">{{ $type->name }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                {{ $type->description ?? '—' }}
              </td>
              <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-700/10">
                  <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ $type->dua ?? 99 }} ans
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-700">
                  {{ $type->archives_count }} {{ Str::plural('archive', $type->archives_count) }}
                </span>
              </td>
              <td class="px-6 py-4 text-xs text-gray-500">
                {{ $type->creator->name ?? 'Système DGB' }}
                <div class="text-[10px] text-gray-400">{{ $type->created_at ? $type->created_at->format('d/m/Y') : '' }}</div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('archive-types.edit', $type) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-brand-700" title="Modifier le type">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                  </a>
                  <form method="POST" action="{{ route('archive-types.destroy', $type) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type d\'archive ?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600" title="Supprimer le type">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                  </svg>
                </div>
                <p class="mt-3 text-sm font-semibold">Aucun type d'archive trouvé.</p>
                <p class="mt-1 text-xs text-gray-400">Essayez de modifier votre filtre de recherche ou créez un nouveau type.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($archiveTypes->hasPages())
      <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
        {{ $archiveTypes->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
