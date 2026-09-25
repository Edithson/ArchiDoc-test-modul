@extends('admin.layout.app')

@section('title', 'Consultation archive — ' . ($archive->typearchive ?? 'ArchiDoc'))
@section('meta_description', 'Consultation détaillée et visualisation du document d\'archive — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page & Actions -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2">
        <a href="{{ route('archives.search') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-brand-700 hover:text-brand-800">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Retour aux recherches
        </a>
      </div>
      <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">
        {{ $archive->typearchive ?? 'Archive' }}
      </h1>
      <p class="text-sm text-gray-500">Détails de classement et visualisation intégrée du document numérique.</p>
    </div>

    <div class="flex items-center gap-3">
      @if($archive->filepath)
        <a href="{{ asset('storage/' . $archive->filepath) }}" target="_blank" download class="inline-flex items-center gap-2 rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
          </svg>
          Télécharger le document
        </a>
      @endif
    </div>
  </div>

  <!-- Structure 2 Colonnes -->
  <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 items-start">

    <!-- ==================== COLONNE GAUCHE : MÉTADONNÉES ==================== -->
    <div class="lg:col-span-6 space-y-6">

      <!-- Fiche d'Information Principale -->
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
          <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
            <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Informations Générales
          </h2>
        </div>

        <div class="p-6 space-y-6">

          <!-- Badges Type et Format -->
          <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center rounded-lg bg-brand-100 px-3 py-1 text-xs font-bold text-brand-800">
              {{ $archive->typearchive ?? 'N/A' }}
            </span>
            <span class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-700">
              Format: {{ $archive->format ?? 'Standard' }}
            </span>
            @if($archive->date_doc)
              <span class="inline-flex items-center rounded-lg bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800 border border-emerald-200">
                Signé le {{ \Carbon\Carbon::parse($archive->date_doc)->format('d/m/Y') }}
              </span>
            @endif
          </div>

          <!-- Objet -->
          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Objet de l'archive</h3>
            <p class="mt-1 text-base font-semibold text-gray-900 leading-relaxed">
              {{ $archive->description }}
            </p>
          </div>

          <!-- Grille de Classement Physique -->
          <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Classement Physique</h3>
            <div class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
              <div>
                <span class="block text-xs text-gray-400">Emplacement</span>
                <span class="font-bold text-gray-800">{{ $archive->emplacement ?? 'N/A' }}</span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Rayon</span>
                <span class="font-bold text-gray-800">{{ $archive->rayon ?? '-' }}</span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Travée</span>
                <span class="font-bold text-gray-800">{{ $archive->travee ?? '-' }}</span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Cote de boîte</span>
                <span class="font-bold text-brand-700">{{ $archive->cote ?? '-' }}</span>
              </div>
            </div>
          </div>

          <!-- Grille de Numérisation & Accès -->
          <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Accès & Métadonnées Système</h3>
            <div class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
              <div>
                <span class="block text-xs text-gray-400">Emplacement Virtuel</span>
                <span class="font-semibold text-gray-800">{{ $archive->emplacement2 ?? 'Serveur' }}</span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Groupe d'accès (Direction)</span>
                <span class="font-semibold text-gray-800">{{ $archive->departement ?? 'Public' }}</span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Date d'enregistrement</span>
                <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($archive->created_at)->format('d/m/Y H:i') }}</span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Opérateur</span>
                <span class="font-semibold text-gray-800">Administrateur</span>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- ==================== COLONNE DROITE : VISUALISATION DU DOCUMENT ==================== -->
    <div class="lg:col-span-6 lg:sticky lg:top-20 space-y-4">

      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        
        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 px-5 py-3.5">
          <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <h3 class="text-sm font-bold text-gray-900">Aperçu du document d'archive</h3>
          </div>
          @if($archive->filepath)
            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
              Fichier Numérisé
            </span>
          @else
            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
              Sans Fichier Joint
            </span>
          @endif
        </div>

        <div class="relative flex min-h-[550px] lg:min-h-[660px] flex-col items-center justify-center bg-gray-100/70 p-3">
          @if($archive->filepath)
            @php
              $extension = strtolower(pathinfo($archive->filepath, PATHINFO_EXTENSION));
              $fileUrl = asset('storage/' . $archive->filepath);
            @endphp

            @if(in_array($extension, ['pdf']))
              <iframe src="{{ $fileUrl }}" class="h-[530px] lg:h-[640px] w-full rounded-lg border border-gray-200 bg-white shadow-inner" title="Document PDF"></iframe>
            @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
              <div class="flex h-full w-full items-center justify-center overflow-auto p-2">
                <img src="{{ $fileUrl }}" alt="Document {{ $archive->description }}" class="max-h-[530px] lg:max-h-[640px] w-auto max-w-full rounded-lg object-contain shadow-md border border-gray-200">
              </div>
            @else
              <div class="flex flex-col items-center justify-center text-center p-6">
                <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-700">
                  <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <h4 class="text-sm font-bold text-gray-900">Document attaché ({{ strtoupper($extension) }})</h4>
                <p class="mt-1 text-xs text-gray-500 mb-4">L'aperçu direct est indisponible pour cette extension de fichier.</p>
                <a href="{{ $fileUrl }}" download class="inline-flex items-center gap-2 rounded-lg bg-brand-700 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-brand-800">
                  Télécharger le fichier
                </a>
              </div>
            @endif
          @else
            <div class="flex flex-col items-center justify-center text-center p-6 max-w-xs">
              <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-sm text-gray-400">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <h4 class="text-sm font-bold text-gray-800">Aucun fichier numérique joint</h4>
              <p class="mt-1 text-xs text-gray-500 leading-relaxed">
                Cette fiche d'archive est enregistrée avec sa référence physique uniquement.
              </p>
            </div>
          @endif
        </div>

        @if($archive->filepath)
          <div class="border-t border-gray-100 bg-white px-4 py-2.5 flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-700 truncate max-w-[220px]">
              {{ basename($archive->filepath) }}
            </span>
            <a href="{{ asset('storage/' . $archive->filepath) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-brand-700 hover:text-brand-800 underline">
              Ouvrir dans un nouvel onglet
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
          </div>
        @endif

      </div>

    </div>

  </div>

</div>
@endsection
