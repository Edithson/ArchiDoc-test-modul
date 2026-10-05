@extends('admin.layout.app')

@section('title', 'Nouveau Rôle & Habilitations — ArchiDoc DGB')
@section('meta_description', 'Formulaire de création d\'un nouveau rôle et configuration des habilitations fonctionnelles JSON — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('roles.index') }}" class="hover:text-brand-700">Habilitations & Rôles</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Nouveau rôle</span>
  </nav>

  <!-- En-tête -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Créer un Nouveau Rôle</h1>
      <p class="mt-1 text-xs text-gray-500">Définissez le nom du rôle et cochez les autorisations pour chacun des 9 modèles de gestion de l'application.</p>
    </div>
    <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Retour
    </a>
  </div>

  <form method="POST" action="{{ route('roles.store') }}" class="space-y-6">
    @csrf

    <!-- SECTION 1: Informations du Rôle -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
      <h2 class="text-sm font-extrabold uppercase tracking-wider text-gray-900 mb-4 border-b border-gray-100 pb-2">1. Informations Générales</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Nom du Rôle -->
        <div>
          <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Nom du Rôle <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ex: Chef de Service, Archiviste Régional..." required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm font-bold text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
          @error('name')
            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Description du Rôle
          </label>
          <input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Ex: Accès en consultation et versement des pièces d'intégration..." class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
        </div>
      </div>
    </div>

    <!-- SECTION 2: Matrice des Habilitations JSON (9 Modèles) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
      <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-6">
        <div>
          <h2 class="text-sm font-extrabold uppercase tracking-wider text-gray-900">2. Matrice d'Habilitations par Modèle (JSON)</h2>
          <p class="text-xs text-gray-500 mt-0.5">Cochez les actions autorisées pour ce rôle sur chaque entité fonctionnelle du système.</p>
        </div>

        <div class="flex items-center gap-2">
          <button type="button" onclick="toggleAllPermissions(true)" class="rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-800 hover:bg-emerald-100">
            Tout Cocher
          </button>
          <button type="button" onclick="toggleAllPermissions(false)" class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-1 text-xs font-bold text-gray-700 hover:bg-gray-100">
            Tout Décocher
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($modelDefinitions as $modelKey => $def)
          <div class="rounded-xl border border-gray-200 bg-gray-50/40 p-4 shadow-2xs">
            <div class="flex items-center justify-between border-b border-gray-200/80 pb-2.5 mb-3">
              <div class="flex items-center gap-2">
                <span class="flex h-6 w-6 items-center justify-center rounded-md bg-brand-700 text-white font-bold text-xs">
                  {{ strtoupper(substr($modelKey, 0, 1)) }}
                </span>
                <span class="text-xs font-extrabold text-gray-900">{{ $def['label'] }}</span>
              </div>
              <button type="button" onclick="toggleModelPermissions('{{ $modelKey }}')" class="text-[10px] font-bold text-brand-700 hover:underline">
                Basculer
              </button>
            </div>

            <div class="space-y-2">
              @foreach($def['actions'] as $actionKey => $actionLabel)
                @php
                  $inputName = "permissions[{$modelKey}][{$actionKey}]";
                  $isChecked = old("permissions.{$modelKey}.{$actionKey}", false);
                @endphp
                <label class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-2 text-xs font-medium text-gray-700 hover:bg-brand-50/40 transition cursor-pointer">
                  <span>{{ $actionLabel }}</span>
                  <input type="checkbox" name="{{ $inputName }}" value="1" data-model="{{ $modelKey }}" {{ $isChecked ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-700 focus:ring-brand-600">
                </label>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Actions de Validation -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 flex items-center justify-end gap-3 shadow-xs">
      <a href="{{ route('roles.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        Annuler
      </a>
      <button type="submit" class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        Enregistrer le rôle et ses habilitations
      </button>
    </div>

  </form>

</div>

<script>
function toggleAllPermissions(check) {
  document.querySelectorAll('input[data-model]').forEach(cb => cb.checked = check);
}

function toggleModelPermissions(modelKey) {
  const checkboxes = document.querySelectorAll(`input[data-model="${modelKey}"]`);
  const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
  checkboxes.forEach(cb => cb.checked = anyUnchecked);
}
</script>
@endsection
