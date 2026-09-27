@extends('admin.layout.app')

@section('title', 'Nouveau type d\'archive — ArchiDoc DGB')
@section('meta_description', 'Formulaire de création d\'un nouveau type d\'archive réglementaire — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('archive-types.index') }}" class="hover:text-brand-700">Types d'archives</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Nouveau type</span>
  </nav>

  <!-- En-tête -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Créer un Type d'Archive</h1>
      <p class="mt-1 text-sm text-gray-500">Ajoutez un nouveau type d'archive au référentiel système de la DGB.</p>
    </div>
    <a href="{{ route('archive-types.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Retour
    </a>
  </div>

  <!-- Formulaire de création -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <form method="POST" action="{{ route('archive-types.store') }}" class="p-6 space-y-6">
      @csrf

      <!-- Nom du type -->
      <div>
        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Nom du type d'archive <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ex: ARRETE, NOTE DE SERVICE, CONVENTION..." required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm uppercase font-bold focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
        @error('name')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Description ou Détails
        </label>
        <textarea name="description" id="description" rows="3" placeholder="Précisez la nature ou le champ d'application de ce type de document..." class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
        @error('description')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- DUA (Durée d'utilisation administrative) -->
      <div>
        <label for="dua" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Durée d'Utilisation Administrative (DUA en années)
        </label>
        <div class="relative max-w-xs">
          <input type="number" name="dua" id="dua" value="{{ old('dua', 99) }}" min="1" max="999" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('dua') border-red-500 @enderror">
          <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-gray-400">ans</span>
        </div>
        <p class="mt-1 text-xs text-gray-500">Par défaut : 99 ans (conservation permanente / longue durée).</p>
        @error('dua')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Actions de validation -->
      <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
        <a href="{{ route('archive-types.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
          Annuler
        </a>
        <button type="submit" class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
          Enregistrer le type
        </button>
      </div>

    </form>
  </div>

</div>
@endsection
