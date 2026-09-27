@extends('admin.layout.app')

@section('title', 'Modifier emplacement — ArchiDoc DGB')
@section('meta_description', 'Formulaire de modification d\'un emplacement d\'archivage — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('archive-locations.index') }}" class="hover:text-brand-700">Emplacements</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Modifier #{{ $archiveLocation->id }}</span>
  </nav>

  <!-- En-tête -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Modifier l'Emplacement</h1>
      <p class="mt-1 text-sm text-gray-500">Mettez à jour les caractéristiques de l'emplacement « {{ $archiveLocation->name }} ».</p>
    </div>
    <a href="{{ route('archive-locations.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Retour
    </a>
  </div>

  <!-- Formulaire d'édition -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <form method="POST" action="{{ route('archive-locations.update', $archiveLocation) }}" class="p-6 space-y-6">
      @csrf
      @method('PUT')

      <!-- Type d'emplacement (Physique vs Virtuel) -->
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
          Type d'Emplacement <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          
          <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-2xs focus:outline-none hover:border-brand-500 has-checked:border-brand-600 has-checked:bg-brand-50/40">
            <input type="radio" name="type" value="1" {{ old('type', $archiveLocation->type) == '1' ? 'checked' : '' }} class="sr-only">
            <div class="flex items-start gap-3">
              <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800 font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </span>
              <div>
                <p class="text-sm font-bold text-gray-900">Emplacement Physique</p>
                <p class="mt-0.5 text-xs text-gray-500">Magasin d'archivage, salle de dépôt, bâtiment.</p>
              </div>
            </div>
          </label>

          <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-2xs focus:outline-none hover:border-brand-500 has-checked:border-brand-600 has-checked:bg-brand-50/40">
            <input type="radio" name="type" value="2" {{ old('type', $archiveLocation->type) == '2' ? 'checked' : '' }} class="sr-only">
            <div class="flex items-start gap-3">
              <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-100 text-purple-800 font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
              </span>
              <div>
                <p class="text-sm font-bold text-gray-900">Emplacement Virtuel</p>
                <p class="mt-0.5 text-xs text-gray-500">Serveur de stockage, NAS, Cloud d'archivage.</p>
              </div>
            </div>
          </label>

        </div>
        @error('type')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Nom / Sigle -->
      <div>
        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Nom ou Sigle de l'emplacement <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" id="name" value="{{ old('name', $archiveLocation->name) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm uppercase font-bold focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
        @error('name')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Localisation / IP -->
      <div>
        <label for="location" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Adresse physique ou Adresse IP / Chemin serveur
        </label>
        <input type="text" name="location" id="location" value="{{ old('location', $archiveLocation->location) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('location') border-red-500 @enderror">
        @error('location')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Description & Remarques
        </label>
        <textarea name="description" id="description" rows="3" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('description') border-red-500 @enderror">{{ old('description', $archiveLocation->description) }}</textarea>
        @error('description')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Actions -->
      <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
        <a href="{{ route('archive-locations.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
          Annuler
        </a>
        <button type="submit" class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
          Mettre à jour
        </button>
      </div>

    </form>
  </div>

</div>
@endsection
