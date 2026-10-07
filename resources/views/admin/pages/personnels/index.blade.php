@extends('admin.layout.app')

@section('title', 'Dossiers du Personnel — ArchiDoc DGB')
@section('meta_description', 'Gestion et suivi des dossiers d\'intégration du personnel, pièces obligatoires et facultatives — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Dossiers du personnel</span>
  </nav>

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </span>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Dossiers du Personnel</h1>
      </div>
      <p class="mt-1 text-sm text-gray-500">Suivez la complétude des pièces d'intégration des agents, décomptes des pièces manquantes et taux d'achèvement.</p>
    </div>

    <div class="flex items-center gap-3">
      @if(auth()->user()?->hasPermission('Piece', 'read'))
      <a href="{{ route('pieces.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Référentiel des pièces
      </a>
      @endif
      @if(auth()->user()?->hasPermission('Personnel', 'create'))
      <a href="{{ route('personnels.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau dossier agent
      </a>
      @endif
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

  <!-- Statistiques des Dossiers -->
  <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <!-- Total Agents -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Personnel</p>
        <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $totalPersonnel }}</p>
      </div>
      <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-700">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
      </span>
    </div>

    <!-- Dossiers Complets -->
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-emerald-800">Dossiers Complets (100%)</p>
        <p class="mt-1 text-2xl font-extrabold text-emerald-900">{{ $completeCount }}</p>
      </div>
      <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </span>
    </div>

    <!-- Dossiers Incomplets -->
    <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5 shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-amber-800">Dossiers Incomplets</p>
        <p class="mt-1 text-2xl font-extrabold text-amber-900">{{ $incompleteCount }}</p>
      </div>
      <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </span>
    </div>
  </div>

  <!-- Carte de Filtres -->
  <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <form method="GET" action="{{ route('personnels.index') }}" class="p-4 sm:p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 items-end">
        
        <!-- Recherche par nom / matricule -->
        <div class="sm:col-span-2 lg:col-span-2">
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Recherche agent (Nom, Matricule, Email)</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </span>
            <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Ex: FOUDA, MAT-78901..." class="w-full rounded-xl border border-gray-300 py-2 pl-9 pr-3 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
          </div>
        </div>

        <!-- Direction Principale -->
        <div>
          <label for="department_id" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Direction Principale</label>
          <select name="department_id" id="department_id" onchange="handleFilterDeptChange(this)" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
            <option value="">Toutes les directions</option>
            @foreach($mainDepartments as $mDept)
              <option value="{{ $mDept->id }}" {{ (string) request('department_id') === (string) $mDept->id ? 'selected' : '' }}>
                {{ $mDept->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Sous-Département / Service -->
        <div>
          <label for="sub_department_id" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Sous-Département</label>
          <select name="sub_department_id" id="sub_department_id" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
            <option value="">Tous les services</option>
            @foreach($mainDepartments as $mDept)
              @if($mDept->children->isNotEmpty())
                @if(!request('department_id') || (string)request('department_id') === (string)$mDept->id)
                  <optgroup label="Services : {{ $mDept->name }}" data-parent-id="{{ $mDept->id }}">
                    @foreach($mDept->children as $sDept)
                      <option value="{{ $sDept->id }}" {{ (string) request('sub_department_id') === (string) $sDept->id ? 'selected' : '' }}>
                        {{ $sDept->name }}
                      </option>
                    @endforeach
                  </optgroup>
                @endif
              @endif
            @endforeach
          </select>
        </div>

        <script>
          function handleFilterDeptChange(selectEl) {
            const subDeptSelect = document.getElementById('sub_department_id');
            if (subDeptSelect) {
              subDeptSelect.value = '';
            }
            selectEl.form.submit();
          }
        </script>

        <!-- Filtre de statut de complétude -->
        <div>
          <label for="status" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Statut du dossier</label>
          <select name="status" id="status" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
            <option value="">Tous les dossiers</option>
            <option value="complete" {{ $statusFilter === 'complete' ? 'selected' : '' }}>Dossiers Complets (100%)</option>
            <option value="incomplete" {{ $statusFilter === 'incomplete' ? 'selected' : '' }}>Dossiers Incomplets (< 100%)</option>
          </select>
        </div>

      </div>

      <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
        <div class="text-xs text-gray-500">
          Affichage des résultats filtrés
        </div>
        <div class="flex items-center gap-2">
          @if($search || $statusFilter || request('department_id') || request('sub_department_id'))
            <a href="{{ route('personnels.index') }}" class="inline-flex items-center gap-1 rounded-xl border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
              Réinitialiser
            </a>
          @endif
          <button type="submit" class="inline-flex items-center gap-1 rounded-xl bg-gray-900 px-4 py-1.5 text-xs font-semibold text-white hover:bg-gray-800">
            Filtrer
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Tableau des Dossiers du Personnel -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50/80 text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100">
          <tr>
            <th scope="col" class="px-6 py-4">Agent / Nom complet</th>
            <th scope="col" class="px-6 py-4">Matricule</th>
            <th scope="col" class="px-6 py-4">Structure rattachée</th>
            <th scope="col" class="px-6 py-4">Contact</th>
            <th scope="col" class="px-6 py-4">Taux d'Achèvement</th>
            <th scope="col" class="px-6 py-4">Statut du Dossier</th>
            <th scope="col" class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 font-medium">
          @forelse($personnels as $agent)
            @php
              $taux = $agent->taux_achevement;
              $missing = $agent->missing_obligatory_pieces_count;
              $isComplete = $agent->is_complete;
            @endphp
            <tr class="hover:bg-brand-50/30 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">
                <div class="flex items-center gap-3">
                  <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-100 text-brand-800 font-extrabold text-sm shadow-2xs">
                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                  </span>
                  <div>
                    <a href="{{ route('personnels.show', $agent) }}" class="font-extrabold text-brand-900 hover:underline">
                      {{ $agent->name }}
                    </a>
                    <div class="text-xs font-normal text-gray-500">{{ $agent->address ?? 'Siège' }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 font-mono font-bold text-brand-800 text-xs">
                {{ $agent->matricule }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-col gap-1 items-start">
                  @if($agent->department)
                    <span class="inline-flex items-center rounded border border-purple-200 bg-purple-50 px-2 py-0.5 text-xs font-bold text-purple-700">
                      {{ $agent->department->name }}
                    </span>
                  @else
                    <span class="text-xs text-gray-400 italic">Non rattaché</span>
                  @endif

                  @if($agent->subDepartment)
                    <span class="inline-flex items-center gap-1 rounded border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[11px] font-semibold text-indigo-700">
                      <svg class="h-3 w-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                      {{ $agent->subDepartment->name }}
                    </span>
                  @elseif($agent->department)
                    <span class="text-[11px] font-normal text-gray-400">Tous les services</span>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 text-xs text-gray-600">
                <div>{{ $agent->email ?? '—' }}</div>
                <div class="text-gray-400 font-mono">{{ $agent->phone ?? '' }}</div>
              </td>
              <td class="px-6 py-4 w-48">
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-xs font-extrabold text-gray-900">{{ $taux }}%</span>
                  <span class="text-[10px] text-gray-400 font-medium">du dossier</span>
                </div>
                <!-- Barre de progression -->
                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                  <div class="h-full transition-all duration-300 rounded-full {{ $taux == 100 ? 'bg-emerald-500' : ($taux >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $taux }}%"></div>
                </div>
              </td>
              <td class="px-6 py-4">
                @if($isComplete)
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-800 ring-1 ring-emerald-700/10">
                    <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Dossier Complet
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-800 ring-1 ring-amber-700/10">
                    <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Incomplet ({{ $missing }} {{ Str::plural('pièce', $missing) }} manquant{{ $missing > 1 ? 'es' : '' }})
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  @if(auth()->user()?->hasPermission('Personnel', 'read'))
                  <a href="{{ route('personnels.show', $agent) }}" class="rounded-lg p-1.5 text-brand-700 hover:bg-brand-50" title="Consulter le dossier">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </a>
                  @endif
                  @if(auth()->user()?->hasPermission('Personnel', 'update'))
                  <a href="{{ route('personnels.edit', $agent) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-brand-700" title="Compléter / Modifier le dossier">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                  </a>
                  @endif
                  @if(auth()->user()?->hasPermission('Personnel', 'delete'))
                  <form method="POST" action="{{ route('personnels.destroy', $agent) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer le dossier de cet agent ?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600" title="Supprimer le dossier">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <p class="mt-3 text-sm font-semibold">Aucun dossier de personnel trouvé.</p>
                <p class="mt-1 text-xs text-gray-400">Enregistrez un premier agent avec ses pièces d'intégration.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($personnels->hasPages())
      <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
        {{ $personnels->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
