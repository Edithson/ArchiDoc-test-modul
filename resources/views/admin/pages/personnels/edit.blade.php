@extends('admin.layout.app')

@section('title', 'Modifier le dossier agent — ArchiDoc DGB')
@section('meta_description', 'Formulaire de modification d\'un agent et de gestion complète de ses pièces d\'intégration — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('personnels.index') }}" class="hover:text-brand-700">Dossiers du personnel</a>
    <span>/</span>
    <a href="{{ route('personnels.show', $personnel) }}" class="hover:text-brand-700">{{ $personnel->name }}</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">Modification</span>
  </nav>

  <!-- Modal Avertissement 5 Mo -->
  <div id="file-error-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="file-error-title">
    <div class="flex min-h-screen items-center justify-center p-4">
      <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" data-modal-close></div>
      <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center gap-3 text-amber-800 mb-3">
          <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-6 w-6 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </span>
          <h3 id="file-error-title" class="text-base font-extrabold text-gray-900">Fichier Trop Volumineux (Max 5 Mo)</h3>
        </div>
        <p id="file-error-message" class="text-xs text-gray-600 leading-relaxed mb-6"></p>
        <div class="flex justify-end">
          <button type="button" data-modal-close class="rounded-xl bg-gray-900 px-5 py-2 text-xs font-bold text-white hover:bg-gray-800">
            Compris
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- En-tête -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Modifier l'Agent & Dossier</h1>
      <p class="mt-1 text-xs text-gray-500">Mise à jour des informations de l'agent et gestion des fichiers (max. 5 Mo par fichier).</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('personnels.show', $personnel) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        Voir dossier
      </a>
      <a href="{{ route('personnels.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 px-3.5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
      </a>
    </div>
  </div>

  <!-- Container pour les inputs masqués des fichiers à supprimer -->
  <form id="edit-personnel-form" method="POST" action="{{ route('personnels.update', $personnel) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div id="removed-files-container"></div>

    <!-- SECTION 1: Informations Personnelles de l'Agent -->
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
      <div class="mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-100 text-brand-800 font-bold text-xs">1</span>
        <h2 class="text-sm font-bold text-gray-900">Informations de l'Agent</h2>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        
        <!-- Nom & Prénom -->
        <div class="sm:col-span-2">
          <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Nom & Prénom de l'agent <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" id="name" value="{{ old('name', $personnel->name) }}" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-sm font-bold text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
          @error('name')
            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Matricule -->
        <div>
          <label for="matricule" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Matricule Solde <span class="text-red-500">*</span>
          </label>
          <input type="text" name="matricule" id="matricule" value="{{ old('matricule', $personnel->matricule) }}" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-sm uppercase font-mono font-bold focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('matricule') border-red-500 @enderror">
          @error('matricule')
            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Adresse Email
          </label>
          <input type="email" name="email" id="email" value="{{ old('email', $personnel->email) }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('email') border-red-500 @enderror">
          @error('email')
            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Téléphone -->
        <div>
          <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Numéro de Téléphone
          </label>
          <input type="text" name="phone" id="phone" value="{{ old('phone', $personnel->phone) }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-sm font-mono focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('phone') border-red-500 @enderror">
        </div>

        <!-- Adresse -->
        <div>
          <label for="address" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Adresse ou Ville de résidence
          </label>
          <input type="text" name="address" id="address" value="{{ old('address', $personnel->address) }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('address') border-red-500 @enderror">
        </div>

      </div>
    </div>


    <!-- SECTION 2: PIÈCES OBLIGATOIRES -->
    <div class="rounded-xl border border-slate-200 bg-slate-50/40 p-4">
      <div class="mb-3.5 flex items-center justify-between border-b border-slate-200/80 pb-2.5">
        <div class="flex items-center gap-2">
          <span class="flex h-6 w-6 items-center justify-center rounded-md bg-slate-800 text-white font-bold text-xs">1</span>
          <h2 class="text-sm font-bold text-slate-800">Block 1 : Pièces Obligatoires</h2>
        </div>
        <span class="rounded-full bg-slate-200/80 px-2.5 py-0.5 text-[11px] font-semibold text-slate-700">
          {{ $obligatoryPieces->count() }} pièces
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($obligatoryPieces as $piece)
          @php
            $fileRecord = $uploadedFiles[$piece->id] ?? null;
            $existingPaths = $fileRecord ? ($fileRecord->file_paths ?? []) : [];
            $hasExisting = count($existingPaths) > 0;
          @endphp
          <div class="relative flex flex-col justify-between rounded-xl p-3 border {{ $hasExisting ? 'border-slate-400 bg-slate-50/80 shadow-xs' : 'border-gray-200 bg-white shadow-2xs hover:border-slate-300' }} transition-all duration-200"
               data-piece-card
               data-existing-count="{{ count($existingPaths) }}"
               data-on-class="border-slate-400 bg-slate-50/80 shadow-xs"
               data-off-class="border-gray-200 bg-white shadow-2xs hover:border-slate-300">

            <div>
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1">
                  <span class="block text-xs font-bold text-gray-800 truncate" data-on-class="text-slate-900" data-off-class="text-gray-800">
                    {{ $piece->name }}
                  </span>
                  @if($piece->description)
                    <p class="mt-0.5 text-[11px] text-gray-400 leading-tight truncate" data-on-class="text-slate-500" data-off-class="text-gray-400">{{ $piece->description }}</p>
                  @endif
                </div>

                <!-- Badge dynamique -->
                <div class="shrink-0">
                  <div data-show="filled" class="{{ $hasExisting ? '' : 'hidden' }}">
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                      <svg class="h-3 w-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                      <span data-count-badge>{{ count($existingPaths) > 1 ? count($existingPaths).' Fichiers' : 'Fournie' }}</span>
                    </span>
                  </div>
                  <div data-show="empty" class="{{ $hasExisting ? 'hidden' : '' }}">
                    <span class="rounded-md bg-rose-50 border border-rose-100 px-1.5 py-0.5 text-[10px] font-medium text-rose-600">Requis</span>
                  </div>
                </div>
              </div>

              <!-- Liste des fichiers existants en base -->
              <div class="mt-2 space-y-1.5" data-existing-list>
                @foreach($existingPaths as $idx => $path)
                  @php
                    $filename = basename($path);
                    $ext = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
                  @endphp
                  <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white py-1 px-2 text-xs shadow-2xs" data-existing-item="{{ $path }}">
                    <div class="flex items-center gap-1.5 truncate">
                      <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded bg-slate-700 text-[8px] font-bold text-white">{{ $ext ?: 'DOC' }}</span>
                      <a href="{{ Storage::url($path) }}" target="_blank" class="truncate text-xs font-medium text-slate-700 hover:text-brand-600 underline" title="{{ $filename }}">
                        {{ $filename }}
                      </a>
                    </div>
                    <button type="button" data-delete-existing="{{ $path }}" class="ml-2 shrink-0 text-[10px] font-medium text-rose-600 hover:text-rose-800 hover:underline" title="Supprimer ce fichier">
                      Supprimer
                    </button>
                  </div>
                @endforeach
              </div>

              <!-- Liste des nouveaux fichiers choisis (non encore enregistrés) -->
              <div data-show="pending" class="hidden mt-2 space-y-1.5" data-new-files-list>
              </div>
            </div>

            <!-- Champ d'upload multiple pour ajout cumulatif -->
            <div class="mt-2.5 border-t border-gray-100 pt-2" data-on-class="border-slate-200" data-off-class="border-gray-100">
              <div class="flex items-center justify-between mb-1">
                <label for="file_input_{{ $piece->id }}" class="block text-[11px] text-gray-500 font-medium" data-on-class="text-slate-600" data-off-class="text-gray-500">
                  <span>Ajouter des fichiers</span>
                </label>
                <button type="button" data-clear-new class="hidden text-[10px] font-medium text-rose-600 hover:text-rose-800">Effacer</button>
              </div>

              <input type="file"
                     id="file_input_{{ $piece->id }}"
                     name="files[{{ $piece->id }}][]"
                     multiple
                     accept=".pdf,.jpg,.jpeg,.png"
                     class="block w-full text-xs text-gray-500
                            file:mr-2 file:py-1 file:px-2.5
                            file:rounded-lg file:border-0
                            file:text-[11px] file:font-semibold
                            file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200
                            file:cursor-pointer cursor-pointer
                            rounded-lg border border-gray-200 bg-gray-50/50 p-1">
            </div>

          </div>
        @endforeach
      </div>
    </div>


    <!-- SECTION 3: PIÈCES FACULTATIVES -->
    <div class="rounded-xl border border-slate-200 bg-slate-50/40 p-4">
      <div class="mb-3.5 flex items-center justify-between border-b border-slate-200/80 pb-2.5">
        <div class="flex items-center gap-2">
          <span class="flex h-6 w-6 items-center justify-center rounded-md bg-slate-600 text-white font-bold text-xs">2</span>
          <h2 class="text-sm font-bold text-slate-800">Block 2 : Pièces Facultatives</h2>
        </div>
        <span class="rounded-full bg-slate-200/80 px-2.5 py-0.5 text-[11px] font-semibold text-slate-600">
          {{ $optionalPieces->count() }} pièces
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($optionalPieces as $piece)
          @php
            $fileRecord = $uploadedFiles[$piece->id] ?? null;
            $existingPaths = $fileRecord ? ($fileRecord->file_paths ?? []) : [];
            $hasExisting = count($existingPaths) > 0;
          @endphp
          <div class="relative flex flex-col justify-between rounded-xl p-3 border {{ $hasExisting ? 'border-slate-400 bg-slate-50/80 shadow-xs' : 'border-gray-200 bg-white shadow-2xs hover:border-slate-300' }} transition-all duration-200"
               data-piece-card
               data-existing-count="{{ count($existingPaths) }}"
               data-on-class="border-slate-400 bg-slate-50/80 shadow-xs"
               data-off-class="border-gray-200 bg-white shadow-2xs hover:border-slate-300">

            <div>
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1">
                  <span class="block text-xs font-bold text-gray-800 truncate" data-on-class="text-slate-900" data-off-class="text-gray-800">
                    {{ $piece->name }}
                  </span>
                  @if($piece->description)
                    <p class="mt-0.5 text-[11px] text-gray-400 leading-tight truncate" data-on-class="text-slate-500" data-off-class="text-gray-400">{{ $piece->description }}</p>
                  @endif
                </div>

                <!-- Badge dynamique -->
                <div class="shrink-0">
                  <div data-show="filled" class="{{ $hasExisting ? '' : 'hidden' }}">
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                      <svg class="h-3 w-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                      <span data-count-badge>{{ count($existingPaths) > 1 ? count($existingPaths).' Fichiers' : 'Fournie' }}</span>
                    </span>
                  </div>
                  <div data-show="empty" class="{{ $hasExisting ? 'hidden' : '' }}">
                    <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium text-slate-500">Optionnel</span>
                  </div>
                </div>
              </div>

              <!-- Liste des fichiers existants en base -->
              <div class="mt-2 space-y-1.5" data-existing-list>
                @foreach($existingPaths as $idx => $path)
                  @php
                    $filename = basename($path);
                    $ext = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
                  @endphp
                  <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white py-1 px-2 text-xs shadow-2xs" data-existing-item="{{ $path }}">
                    <div class="flex items-center gap-1.5 truncate">
                      <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded bg-slate-700 text-[8px] font-bold text-white">{{ $ext ?: 'DOC' }}</span>
                      <a href="{{ Storage::url($path) }}" target="_blank" class="truncate text-xs font-medium text-slate-700 hover:text-brand-600 underline" title="{{ $filename }}">
                        {{ $filename }}
                      </a>
                    </div>
                    <button type="button" data-delete-existing="{{ $path }}" class="ml-2 shrink-0 text-[10px] font-medium text-rose-600 hover:text-rose-800 hover:underline" title="Supprimer ce fichier">
                      Supprimer
                    </button>
                  </div>
                @endforeach
              </div>

              <!-- Liste des nouveaux fichiers choisis (non encore enregistrés) -->
              <div data-show="pending" class="hidden mt-2 space-y-1.5" data-new-files-list>
              </div>
            </div>

            <!-- Champ d'upload multiple pour ajout cumulatif -->
            <div class="mt-2.5 border-t border-gray-100 pt-2" data-on-class="border-slate-200" data-off-class="border-gray-100">
              <div class="flex items-center justify-between mb-1">
                <label for="file_input_{{ $piece->id }}" class="block text-[11px] text-gray-500 font-medium" data-on-class="text-slate-600" data-off-class="text-gray-500">
                  <span>Ajouter des fichiers</span>
                </label>
                <button type="button" data-clear-new class="hidden text-[10px] font-medium text-rose-600 hover:text-rose-800">Effacer</button>
              </div>

              <input type="file"
                     id="file_input_{{ $piece->id }}"
                     name="files[{{ $piece->id }}][]"
                     multiple
                     accept=".pdf,.jpg,.jpeg,.png"
                     class="block w-full text-xs text-gray-500
                            file:mr-2 file:py-1 file:px-2.5
                            file:rounded-lg file:border-0
                            file:text-[11px] file:font-semibold
                            file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200
                            file:cursor-pointer cursor-pointer
                            rounded-lg border border-gray-200 bg-gray-50/50 p-1">
            </div>

          </div>
        @endforeach
      </div>
    </div>


    <!-- Actions de validation -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 flex items-center justify-end gap-3">
      <a href="{{ route('personnels.show', $personnel) }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        Annuler
      </a>
      <button type="submit" class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        Enregistrer les modifications
      </button>
    </div>

  </form>

</div>
@endsection

@push('scripts')
<script>
(function () {
  const MAX_SIZE = 5 * 1024 * 1024; // 5 Mo

  const modal = document.getElementById('file-error-modal');
  const modalMessage = document.getElementById('file-error-message');
  const removedFilesContainer = document.getElementById('removed-files-container');

  const tokens = (value) => (value || '').split(/\s+/).filter(Boolean);

  function openModal(message) {
    modalMessage.textContent = message;
    modal.classList.remove('hidden');
  }

  function closeModal() {
    modal.classList.add('hidden');
  }

  modal.querySelectorAll('[data-modal-close]').forEach((el) => el.addEventListener('click', closeModal));
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

  function describe(file) {
    const mime = file.type || '';
    return {
      name: file.name,
      size: (file.size / (1024 * 1024)).toFixed(2) + ' Mo',
      type: mime.includes('pdf') ? 'PDF' : (mime.includes('image') ? 'IMG' : 'DOC'),
    };
  }

  function updateCardState(card) {
    const existingItems = card.querySelectorAll('[data-existing-item]');
    const existingCount = existingItems.length;
    const input = card.querySelector('input[type="file"]');
    const newFiles = Array.from(input.files || []);
    const newCount = newFiles.length;
    const totalCount = existingCount + newCount;
    const filled = totalCount > 0;

    [card, ...card.querySelectorAll('[data-on-class]')].forEach((el) => {
      const on = tokens(el.getAttribute('data-on-class'));
      const off = tokens(el.getAttribute('data-off-class'));
      (filled ? off : on).forEach((c) => el.classList.remove(c));
      (filled ? on : off).forEach((c) => el.classList.add(c));
    });

    card.querySelectorAll('[data-show="filled"]').forEach((el) => el.classList.toggle('hidden', !filled));
    card.querySelectorAll('[data-show="empty"]').forEach((el) => el.classList.toggle('hidden', filled));
    card.querySelectorAll('[data-show="pending"]').forEach((el) => el.classList.toggle('hidden', newCount === 0));
    card.querySelectorAll('[data-clear-new]').forEach((el) => el.classList.toggle('hidden', newCount === 0));

    const badgeText = card.querySelector('[data-count-badge]');
    if (badgeText) {
      badgeText.textContent = totalCount > 1 ? totalCount + ' Fichiers' : 'Fournie';
    }

    const newContainer = card.querySelector('[data-new-files-list]');
    if (newContainer) {
      newContainer.innerHTML = '';
      if (newCount > 0) {
        newFiles.forEach((file) => {
          const info = describe(file);
          const item = document.createElement('div');
          item.className = 'flex items-center justify-between rounded-lg border border-dashed border-slate-300 bg-slate-50/60 py-1 px-2 text-xs';
          item.innerHTML = `
            <div class="flex items-center gap-1.5 truncate">
              <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded bg-slate-700 text-[8px] font-bold text-white">${info.type}</span>
              <span class="block truncate text-xs font-medium text-slate-700" title="${info.name}">${info.name}</span>
            </div>
            <span class="shrink-0 font-mono text-[10px] text-slate-400 ml-2">${info.size}</span>
          `;
          newContainer.appendChild(item);
        });
      }
    }
  }

  document.querySelectorAll('[data-piece-card]').forEach((card) => {
    const input = card.querySelector('input[type="file"]');
    const clearNewBtn = card.querySelector('[data-clear-new]');

    // Gestion de la suppression des fichiers existants en base
    card.querySelectorAll('[data-delete-existing]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const path = btn.getAttribute('data-delete-existing');
        const itemRow = card.querySelector('[data-existing-item="' + CSS.escape(path) + '"]');

        if (itemRow) {
          itemRow.remove();

          // Ajout de l'input hidden pour transmettre le fichier à supprimer au backend
          const hiddenInput = document.createElement('input');
          hiddenInput.type = 'hidden';
          hiddenInput.name = 'remove_files[]';
          hiddenInput.value = path;
          removedFilesContainer.appendChild(hiddenInput);

          updateCardState(card);
        }
      });
    });

    // Gestion de la sélection de nouveaux fichiers
    input.addEventListener('change', () => {
      const selectedFiles = Array.from(input.files || []);

      if (selectedFiles.length === 0) {
        updateCardState(card);
        return;
      }

      const oversizedFile = selectedFiles.find((f) => f.size > MAX_SIZE);
      if (oversizedFile) {
        const sizeMB = (oversizedFile.size / (1024 * 1024)).toFixed(2);
        openModal('Le fichier « ' + oversizedFile.name + ' » (' + sizeMB + ' Mo) dépasse la limite maximale autorisée de 5 Mo par fichier.');
        input.value = '';
        updateCardState(card);
        return;
      }

      updateCardState(card);
    });

    if (clearNewBtn) {
      clearNewBtn.addEventListener('click', () => {
        input.value = '';
        updateCardState(card);
      });
    }

    updateCardState(card);
  });
})();
</script>
@endpush