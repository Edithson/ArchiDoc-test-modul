@extends('admin.layout.app')

@section('title', "Configurer {$role->name} — ArchiDoc DGB")
@section('meta_description', "Modification des habilitations fonctionnelles du rôle {$role->name} — ArchiDoc DGB")

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('roles.index') }}" class="hover:text-brand-700">Habilitations & Rôles</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">{{ $role->name }}</span>
  </nav>

  <!-- En-tête -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Configurer Habilitations : {{ $role->name }}</h1>
        @if(in_array(strtolower(trim($role->name)), ['super privilégé', 'privilégié', 'classic', 'classique']))
          <span class="rounded-lg bg-purple-100 px-2.5 py-1 text-xs font-extrabold text-purple-800 border border-purple-200">Rôle Système</span>
        @endif
      </div>
      <p class="mt-1 text-xs text-gray-500">Ajustez les autorisations d'accès par modèle (lecture, écriture, téléchargement, suppression).</p>
    </div>
    <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Retour
    </a>
  </div>

  @if(str_contains(strtolower($role->name), 'super'))
    <div class="mb-6 rounded-2xl border border-purple-200 bg-purple-50 p-4 text-xs text-purple-900 flex items-start gap-3 shadow-xs">
      <svg class="h-5 w-5 text-purple-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <div>
        <strong class="font-extrabold">Notice Rôle Super Privilégié :</strong>
        <p class="mt-0.5 leading-relaxed">Le rôle Super Privilégié bénéficie d'un privilège d'accès absolu sur l'intégralité du système. Toutes les permissions ci-dessous lui sont implicitement accordées à 100%.</p>
      </div>
    </div>
  @endif

  <form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- SECTION 1: Informations du Rôle -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
      <h2 class="text-sm font-extrabold uppercase tracking-wider text-gray-900 mb-4 border-b border-gray-100 pb-2">1. Informations Générales</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @php
          $isProtected = in_array(strtolower(trim($role->name)), ['super privilégé', 'super privilégie', 'super-privilégié', 'privilégié', 'privilegie', 'classic', 'classique']);
        @endphp
        <!-- Nom du Rôle -->
        <div>
          <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Nom du Rôle <span class="text-red-500">*</span>
          </label>
          @if($isProtected)
            <input type="text" name="name" id="name" value="{{ $role->name }}" readonly class="w-full rounded-xl border border-gray-200 bg-gray-100 px-3.5 py-2.5 text-sm font-extrabold text-gray-700 cursor-not-allowed">
            <p class="mt-1 text-[11px] font-semibold text-purple-700">Le nom d'un rôle système primaire ne peut pas être modifié.</p>
          @else
            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm font-bold text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
            @error('name')
              <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
            @enderror
          @endif
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Description du Rôle
          </label>
          <input type="text" name="description" id="description" value="{{ old('description', $role->description) }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
        </div>
      </div>
    </div>

    <!-- SECTION 2: Matrice des Habilitations JSON (9 Modèles) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
      <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-6">
        <div>
          <h2 class="text-sm font-extrabold uppercase tracking-wider text-gray-900">2. Matrice d'Habilitations par Modèle (JSON)</h2>
          <p class="text-xs text-gray-500 mt-0.5">Cochez ou décochez les autorisations d'accès spécifiques pour chaque module.</p>
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
                  $defaultVal = !empty($role->permissions[$modelKey][$actionKey]);
                  if (str_contains(strtolower($role->name), 'super')) {
                      $defaultVal = true;
                  }
                  $isChecked = old("permissions.{$modelKey}.{$actionKey}", $defaultVal);
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
        Enregistrer les modifications
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
