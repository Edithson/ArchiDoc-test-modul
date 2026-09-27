@extends('admin.layout.app')

@section('title', 'Modifier le dossier agent — ArchiDoc DGB')
@section('meta_description', 'Formulaire de modification d\'un agent et d\'ajout/mise à jour de ses pièces d\'intégration — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8"
     x-data="{
       filesInfo: {},
       errorMessage: '',
       showErrorModal: false,

       handleFileChange(pieceId, event) {
         const file = event.target.files[0];
         if (!file) {
           delete this.filesInfo[pieceId];
           return;
         }

         const maxSize = 5 * 1024 * 1024; // 5 Mo
         if (file.size > maxSize) {
           const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
           this.errorMessage = `Le fichier « ${file.name} » de ${fileSizeMB} Mo dépasse la limite maximale autorisée de 5 Mo. Veuillez choisir un fichier plus léger.`;
           this.showErrorModal = true;
           event.target.value = '';
           delete this.filesInfo[pieceId];
           return;
         }

         this.filesInfo[pieceId] = {
           name: file.name,
           size: (file.size / (1024 * 1024)).toFixed(2) + ' Mo',
           type: file.type.includes('pdf') ? 'PDF' : (file.type.includes('image') ? 'IMAGE' : 'DOC')
         };
       }
     }">

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

  <!-- Modal Avertissement 5Mo -->
  <div x-show="showErrorModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex min-h-screen items-center justify-center p-4">
      <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="showErrorModal = false"></div>
      <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center gap-3 text-amber-800 mb-3">
          <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-6 w-6 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </span>
          <h3 class="text-base font-extrabold text-gray-900">Fichier Trop Volumineux (Max 5 Mo)</h3>
        </div>
        <p class="text-xs text-gray-600 leading-relaxed mb-6" x-text="errorMessage"></p>
        <div class="flex justify-end">
          <button type="button" @click="showErrorModal = false" class="rounded-xl bg-gray-900 px-5 py-2 text-xs font-bold text-white hover:bg-gray-800">
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
      <p class="mt-1 text-sm text-gray-500">Mettez à jour les informations de l'agent. Les grilles à surbrillance renforcée indiquent les pièces déjà fournies.</p>
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

  <!-- Formulaire -->
  <form method="POST" action="{{ route('personnels.update', $personnel) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- SECTION 1: Informations Personnelles de l'Agent -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
      <div class="mb-4 flex items-center gap-2.5 border-b border-gray-100 pb-3">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 text-brand-800 font-bold text-sm">1</span>
        <h2 class="text-base font-extrabold text-gray-900">Informations de l'Agent</h2>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        
        <!-- Nom & Prénom -->
        <div class="sm:col-span-2">
          <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Nom & Prénom de l'agent <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" id="name" value="{{ old('name', $personnel->name) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-bold text-gray-900 focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('name') border-red-500 @enderror">
          @error('name')
            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Matricule -->
        <div>
          <label for="matricule" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Matricule Solde <span class="text-red-500">*</span>
          </label>
          <input type="text" name="matricule" id="matricule" value="{{ old('matricule', $personnel->matricule) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm uppercase font-mono font-bold focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('matricule') border-red-500 @enderror">
          @error('matricule')
            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Adresse Email
          </label>
          <input type="email" name="email" id="email" value="{{ old('email', $personnel->email) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('email') border-red-500 @enderror">
          @error('email')
            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Téléphone -->
        <div>
          <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Numéro de Téléphone
          </label>
          <input type="text" name="phone" id="phone" value="{{ old('phone', $personnel->phone) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-mono focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('phone') border-red-500 @enderror">
        </div>

        <!-- Adresse -->
        <div>
          <label for="address" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Adresse ou Ville de résidence
          </label>
          <input type="text" name="address" id="address" value="{{ old('address', $personnel->address) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600 @error('address') border-red-500 @enderror">
        </div>

      </div>
    </div>


    <!-- SECTION 2: BLOCK 1 - PIÈCES OBLIGATOIRES (Thème Ambré Chaud) -->
    <div class="rounded-2xl border-2 border-amber-300/80 bg-amber-50/20 p-6 shadow-xs">
      <div class="mb-5 flex items-center justify-between border-b border-amber-200/70 pb-3">
        <div class="flex items-center gap-2.5">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-700 text-white font-black text-sm shadow-xs">1</span>
          <div>
            <h2 class="text-base font-extrabold text-amber-950">Block 1 : Pièces Obligatoires</h2>
            <p class="text-xs text-amber-800/80">Pièces obligatoires. Couleur Ambrée dédiée.</p>
          </div>
        </div>
        <span class="rounded-full bg-amber-700 px-3.5 py-1 text-xs font-black text-white shadow-2xs uppercase tracking-wider">
          Obligatoires ({{ $obligatoryPieces->count() }} pièces)
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($obligatoryPieces as $piece)
          @php $hasFile = isset($uploadedFiles[$piece->id]); @endphp
          <div class="relative rounded-2xl p-4 transition-all duration-300 flex flex-col justify-between"
               :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) 
                 ? 'border-2 border-amber-600 bg-amber-100/90 ring-2 ring-amber-500/40 shadow-md' 
                 : 'border border-amber-300/80 bg-white shadow-2xs hover:border-amber-400 hover:shadow-xs'">
            
            <div>
              <div class="flex items-start justify-between mb-2">
                <div>
                  <span class="text-sm font-black block" :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) ? 'text-amber-950' : 'text-gray-900'">
                    {{ $piece->name }}
                  </span>
                  @if($piece->description)
                    <p class="text-xs mt-0.5" :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) ? 'text-amber-900/80' : 'text-gray-500'">{{ $piece->description }}</p>
                  @endif
                </div>

                <div>
                  @if($hasFile)
                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-white bg-amber-800 px-3 py-1 rounded-full shadow-xs">
                      <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                      </svg>
                      Fournie
                    </span>
                  @else
                    <template x-if="filesInfo[{{ $piece->id }}]">
                      <span class="inline-flex items-center gap-1.5 text-xs font-black text-white bg-amber-800 px-3 py-1 rounded-full shadow-xs">
                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Fournie
                      </span>
                    </template>
                    <template x-if="!filesInfo[{{ $piece->id }}]">
                      <span class="text-[10px] font-extrabold text-amber-900 uppercase bg-amber-100 px-2.5 py-1 rounded-lg border border-amber-300">
                        Manquante
                      </span>
                    </template>
                  @endif
                </div>
              </div>

              <!-- Fichier existant -->
              @if($hasFile)
                <div class="mt-3 rounded-xl border-2 border-amber-500 bg-white p-3 text-xs flex items-center justify-between shadow-xs">
                  <a href="{{ Storage::url($uploadedFiles[$piece->id]->file_paths[0] ?? '#') }}" target="_blank" class="inline-flex items-center gap-2 font-extrabold text-amber-950 underline hover:text-amber-800 truncate">
                    <svg class="h-4 w-4 text-amber-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span class="truncate">Consulter le document fourni</span>
                  </a>
                </div>
              @endif
            </div>

            <!-- Champ d'Upload Natif Stylisé Très Visible -->
            <div class="mt-4 pt-3 border-t" :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) ? 'border-amber-300' : 'border-amber-100'">
              <label for="file_input_{{ $piece->id }}" class="block text-xs font-bold uppercase tracking-wider mb-1.5" :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) ? 'text-amber-950' : 'text-gray-700'">
                <span>{{ $hasFile ? 'Remplacer le document joint (Max 5 Mo)' : 'Téléverser le document PDF/Image (Max 5 Mo)' }}</span>
              </label>

              <input type="file" 
                     id="file_input_{{ $piece->id }}" 
                     name="files[{{ $piece->id }}]" 
                     accept=".pdf,.jpg,.jpeg,.png"
                     @change="handleFileChange({{ $piece->id }}, $event)"
                     class="block w-full text-xs text-gray-700
                            file:mr-3 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:text-xs file:font-black file:uppercase file:tracking-wider
                            file:bg-amber-700 file:text-white hover:file:bg-amber-800
                            file:cursor-pointer cursor-pointer
                            rounded-xl border border-amber-300 bg-white p-1.5 shadow-2xs">
            </div>

          </div>
        @endforeach
      </div>
    </div>


    <!-- SECTION 3: BLOCK 2 - PIÈCES FACULTATIVES (Thème Indigo / Ardoise) -->
    <div class="rounded-2xl border-2 border-indigo-300/80 bg-indigo-50/20 p-6 shadow-xs">
      <div class="mb-5 flex items-center justify-between border-b border-indigo-200/70 pb-3">
        <div class="flex items-center gap-2.5">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-700 text-white font-black text-sm shadow-xs">2</span>
          <div>
            <h2 class="text-base font-extrabold text-indigo-950">Block 2 : Pièces Facultatives</h2>
            <p class="text-xs text-indigo-800/80">Documents d'accompagnement. Couleur Indigo dédiée.</p>
          </div>
        </div>
        <span class="rounded-full bg-indigo-700 px-3.5 py-1 text-xs font-black text-white shadow-2xs uppercase tracking-wider">
          Facultatives ({{ $optionalPieces->count() }} pièces)
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($optionalPieces as $piece)
          @php $hasFile = isset($uploadedFiles[$piece->id]); @endphp
          <div class="relative rounded-2xl p-4 transition-all duration-300 flex flex-col justify-between"
               :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) 
                 ? 'border-2 border-indigo-600 bg-indigo-100/90 ring-2 ring-indigo-500/40 shadow-md' 
                 : 'border border-indigo-200/90 bg-white shadow-2xs hover:border-indigo-300 hover:shadow-xs'">
            
            <div>
              <div class="flex items-start justify-between mb-2">
                <div>
                  <span class="text-sm font-black block" :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) ? 'text-indigo-950' : 'text-gray-900'">
                    {{ $piece->name }}
                  </span>
                  @if($piece->description)
                    <p class="text-xs mt-0.5" :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) ? 'text-indigo-900/80' : 'text-gray-500'">{{ $piece->description }}</p>
                  @endif
                </div>
                <div>
                  @if($hasFile)
                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-white bg-indigo-700 px-3 py-1 rounded-full shadow-xs">
                      <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                      </svg>
                      Fournie
                    </span>
                  @else
                    <template x-if="filesInfo[{{ $piece->id }}]">
                      <span class="inline-flex items-center gap-1.5 text-xs font-black text-white bg-indigo-700 px-3 py-1 rounded-full shadow-xs">
                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Fournie
                      </span>
                    </template>
                    <template x-if="!filesInfo[{{ $piece->id }}]">
                      <span class="text-[10px] font-extrabold text-indigo-900 uppercase bg-indigo-100 px-2.5 py-1 rounded-lg border border-indigo-200">
                        Non fournie
                      </span>
                    </template>
                  @endif
                </div>
              </div>

              @if($hasFile)
                <div class="mt-3 rounded-xl border-2 border-indigo-500 bg-white p-3 text-xs flex items-center justify-between shadow-xs">
                  <a href="{{ Storage::url($uploadedFiles[$piece->id]->file_paths[0] ?? '#') }}" target="_blank" class="inline-flex items-center gap-2 font-extrabold text-indigo-950 underline hover:text-indigo-800 truncate">
                    <svg class="h-4 w-4 text-indigo-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span class="truncate">Consulter le document fourni</span>
                  </a>
                </div>
              @endif
            </div>

            <!-- Champ d'Upload Natif Stylisé Très Visible -->
            <div class="mt-4 pt-3 border-t" :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) ? 'border-indigo-300' : 'border-indigo-100'">
              <label for="file_input_{{ $piece->id }}" class="block text-xs font-bold uppercase tracking-wider mb-1.5" :class="({{ $hasFile ? 'true' : 'false' }} || filesInfo[{{ $piece->id }}]) ? 'text-indigo-950' : 'text-gray-700'">
                <span>{{ $hasFile ? 'Remplacer le document joint (Max 5 Mo)' : 'Téléverser le document PDF/Image (Max 5 Mo)' }}</span>
              </label>

              <input type="file" 
                     id="file_input_{{ $piece->id }}" 
                     name="files[{{ $piece->id }}]" 
                     accept=".pdf,.jpg,.jpeg,.png"
                     @change="handleFileChange({{ $piece->id }}, $event)"
                     class="block w-full text-xs text-gray-700
                            file:mr-3 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:text-xs file:font-black file:uppercase file:tracking-wider
                            file:bg-indigo-700 file:text-white hover:file:bg-indigo-800
                            file:cursor-pointer cursor-pointer
                            rounded-xl border border-indigo-300 bg-white p-1.5 shadow-2xs">
            </div>

          </div>
        @endforeach
      </div>
    </div>


    <!-- Actions de validation -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 flex items-center justify-end gap-3">
      <a href="{{ route('personnels.show', $personnel) }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        Annuler
      </a>
      <button type="submit" class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
        Enregistrer les modifications
      </button>
    </div>

  </form>

</div>
@endsection
