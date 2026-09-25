@extends('admin.layout.app')

@section('title', 'Recherche d\'archives — ArchiDoc DGB')
@section('meta_description', 'Recherche multicritère, consultation et filtrage des archives numériques — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </span>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Recherche & Consultation d'archives</h1>
      </div>
      <p class="mt-1 text-sm text-gray-500">Formulaire multicritère de recherche, tri dynamique et consultation des documents archivés.</p>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('archives.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle archive
      </a>
    </div>
  </div>

  <!-- Carte du Formulaire Multicritère -->
  <div class="mb-8 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
      <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
        <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        Filtres de recherche multicritères
      </h2>
    </div>

    <form method="GET" action="{{ route('archives.search') }}" class="px-6 py-6 sm:px-8">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        <!-- 1. Type d'archive -->
        <div>
          <label for="typearchive" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-700">
            Type d'archive
          </label>
          <div class="relative">
            <select id="typearchive" name="typearchive"
              class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <option value="">Tous les types</option>
              @foreach($archiveTypes as $type)
                <option value="{{ $type }}" {{ ($filters['typearchive'] ?? '') === $type ? 'selected' : '' }}>
                  {{ $type }}
                </option>
              @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>

        <!-- 2. Date du document -->
        <div>
          <label for="date_doc" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-700">
            Date du document
          </label>
          <input type="date" id="date_doc" name="date_doc" value="{{ $filters['date_doc'] ?? '' }}"
            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
        </div>

        <!-- 3. Objet de l'archive -->
        <div>
          <label for="description" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-700">
            Objet / Description
          </label>
          <input type="text" id="description" name="description" value="{{ $filters['description'] ?? '' }}" placeholder="Ex. Nomination, budget..."
            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
        </div>

        <!-- 4. Personne l'ayant chargé (Auteur) -->
        <div>
          <label for="user_id" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-700">
            Chargé par
          </label>
          <div class="relative">
            <select id="user_id" name="user_id"
              class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <option value="">Tous les utilisateurs</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (string)($filters['user_id'] ?? '') === (string)$user->id ? 'selected' : '' }}>
                  {{ $user->name }}
                </option>
              @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>

      </div>

      <!-- Preservations du Tri lors de la soumission -->
      <input type="hidden" name="sort_by" value="{{ $filters['sort_by'] ?? 'created_at' }}">
      <input type="hidden" name="sort_order" value="{{ $filters['sort_order'] ?? 'desc' }}">

      <!-- Boutons d'action -->
      <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end border-t border-gray-100 pt-4">
        <a href="{{ route('archives.search') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-600">
          Réinitialiser
        </a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-700 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Lancer la recherche
        </button>
      </div>
    </form>
  </div>

  <!-- Barre d'options de Tri & Résultats -->
  <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="text-sm text-gray-600">
      Affichage de <span class="font-bold text-gray-900">{{ $archives->firstItem() ?? 0 }}</span> à <span class="font-bold text-gray-900">{{ $archives->lastItem() ?? 0 }}</span> sur <span class="font-bold text-gray-900">{{ $archives->total() }}</span> archives trouvées
    </div>

    <!-- Options de tri croissant / décroissant -->
    <div class="flex items-center gap-3">
      <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Trier par :</span>
      <form method="GET" action="{{ route('archives.search') }}" class="flex items-center gap-2" id="sort-form">
        @foreach($filters as $k => $v)
          @if(!in_array($k, ['sort_by', 'sort_order']) && !empty($v))
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
          @endif
        @endforeach

        <div class="relative">
          <select name="sort_by" onchange="document.getElementById('sort-form').submit()"
            class="block appearance-none rounded-lg border border-gray-300 bg-white py-1.5 pl-3 pr-8 text-xs font-semibold text-gray-800 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
            <option value="created_at" {{ ($filters['sort_by'] ?? 'created_at') === 'created_at' ? 'selected' : '' }}>Date d'enregistrement</option>
            <option value="date_doc" {{ ($filters['sort_by'] ?? '') === 'date_doc' ? 'selected' : '' }}>Date du document</option>
            <option value="typearchive" {{ ($filters['sort_by'] ?? '') === 'typearchive' ? 'selected' : '' }}>Type d'archive</option>
            <option value="description" {{ ($filters['sort_by'] ?? '') === 'description' ? 'selected' : '' }}>Objet</option>
          </select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
        </div>

        <div class="relative">
          <select name="sort_order" onchange="document.getElementById('sort-form').submit()"
            class="block appearance-none rounded-lg border border-gray-300 bg-white py-1.5 pl-3 pr-8 text-xs font-semibold text-gray-800 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
            <option value="desc" {{ ($filters['sort_order'] ?? 'desc') === 'desc' ? 'selected' : '' }}>Descendant (↓)</option>
            <option value="asc" {{ ($filters['sort_order'] ?? '') === 'asc' ? 'selected' : '' }}>Croissant (↑)</option>
          </select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-gray-400"><path d="m6 9 6 6 6-6"/></svg>
        </div>
      </form>
    </div>
  </div>

  <!-- Tableau des Résultats -->
  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
        <thead class="bg-gray-50/80 text-xs font-bold uppercase tracking-wider text-gray-500">
          <tr>
            <th scope="col" class="px-6 py-3.5">Type & Format</th>
            <th scope="col" class="px-6 py-3.5">Objet de l'archive</th>
            <th scope="col" class="px-6 py-3.5">Date Doc.</th>
            <th scope="col" class="px-6 py-3.5">Emplacement</th>
            <th scope="col" class="px-6 py-3.5">Groupe</th>
            <th scope="col" class="px-6 py-3.5 text-right">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
          @forelse($archives as $archive)
            <tr class="hover:bg-brand-50/20 transition-colors">
              <!-- Type & Format -->
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-col gap-1">
                  <span class="inline-flex w-fit items-center rounded-md bg-brand-50 px-2 py-0.5 text-xs font-bold text-brand-700">
                    {{ $archive->typearchive ?? 'NON SPÉCIFIÉ' }}
                  </span>
                  <span class="text-xs text-gray-500 font-medium flex items-center gap-1">
                    @if(str_contains(strtolower($archive->format ?? ''), 'pdf'))
                      <svg class="h-3.5 w-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/></svg>
                    @elseif(str_contains(strtolower($archive->format ?? ''), 'image'))
                      <svg class="h-3.5 w-3.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                    @else
                      <svg class="h-3.5 w-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                    @endif
                    {{ $archive->format ?? 'Standard' }}
                  </span>
                </div>
              </td>

              <!-- Objet / Description -->
              <td class="px-6 py-4 max-w-xs sm:max-w-md">
                <p class="font-semibold text-gray-900 line-clamp-2" title="{{ $archive->description }}">
                  {{ $archive->description }}
                </p>
                <div class="mt-1 flex items-center gap-2 text-xs text-gray-400">
                  <span>Cote: {{ $archive->cote ?? 'N/A' }}</span>
                  <span>•</span>
                  <span>Créé le {{ \Carbon\Carbon::parse($archive->created_at)->format('d/m/Y') }}</span>
                </div>
              </td>

              <!-- Date Doc -->
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                {{ $archive->date_doc ? \Carbon\Carbon::parse($archive->date_doc)->format('d/m/Y') : 'N/A' }}
              </td>

              <!-- Emplacement -->
              <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                <div class="font-medium text-gray-800">{{ $archive->emplacement ?? 'N/A' }}</div>
                @if($archive->rayon || $archive->travee)
                  <div class="text-gray-400">Rayon: {{ $archive->rayon ?? '-' }} / Travée: {{ $archive->travee ?? '-' }}</div>
                @endif
              </td>

              <!-- Groupe d'accès -->
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center rounded border border-gray-200 bg-gray-50 px-2 py-0.5 text-xs font-semibold text-gray-700">
                  {{ $archive->departement ?? 'GLOBAL' }}
                </span>
              </td>

              <!-- Action Consulter -->
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="{{ route('archives.show', $archive->id) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-brand-200 bg-brand-50 px-3 py-1.5 text-xs font-bold text-brand-700 hover:bg-brand-100 hover:text-brand-800 transition-colors">
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  Consulter
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                  <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                  </div>
                  <h3 class="text-sm font-bold text-gray-800">Aucune archive trouvée</h3>
                  <p class="mt-1 text-xs text-gray-500">Essayez de modifier vos critères de recherche ou réinitialisez les filtres.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($archives->hasPages())
      <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
        {{ $archives->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
