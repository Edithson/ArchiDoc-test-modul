@extends('admin.layout.app')

@section('title', 'Nouveau type d\'emplacement — ArchiDoc DGB')
@section('meta_description', 'Formulaire de création d\'un nouvel emplacement d\'archivage physique ou virtuel — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('archive-locations.index') }}" class="hover:text-brand-700">Emplacements</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Nouvel emplacement</span>
  </nav>

  <!-- En-tête -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Créer un Emplacement</h1>
      <p class="mt-1 text-sm text-gray-500">Définissez un nouvel endroit physique (magasin) ou virtuel (serveur) pour la conservation des archives.</p>
    </div>
    <a href="{{ route('archive-locations.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Retour
    </a>
  </div>

  <!-- Formulaire avec Alpine.js pour la réactivité visuelle -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm" x-data="{ selectedType: '{{ old('type', '1') }}' }">
    <form method="POST" action="{{ route('archive-locations.store') }}" class="p-6 space-y-6">
      @csrf

      <!-- Type d'emplacement (Physique vs Virtuel) -->
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
          Type d'Emplacement <span class="text-red-500">*</span>
        </label>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          
          <!-- Carte 1: Physique -->
          <label @click="selectedType = '1'"
            :class="selectedType === '1' ? 'border-2 border-amber-600 bg-amber-50/60 shadow-md ring-2 ring-amber-500/20' : 'border border-gray-200 bg-white hover:border-gray-300'"
            class="relative flex cursor-pointer rounded-2xl p-4 transition-all duration-150 ease-in-out">
            <input type="radio" name="type" value="1" x-model="selectedType" class="sr-only">
            
            <div class="flex items-start gap-3.5 w-full">
              <span :class="selectedType === '1' ? 'bg-amber-600 text-white shadow-sm' : 'bg-amber-100 text-amber-800'" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl font-bold transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </span>
              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <p class="text-sm font-extrabold text-gray-900">Emplacement Physique</p>
                  <span x-show="selectedType === '1'" class="inline-flex items-center gap-1 rounded-full bg-amber-600 px-2 py-0.5 text-[10px] font-extrabold uppercase text-white shadow-2xs">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Sélectionné
                  </span>
                </div>
                <p class="mt-1 text-xs font-medium text-gray-600">Magasin d'archivage, salle de dépôt, bâtiment, rayonnages physiques.</p>
              </div>
            </div>
          </label>

          <!-- Carte 2: Virtuel -->
          <label @click="selectedType = '2'"
            :class="selectedType === '2' ? 'border-2 border-purple-600 bg-purple-50/60 shadow-md ring-2 ring-purple-500/20' : 'border border-gray-200 bg-white hover:border-gray-300'"
            class="relative flex cursor-pointer rounded-2xl p-4 transition-all duration-150 ease-in-out">
            <input type="radio" name="type" value="2" x-model="selectedType" class="sr-only">
            
            <div class="flex items-start gap-3.5 w-full">
              <span :class="selectedType === '2' ? 'bg-purple-600 text-white shadow-sm' : 'bg-purple-100 text-purple-800'" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl font-bold transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
              </span>
              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <p class="text-sm font-extrabold text-gray-900">Emplacement Virtuel</p>
                  <span x-show="selectedType === '2'" class="inline-flex items-center gap-1 rounded-full bg-purple-600 px-2 py-0.5 text-[10px] font-extrabold uppercase text-white shadow-2xs">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Sélectionné
                  </span>
                </div>
                <p class="mt-1 text-xs font-medium text-gray-600">Serveur de stockage en ligne, NAS, Cloud d'archivage numérique.</p>
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
        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ex: FOUDA, DGB, SERVEUR-NAS-01..." required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm uppercase font-bold focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
        @error('name')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Localisation Géographique / Serveur IP -->
      <div>
        <label for="location" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          <span x-text="selectedType === '2' ? 'Adresse IP ou Chemin Réseau Serveur' : 'Adresse physique ou Bâtiment'"></span>
        </label>
        <input type="text" name="location" id="location" value="{{ old('location') }}" :placeholder="selectedType === '2' ? 'Ex: 192.168.1.100 / NAS-ARCHIDOC' : 'Ex: Quartier Messa, Yaoundé'" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('location') border-red-500 @enderror">
        @error('location')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Description & Remarques
        </label>
        <textarea name="description" id="description" rows="3" placeholder="Description de la capacité, du site ou des conditions de conservation..." class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
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
          Enregistrer l'emplacement
        </button>
      </div>

    </form>
  </div>

</div>
@endsection
