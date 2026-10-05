@extends('admin.layout.app')

@section('title', 'Habilitations & Permissions — ' . $user->name)
@section('meta_description', 'Gestion des habilitations et surcharges de permissions individuelles de l\'utilisateur')

@section('content')
<div class="mx-auto max-w-[1200px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête -->
  <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-700 mb-1">
        <a href="{{ route('users.index') }}" class="hover:underline">Comptes utilisateurs</a>
        <span>/</span>
        <span>Habilitations & Permissions</span>
      </div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Droits d'Accès Personnalisés</h1>
      <p class="mt-1 text-sm text-gray-500">
        Gestion des habilitations pour <span class="font-bold text-gray-900">{{ $user->name }}</span> (Rôle principal: <span class="font-bold text-brand-700">{{ $user->role?->name ?? 'Aucun' }}</span>).
      </p>
    </div>

    <div>
      <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour à la liste
      </a>
    </div>
  </div>

  <!-- Flash Messages -->
  @if(session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 flex items-center gap-2" role="status">
      <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <!-- Onglets de Navigation (Séparation Informations vs Habilitations) -->
  <div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex gap-6" aria-label="Onglets Édition Utilisateur">
      <a href="{{ route('users.edit', $user->id) }}"
        class="group inline-flex items-center gap-2 border-b-2 border-transparent py-3 px-1 text-sm font-semibold text-gray-500 hover:border-gray-300 hover:text-gray-700 transition">
        <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Informations du Compte
      </a>

      <a href="{{ route('users.permissions', $user->id) }}"
        class="group inline-flex items-center gap-2 border-b-2 border-brand-700 py-3 px-1 text-sm font-bold text-brand-700" aria-current="page">
        <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        Habilitations & Permissions Personnalisées
      </a>
    </nav>
  </div>

  <!-- Formulaire caché pour la révocation en 1 clic -->
  <form id="revoke-custom-permissions-form" method="POST" action="{{ route('users.revoke-custom-permissions', $user->id) }}" class="hidden" onsubmit="return confirm('Êtes-vous sûr de vouloir révoquer l\'ensemble des autorisations personnalisées de cet utilisateur ? Il réhéritera strictement des droits de son rôle ({{ $user->role?->name }}).');">
    @csrf
  </form>

  <!-- Carte des Permissions Matrix -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 sm:px-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
          Matrice des Surcharges Individuelles vs Rôle « {{ $user->role?->name }} »
        </h2>
        <p class="mt-1 text-xs text-gray-500">
          Chaque action est héritée par défaut du rôle de l'utilisateur. Seules les modifications explicites (Forcer Autorisé / Forcer Interdit) sont stockées.
        </p>
      </div>

      <div>
        @if($user->hasCustomPermissionOverrides())
          <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 border border-amber-200">
              <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
              {{ $user->customPermissionsCount() }} surcharge(s) active(s)
            </span>
            <button type="submit" form="revoke-custom-permissions-form" class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-3.5 py-2 text-xs font-bold text-red-700 shadow-sm hover:bg-red-100 transition">
              <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              Révoquer en 1 clic
            </button>
          </div>
        @else
          <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            100% Héritage du rôle « {{ $user->role?->name }} »
          </span>
        @endif
      </div>
    </div>

    <form method="POST" action="{{ route('users.permissions.update', $user->id) }}" class="p-6 sm:p-8 space-y-6">
      @csrf
      @method('PUT')

      @if(isset($modelDefinitions) && is_array($modelDefinitions))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach($modelDefinitions as $modelKey => $def)
            <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white transition">
              <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-200">
                <span class="p-1.5 rounded-lg bg-brand-50 text-brand-700 font-bold text-xs">
                  {{ $def['label'] }}
                </span>
              </div>

              <div class="space-y-2.5">
                @foreach($def['actions'] as $actionKey => $actionLabel)
                  @php
                    $roleDefault = (bool) ($user->role?->permissions[$modelKey][$actionKey] ?? false);
                    $hasOverride = isset($user->custom_permissions[$modelKey][$actionKey]);
                    $overrideValue = $hasOverride ? (bool) $user->custom_permissions[$modelKey][$actionKey] : null;

                    $selectedOpt = 'inherit';
                    if ($overrideValue === true) {
                        $selectedOpt = '1';
                    } elseif ($overrideValue === false) {
                        $selectedOpt = '0';
                    }
                  @endphp

                  <div class="flex flex-col gap-1 text-xs p-2 rounded-lg {{ $hasOverride ? 'bg-amber-50/80 border border-amber-200' : 'bg-white border border-gray-200' }}">
                    <div class="flex items-center justify-between font-medium text-gray-700">
                      <span>{{ $actionLabel }}</span>
                      @if($hasOverride)
                        <span class="text-[10px] uppercase font-bold tracking-wider text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">Surchargé</span>
                      @endif
                    </div>

                    <select name="custom_permissions[{{ $modelKey }}][{{ $actionKey }}]" class="w-full text-xs font-semibold rounded-lg border border-gray-300 py-1 px-2 bg-white focus:ring-brand-500 focus:border-brand-500">
                      <option value="inherit" {{ $selectedOpt === 'inherit' ? 'selected' : '' }}>
                        Hériter ({{ $roleDefault ? 'Autorisé' : 'Interdit' }})
                      </option>
                      <option value="1" {{ $selectedOpt === '1' ? 'selected' : '' }} class="font-bold text-emerald-700">
                        Forcer Autorisé ✓
                      </option>
                      <option value="0" {{ $selectedOpt === '0' ? 'selected' : '' }} class="font-bold text-red-700">
                        Forcer Interdit ✗
                      </option>
                    </select>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
      @endif

      <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
        <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
          Annuler
        </a>
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Mettre à jour les habilitations
        </button>
      </div>

    </form>
  </div>

</div>
@endsection
