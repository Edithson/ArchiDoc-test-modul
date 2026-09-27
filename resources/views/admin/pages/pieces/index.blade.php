@extends('admin.layout.app')

@section('title', 'Référentiel des pièces d\'intégration — ArchiDoc DGB')
@section('meta_description', 'Gestion de la nomenclature et du statut obligatoire ou optionnel des pièces d\'intégration du personnel — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8" x-data="{ showCreateModal: false, showEditModal: false, editPiece: { id: null, name: '', description: '', obligatory: true } }">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('personnels.index') }}" class="hover:text-brand-700">Gestion du personnel</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Pièces d'intégration</span>
  </nav>

  <!-- Message de succès -->
  @if(session('success'))
    <div class="mb-6 flex items-center justify-between rounded-xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800">
      <div class="flex items-center gap-2.5">
        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
      </div>
    </div>
  @endif

  <!-- En-tête -->
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Nomenclature des Pièces</h1>
      <p class="mt-1 text-sm text-gray-500">Définissez et gérez la liste des pièces constitutives des dossiers d'intégration administrative.</p>
    </div>

    <button @click="showCreateModal = true" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700 transition-all">
      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Ajouter une Pièce
    </button>
  </div>

  <!-- Barre de Recherche -->
  <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
    <form method="GET" action="{{ route('pieces.index') }}" class="flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher une pièce par libellé ou description..." class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
      </div>
      <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
        Rechercher
      </button>
      @if($search)
        <a href="{{ route('pieces.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
          Réinitialiser
        </a>
      @endif
    </form>
  </div>

  <!-- Tableau des Pièces -->
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xs">
    <table class="w-full text-left text-sm text-gray-600">
      <thead class="border-b border-gray-200 bg-gray-50/80 text-xs uppercase font-extrabold text-gray-700 tracking-wider">
        <tr>
          <th scope="col" class="px-6 py-4">Intitulé de la Pièce</th>
          <th scope="col" class="px-6 py-4">Caractère / Statut</th>
          <th scope="col" class="px-6 py-4">Description</th>
          <th scope="col" class="px-6 py-4 text-center">Fichiers Déposés</th>
          <th scope="col" class="px-6 py-4 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @forelse($pieces as $piece)
          <tr class="hover:bg-gray-50/60 transition-colors">
            
            <td class="px-6 py-4 font-bold text-gray-900">
              {{ $piece->name }}
            </td>

            <td class="px-6 py-4">
              @if($piece->obligatory)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100/80 px-3 py-1 text-xs font-extrabold text-amber-900 border border-amber-200/70">
                  <span class="h-2 w-2 rounded-full bg-amber-600"></span>
                  Obligatoire (Block 1)
                </span>
              @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-700 border border-slate-200">
                  <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                  Facultative (Block 2)
                </span>
              @endif
            </td>

            <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
              {{ $piece->description ?? 'Aucune précision' }}
            </td>

            <td class="px-6 py-4 text-center">
              <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700">
                {{ $piece->files_count }} dossier(s)
              </span>
            </td>

            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                
                <!-- Modifier -->
                <button @click="editPiece = { id: {{ $piece->id }}, name: '{{ addslashes($piece->name) }}', description: '{{ addslashes($piece->description ?? '') }}', obligatory: {{ $piece->obligatory ? 'true' : 'false' }} }; showEditModal = true" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-brand-700" title="Modifier">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>

                <!-- Supprimer -->
                <form method="POST" action="{{ route('pieces.destroy', $piece) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la pièce « {{ addslashes($piece->name) }} » ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="rounded-lg p-1.5 text-gray-400 hover:bg-amber-50 hover:text-amber-800" title="Supprimer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </form>

              </div>
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
              Aucune pièce d'intégration enregistrée dans la nomenclature.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($pieces->hasPages())
      <div class="border-t border-gray-200 px-6 py-4">
        {{ $pieces->links() }}
      </div>
    @endif
  </div>


  <!-- MODAL DE CRÉATION DE PIÈCE -->
  <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex min-h-screen items-center justify-center p-4">
      <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity" @click="showCreateModal = false"></div>

      <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all">
        <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
          <h3 class="text-lg font-extrabold text-gray-900">Ajouter une Pièce d'Intégration</h3>
          <button @click="showCreateModal = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form method="POST" action="{{ route('pieces.store') }}" class="space-y-4">
          @csrf

          <div>
            <label for="create_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
              Libellé de la pièce <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="create_name" required placeholder="Ex: Copie certifiée d'Acte de Naissance" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-bold text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
          </div>

          <div>
            <label for="create_description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
              Description ou instructions
            </label>
            <textarea name="description" id="create_description" rows="2" placeholder="Précisez la date de validité ou la signature requise..." class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600"></textarea>
          </div>

          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
              Exigence / Bloc de classement <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50/50 p-3 cursor-pointer">
                <input type="radio" name="obligatory" value="1" checked class="h-4 w-4 text-amber-700 focus:ring-amber-500">
                <div>
                  <span class="block text-xs font-extrabold text-amber-900">Obligatoire</span>
                  <span class="text-[10px] text-amber-700">Block 1 requis</span>
                </div>
              </label>
              <label class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50/50 p-3 cursor-pointer">
                <input type="radio" name="obligatory" value="0" class="h-4 w-4 text-slate-700 focus:ring-slate-500">
                <div>
                  <span class="block text-xs font-extrabold text-slate-900">Facultatif</span>
                  <span class="text-[10px] text-slate-600">Block 2 optionnel</span>
                </div>
              </label>
            </div>
          </div>

          <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
            <button type="button" @click="showCreateModal = false" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
              Annuler
            </button>
            <button type="submit" class="rounded-xl bg-brand-700 px-5 py-2 text-sm font-bold text-white shadow-md hover:bg-brand-800">
              Enregistrer la pièce
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- MODAL DE MODIFICATION DE PIÈCE -->
  <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex min-h-screen items-center justify-center p-4">
      <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity" @click="showEditModal = false"></div>

      <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all">
        <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
          <h3 class="text-lg font-extrabold text-gray-900">Modifier la Pièce d'Intégration</h3>
          <button @click="showEditModal = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form method="POST" :action="`/admin/pieces/${editPiece.id}`" class="space-y-4">
          @csrf
          @method('PUT')

          <div>
            <label for="edit_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
              Libellé de la pièce <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="edit_name" x-model="editPiece.name" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-bold text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
          </div>

          <div>
            <label for="edit_description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
              Description ou instructions
            </label>
            <textarea name="description" id="edit_description" rows="2" x-model="editPiece.description" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600"></textarea>
          </div>

          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
              Exigence / Bloc de classement <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50/50 p-3 cursor-pointer">
                <input type="radio" name="obligatory" value="1" :checked="editPiece.obligatory" class="h-4 w-4 text-amber-700 focus:ring-amber-500">
                <div>
                  <span class="block text-xs font-extrabold text-amber-900">Obligatoire</span>
                  <span class="text-[10px] text-amber-700">Block 1 requis</span>
                </div>
              </label>
              <label class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50/50 p-3 cursor-pointer">
                <input type="radio" name="obligatory" value="0" :checked="!editPiece.obligatory" class="h-4 w-4 text-slate-700 focus:ring-slate-500">
                <div>
                  <span class="block text-xs font-extrabold text-slate-900">Facultatif</span>
                  <span class="text-[10px] text-slate-600">Block 2 optionnel</span>
                </div>
              </label>
            </div>
          </div>

          <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
            <button type="button" @click="showEditModal = false" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
              Annuler
            </button>
            <button type="submit" class="rounded-xl bg-brand-700 px-5 py-2 text-sm font-bold text-white shadow-md hover:bg-brand-800">
              Mettre à jour
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection
