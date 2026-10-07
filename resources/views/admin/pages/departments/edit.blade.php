@extends('admin.layout.app')

@section('title', 'Modifier le Département — ArchiDoc DGB')
@section('meta_description', 'Formulaire de modification d\'un département ou groupe d\'accès existant — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <span class="text-gray-500">Création</span>
    <span>/</span>
    <a href="{{ route('departments.index') }}" class="hover:text-brand-700">Groupes d'accès / Départements</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Modification</span>
  </nav>

  <!-- En-tête -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Modifier le Département</h1>
      <p class="mt-1 text-sm text-gray-500">Modifiez l'intitulé ou la description du département « {{ $department->name }} ».</p>
    </div>
    <a href="{{ route('departments.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Retour
    </a>
  </div>

  <!-- Formulaire -->
  <form method="POST" action="{{ route('departments.update', $department) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-xs space-y-4">
      
      <!-- Intitulé / Sigle du département -->
      <div>
        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Intitulé ou Sigle du département <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-bold text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
        @error('name')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Structure d'appartenance / Direction Parente -->
      <div>
        <label for="parent_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Direction Principale de rattachement (Optionnel)
        </label>
        <select name="parent_id" id="parent_id" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('parent_id') border-red-500 @enderror">
          <option value="">-- Aucune (Il s'agit d'une Direction Principale) --</option>
          @foreach($parentDepartments as $pDept)
            <option value="{{ $pDept->id }}" @selected(old('parent_id', $department->parent_id) == $pDept->id)>
              {{ $pDept->name }} — {{ $pDept->description }}
            </option>
          @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">Laissez vide si ce département est une Direction Générale/Principale (ex: DGB, DGI, DGD...). Choisissez une Direction si c'est un sous-département ou service rattaché.</p>
        @error('parent_id')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Description ou Libellé complet du service
        </label>
        <textarea name="description" id="description" rows="4" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('description') border-red-500 @enderror">{{ old('description', $department->description) }}</textarea>
        @error('description')
          <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
        @enderror
      </div>

    </div>

    <!-- Actions de validation -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 flex items-center justify-end gap-3">
      <a href="{{ route('departments.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
        Annuler
      </a>
      <button type="submit" class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-brand-800 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-700">
        Enregistrer les modifications
      </button>
    </div>

  </form>

</div>
@endsection
