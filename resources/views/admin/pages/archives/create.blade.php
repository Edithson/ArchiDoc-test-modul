@extends('admin.layout.app')

@section('title', 'Nouvelle archive — ArchiDoc DGB')
@section('meta_description', 'Création et numérisation d\'une nouvelle archive — ArchiDoc, Direction Générale du Budget')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Nouvelle archive</h1>
      <p class="text-sm text-gray-500">Chargez le document et renseignez les informations de classement dans ce formulaire unique.</p>
    </div>
    <div>
      <a href="{{ route('archives.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-600">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour au tableau de bord
      </a>
    </div>
  </div>

  <!-- Disposition sur 2 colonnes (Split-View) -->
  <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 items-start">

    <!-- ==================== COLONNE GAUCHE : FORMULAIRE & UPLOAD ==================== -->
    <div class="lg:col-span-7 xl:col-span-7 space-y-6">

      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <!-- En-tête de la carte -->
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 sm:px-8">
          <div class="flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-700 text-xs font-bold text-white shadow-sm">1</span>
            <h2 class="text-base font-bold text-gray-900">Document & Informations de l'archive</h2>
          </div>
        </div>

        <div class="px-6 py-6 sm:px-8 sm:py-8">

          <!-- Bandeau de confirmation (masqué par défaut) -->
          <div id="success-panel" tabindex="-1" class="mb-6 hidden rounded-xl border border-emerald-200 bg-emerald-50 p-4 focus:outline-none" role="status">
            <div class="flex gap-3">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
              <div>
                <p class="text-sm font-bold text-emerald-800">Archive créée avec succès !</p>
                <p class="mt-1 text-sm text-emerald-700">Le document et les informations de classement ont été enregistrés.</p>
              </div>
            </div>
          </div>

          <form id="archive-form" action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="space-y-6">

              <!-- Zone d'upload de fichier (Drag & Drop) -->
              <div>
                <label class="mb-1.5 block text-sm font-semibold text-gray-800">
                  Document numérique <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>

                <!-- Zone réceptrice -->
                <div id="dropzone"
                  class="group relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 p-6 text-center transition-all hover:border-brand-600 hover:bg-brand-50/30 cursor-pointer">
                  
                  <input type="file" id="file" name="file" accept=".pdf,image/*" required class="sr-only">

                  <!-- Prompt par défaut -->
                  <div id="dropzone-prompt" class="flex flex-col items-center">
                    <span class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700 shadow-sm transition-transform group-hover:scale-110">
                      <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                      </svg>
                    </span>
                    <p class="text-sm font-medium text-gray-700">
                      <span class="font-bold text-brand-700 underline underline-offset-2 hover:text-brand-800">Glissez-déposez un fichier ici</span> ou parcourez
                    </p>
                    <p class="mt-1 text-xs text-gray-400">Formats acceptés : PDF, PNG, JPG, WEBP (jusqu'à 20 Mo)</p>
                  </div>

                  <!-- Badge Fichier sélectionné (Masqué par défaut) -->
                  <div id="selected-file-badge" class="hidden w-full flex-col sm:flex-row items-center justify-between gap-3 rounded-lg border border-brand-200 bg-white p-3.5 shadow-sm">
                    <div class="flex items-center gap-3 min-w-0">
                      <span id="file-icon-container" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-100 text-brand-700 font-bold text-xs uppercase">
                        PDF
                      </span>
                      <div class="text-left truncate">
                        <p id="file-name-display" class="truncate text-sm font-bold text-gray-900">nom-du-fichier.pdf</p>
                        <p id="file-size-display" class="text-xs text-gray-500">2.4 Mo · Charger un autre fichier</p>
                      </div>
                    </div>

                    <button type="button" id="btn-remove-file" class="inline-flex shrink-0 items-center gap-1 rounded-md border border-gray-200 bg-gray-50 px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 hover:border-red-200 transition-colors">
                      <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      Retirer
                    </button>
                  </div>

                </div>
                
                <!-- Notification de pré-remplissage automatique -->
                <div id="autofill-notice" class="mt-2.5 hidden flex items-center gap-2 rounded-lg border border-brand-200 bg-brand-50/80 p-3 text-xs text-brand-900 shadow-sm transition-all">
                  <svg class="h-4 w-4 shrink-0 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                  <span id="autofill-notice-text">Informations détectées et pré-remplies automatiquement depuis le nom du fichier. Veuillez vérifier la concordance ci-dessous.</span>
                </div>

                <p id="file-error" class="mt-1.5 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Format du document -->
              <div>
                <label for="format" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Format du document <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>
                <div class="relative">
                  <select id="format" name="format" required aria-required="true"
                    class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                    <option value="" disabled selected>Sélectionner le format...</option>
                    @foreach($formats as $fmt)
                      <option value="{{ $fmt }}">{{ $fmt }}</option>
                    @endforeach
                  </select>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </div>
                <p id="format-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Type d'archives (combobox avec autocomplétion) -->
              <div>
                <label for="typearchive" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Type d'archives <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>
                <div class="relative">
                  <input type="text" id="typearchive" name="typearchive" autocomplete="off" required aria-required="true"
                    role="combobox" aria-expanded="false" aria-controls="typearchive-listbox" aria-autocomplete="list"
                    placeholder="Rechercher ou saisir un type d'archive..."
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                  <ul id="typearchive-listbox" role="listbox" aria-label="Types d'archives" class="styled-scroll absolute z-20 mt-1 hidden max-h-56 w-full overflow-auto rounded-lg border border-gray-200 bg-white py-1 text-sm shadow-lg"></ul>
                </div>
                <p id="typearchive-hint" class="mt-1 hidden text-xs text-gray-500"></p>
                <p id="typearchive-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Objet de l'archive -->
              <div>
                <div class="mb-1.5 flex items-baseline justify-between gap-2">
                  <label for="description" class="block text-sm font-medium text-gray-700">
                    Objet de l'archive <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                  </label>
                  <span id="description-count" class="shrink-0 text-xs text-gray-400">0/30</span>
                </div>
                <textarea id="description" name="description" rows="3" maxlength="30" required aria-required="true" aria-describedby="description-count"
                  placeholder="Résumé court de l'objet de l'archive..."
                  class="block w-full resize-y rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30"></textarea>
                <p id="description-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Date signature -->
              <div>
                <label for="date_doc" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Date signature <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>
                <input type="date" id="date_doc" name="date_doc" required aria-required="true"
                  class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                <p id="date_doc-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Emplacement physique / virtuel -->
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label for="emplacement" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Emplacement physique <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                  </label>
                  <div class="relative">
                    <select id="emplacement" name="emplacement" required aria-required="true"
                      class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                      <option value="" disabled selected>Sélectionner l'emplacement...</option>
                      @foreach($emplacementsPhysiques as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                      @endforeach
                    </select>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                  </div>
                  <p id="emplacement-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
                </div>

                <div>
                  <label for="emplacement2" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Emplacement virtuel <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                  </label>
                  <div class="relative">
                    <select id="emplacement2" name="emplacement2" required aria-required="true"
                      class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                      <option value="" disabled selected>Sélectionner l'emplacement...</option>
                      @foreach($emplacementsVirtuels as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                      @endforeach
                    </select>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                  </div>
                  <p id="emplacement2-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
                </div>
              </div>

              <!-- Rayon / Travée -->
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label for="rayon" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Rayon <span class="text-gray-400">(optionnel)</span>
                  </label>
                  <input type="text" id="rayon" name="rayon" autocomplete="off"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                </div>
                <div>
                  <label for="travee" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Travée <span class="text-gray-400">(optionnel)</span>
                  </label>
                  <input type="text" id="travee" name="travee" autocomplete="off"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                </div>
              </div>

              <!-- Cote de boite d'archives -->
              <div>
                <label for="cote" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Cote de boîte d'archives <span class="text-gray-400">(optionnel)</span>
                </label>
                <input type="text" id="cote" name="cote" autocomplete="off" placeholder="Ex. B-2026-014"
                  class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              </div>

              <!-- Groupe d'accès -->
              <div>
                <label for="departement" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Groupe d'accès <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>
                <div class="relative">
                  <select id="departement" name="departement" required aria-required="true"
                    class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                    <option value="" disabled selected>Sélectionner le groupe d'accès...</option>
                    @foreach($groupesAcces as $grp)
                      <option value="{{ $grp['sigle'] }}">{{ $grp['sigle'] }} — {{ $grp['nom'] }}</option>
                    @endforeach
                  </select>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </div>
                <p id="departement-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

            </div>

            <!-- Pied de formulaire -->
            <div class="mt-8 border-t border-gray-100 pt-6">
              <p class="mb-4 text-xs text-gray-500"><span class="text-red-500" aria-hidden="true">*</span> Les champs marqués d'un astérisque sont obligatoires.</p>
              <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="reset" id="reset-btn"
                  class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-600">
                  Réinitialiser
                </button>
                <button type="submit"
                  class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  Créer l'archive
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>

    </div>

    <!-- ==================== COLONNE DROITE : APERÇU EN DIRECT (STICKY) ==================== -->
    <div class="lg:col-span-5 xl:col-span-5 lg:sticky lg:top-20 space-y-4">

      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        
        <!-- En-tête de la carte d'aperçu -->
        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 px-5 py-3.5">
          <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <h3 class="text-sm font-bold text-gray-900">Aperçu du document</h3>
          </div>
          <span id="preview-status-badge" class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-600">
            En attente
          </span>
        </div>

        <!-- Conteneur d'affichage de l'aperçu -->
        <div class="relative flex min-h-[500px] lg:min-h-[660px] flex-col items-center justify-center bg-gray-100/70 p-3">

          <!-- 1. État Vide (No file selected) -->
          <div id="preview-empty-state" class="flex flex-col items-center justify-center text-center p-6 max-w-xs">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-sm text-gray-400">
              <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <h4 class="text-sm font-bold text-gray-800">Aucun document chargé</h4>
            <p class="mt-1 text-xs text-gray-500 leading-relaxed">
              Sélectionnez ou glissez un fichier <strong>PDF</strong> ou une <strong>Image</strong> dans le formulaire pour afficher l'aperçu dynamique ici.
            </p>
          </div>

          <!-- 2. Aperçu PDF (iframe) -->
          <div id="preview-pdf-container" class="hidden h-full w-full">
            <iframe id="preview-pdf" src="" class="h-[520px] lg:h-[660px] w-full rounded-lg border border-gray-200 bg-white shadow-inner" title="Aperçu PDF"></iframe>
          </div>

          <!-- 3. Aperçu Image (img) -->
          <div id="preview-image-container" class="hidden flex h-full w-full items-center justify-center overflow-auto p-2">
            <img id="preview-image" src="" alt="Aperçu de l'image" class="max-h-[520px] lg:max-h-[660px] w-auto max-w-full rounded-lg object-contain shadow-md border border-gray-200">
          </div>

          <!-- 4. Format non géré -->
          <div id="preview-unsupported-state" class="hidden flex flex-col items-center justify-center text-center p-6">
            <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
              <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
            </div>
            <h4 class="text-sm font-bold text-gray-900">Aperçu direct non disponible</h4>
            <p class="mt-1 text-xs text-gray-500">Ce format de fichier ne supporte pas l'aperçu visuel direct.</p>
          </div>

        </div>

        <!-- Barre d'outils bas d'aperçu -->
        <div id="preview-toolbar" class="hidden border-t border-gray-100 bg-white px-4 py-2.5 flex items-center justify-between">
          <div class="flex items-center gap-2 min-w-0">
            <span id="preview-filename" class="text-xs font-semibold text-gray-700 truncate max-w-[180px]">document.pdf</span>
          </div>
          <a id="btn-open-new-tab" href="#" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-brand-700 hover:text-brand-800 underline">
            Ouvrir plein écran
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        </div>

      </div>

    </div>

  </div>

</div>
@endsection

@push('scripts')
<script>
  window.ArchiDoc = window.ArchiDoc || {};
  window.ArchiDoc.archiveTypes = @json($archiveTypes);
  window.ArchiDoc.groupesAcces = @json($groupesAcces);
</script>
@vite('resources/js/archives/create.js')
@endpush
