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
    <a href="{{ route('archive-locations.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Retour
    </a>
  </div>

  <!-- Formulaire de modification -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm" x-data="{ selectedType: '{{ (string) old('type', $archiveLocation->type) }}' }">
    <form method="POST" action="{{ route('archive-locations.update', $archiveLocation) }}" class="p-6 space-y-6">
      @csrf
      @method('PUT')

      <!-- 1. Type d'emplacement (Physique vs Virtuel) -->
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
          Type d'Emplacement <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          <!-- Option 1: Physique -->
          <label @click="selectedType = '1'" class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors" :class="selectedType == '1' ? 'border-brand-600 bg-brand-50/50 ring-1 ring-brand-600' : 'border-gray-200 bg-white hover:border-gray-300'">
            <input type="radio" name="type" value="1" x-model="selectedType" {{ (int) old('type', $archiveLocation->type) === 1 ? 'checked' : '' }} class="mt-0.5 h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300">
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="block text-sm font-bold text-gray-900">Emplacement Physique</span>
                <span x-show="selectedType == '1'" class="inline-flex items-center gap-1 rounded-full bg-brand-700 px-2 py-0.5 text-[10px] font-bold text-white">
                  Actuel
                </span>
              </div>
              <span class="block text-xs text-gray-500 mt-0.5">Magasin d'archivage, salle de dépôt, bâtiment, rayonnage.</span>
            </div>
          </label>

          <!-- Option 2: Virtuel -->
          <label @click="selectedType = '2'" class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors" :class="selectedType == '2' ? 'border-brand-600 bg-brand-50/50 ring-1 ring-brand-600' : 'border-gray-200 bg-white hover:border-gray-300'">
            <input type="radio" name="type" value="2" x-model="selectedType" {{ (int) old('type', $archiveLocation->type) === 2 ? 'checked' : '' }} class="mt-0.5 h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300">
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="block text-sm font-bold text-gray-900">Emplacement Virtuel</span>
                <span x-show="selectedType == '2'" class="inline-flex items-center gap-1 rounded-full bg-brand-700 px-2 py-0.5 text-[10px] font-bold text-white">
                  Actuel
                </span>
              </div>
              <span class="block text-xs text-gray-500 mt-0.5">Serveur de stockage, NAS, cloud d'archivage numérique.</span>
            </div>
          </label>

        </div>
        @error('type')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- 2. Nom / Sigle -->
      <div>
        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Nom ou Sigle de l'emplacement <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" id="name" value="{{ old('name', $archiveLocation->name) }}"
          placeholder="Ex: MAGASIN-FOUDA, SERVEUR-NAS-01..."
          :placeholder="selectedType == '2' ? 'Ex: SERVEUR-NAS-01, CLOUD-DGB...' : 'Ex: MAGASIN-FOUDA, SALLE-02...'"
          required
          class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm uppercase font-bold focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
        <p class="mt-1 text-xs text-gray-500">Sigle ou libellé d'identification de l'emplacement.</p>
        @error('name')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- 3. Localisation Géographique / Adresse IP -->
      <div>
        <label for="location" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          <span x-text="selectedType == '2' ? 'Adresse IP ou Chemin Réseau Serveur' : 'Adresse physique ou Bâtiment'">Adresse physique ou Adresse IP</span>
        </label>
        <input type="text" name="location" id="location" value="{{ old('location', $archiveLocation->location) }}"
          placeholder="Ex: Quartier Messa, Immeuble DGB, Yaoundé ou 192.168.1.100"
          :placeholder="selectedType == '2' ? 'Ex: 192.168.1.100 ou \\NAS-ARCHIDOC\archives' : 'Ex: Quartier Messa, Immeuble DGB, Yaoundé'"
          class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('location') border-red-500 @enderror">
        <p class="mt-1 text-xs text-gray-500">Précisez l'adresse géographique ou l'identifiant réseau (IP/Chemin NAS).</p>
        @error('location')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- 4. Description & Remarques -->
      <div>
        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Description & Remarques
        </label>
        <textarea name="description" id="description" rows="3"
          placeholder="Description de la capacité, du site ou des conditions de conservation..."
          class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('description') border-red-500 @enderror">{{ old('description', $archiveLocation->description) }}</textarea>
        <p class="mt-1 text-xs text-gray-500">Remarques optionnelles concernant la capacité ou les conditions de stockage.</p>
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
          Mettre à jour l'emplacement
        </button>
      </div>

    </form>
  </div>

</div>
@endsection