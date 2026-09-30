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
        <a href="{{ route('archives.download', $archive) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700">
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

          <!-- Grille de Numérisation, Création & Modification -->
          <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3 flex items-center justify-between">
              <span>Métadonnées d'Auteur & Traçabilité</span>
              <span class="text-[10px] font-mono text-gray-400">ID #{{ $archive->id }}</span>
            </h3>
            <div class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
              <div>
                <span class="block text-xs text-gray-400">Date & Auteur de Création</span>
                <span class="font-bold text-gray-800">
                  {{ $archive->created_at ? $archive->created_at->format('d/m/Y à H:i') : 'Inconnue' }}
                </span>
                <span class="block text-xs text-gray-500 mt-0.5">
                  par <strong class="text-gray-700">{{ $archive->creator?->name ?? $archive->user?->name ?? 'Système' }}</strong>
                </span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Dernière Modification</span>
                <span class="font-bold text-gray-800">
                  {{ $archive->updated_at ? $archive->updated_at->format('d/m/Y à H:i') : 'Inconnue' }}
                </span>
                <span class="block text-xs text-gray-500 mt-0.5">
                  par <strong class="text-gray-700">{{ $archive->updater?->name ?? $archive->creator?->name ?? $archive->user?->name ?? 'Système' }}</strong>
                </span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Emplacement Virtuel</span>
                <span class="font-semibold text-gray-800">{{ $archive->emplacement2 ?? 'Serveur' }}</span>
              </div>
              <div>
                <span class="block text-xs text-gray-400">Groupe d'accès (Direction)</span>
                <span class="font-semibold text-gray-800">{{ $archive->departement ?? 'Public' }}</span>
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

  <!-- ==================== SECTION COMPTEURS ET HISTORIQUE D'ACTIONS ==================== -->
  <div class="mt-8 space-y-6">

    <!-- En-tête de section avec compteurs KPI -->
    <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm">
      <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-4">
        <div>
          <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
            <svg class="h-5 w-5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Historique & Traçabilité des Actions sur l'Archive
          </h2>
          <p class="text-xs text-gray-500 font-medium">Bilan statistique et journal individuel des accès, téléchargements et modifications pour ce document.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1 text-xs font-bold text-brand-800 border border-brand-200">
          <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
          {{ number_format($stats['total']) }} action(s) enregistrée(s)
        </span>
      </div>

      <!-- Grille des 4 Compteurs KPI -->
      <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-3.5">
          <span class="block text-[11px] font-bold uppercase tracking-wider text-sky-700">Consultations</span>
          <div class="mt-1 flex items-center justify-between">
            <span class="text-2xl font-black text-sky-900">{{ number_format($stats['consultations']) }}</span>
            <span class="rounded-lg bg-sky-100 p-2 text-sky-700">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </span>
          </div>
        </div>

        <div class="rounded-xl border border-teal-100 bg-teal-50/50 p-3.5">
          <span class="block text-[11px] font-bold uppercase tracking-wider text-teal-700">Téléchargements</span>
          <div class="mt-1 flex items-center justify-between">
            <span class="text-2xl font-black text-teal-900">{{ number_format($stats['downloads']) }}</span>
            <span class="rounded-lg bg-teal-100 p-2 text-teal-700">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </span>
          </div>
        </div>

        <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-3.5">
          <span class="block text-[11px] font-bold uppercase tracking-wider text-indigo-700">Modifications / Éditions</span>
          <div class="mt-1 flex items-center justify-between">
            <span class="text-2xl font-black text-indigo-900">{{ number_format($stats['updates']) }}</span>
            <span class="rounded-lg bg-indigo-100 p-2 text-indigo-700">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </span>
          </div>
        </div>

        <div class="rounded-xl border border-brand-100 bg-brand-50/50 p-3.5">
          <span class="block text-[11px] font-bold uppercase tracking-wider text-brand-700">Total d'opérations</span>
          <div class="mt-1 flex items-center justify-between">
            <span class="text-2xl font-black text-brand-900">{{ number_format($stats['total']) }}</span>
            <span class="rounded-lg bg-brand-100 p-2 text-brand-700">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Tableau d'Historique des Actions -->
    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm">
      @if($activities->isEmpty())
        <div class="py-10 text-center">
          <p class="text-sm font-bold text-gray-700">Aucune activité enregistrée sur ce document pour le moment.</p>
          <p class="text-xs text-gray-400 mt-0.5">Les consultations, téléchargements et modifications futures s'afficheront automatiquement ici.</p>
        </div>
      @else
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="border-b border-gray-100 bg-gray-50/70 text-gray-500 uppercase tracking-wider font-bold">
              <tr>
                <th scope="col" class="px-5 py-3">Horodatage</th>
                <th scope="col" class="px-5 py-3">Événement</th>
                <th scope="col" class="px-5 py-3">Auteur / Agent</th>
                <th scope="col" class="px-5 py-3">Description de l'opération</th>
                <th scope="col" class="px-5 py-3 text-right">Adresse IP</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
              @foreach($activities as $act)
                @php
                  $ev = $act->event ?? 'action';
                  $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';

                  if (str_contains($ev, 'consultation')) {
                      $badgeClass = 'bg-sky-50 text-sky-700 border-sky-200';
                  } elseif (str_contains($ev, 'download')) {
                      $badgeClass = 'bg-teal-50 text-teal-700 border-teal-200';
                  } elseif (str_contains($ev, 'created')) {
                      $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                  } elseif (str_contains($ev, 'updated')) {
                      $badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                  } elseif (str_contains($ev, 'deleted')) {
                      $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                  }
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                  <td class="px-5 py-3.5 whitespace-nowrap font-medium text-gray-900">
                    <div>{{ $act->created_at->format('d/m/Y H:i:s') }}</div>
                    <div class="text-[10px] text-gray-400 font-normal">{{ $act->created_at->diffForHumans() }}</div>
                  </td>
                  <td class="px-5 py-3.5 whitespace-nowrap">
                    <span class="inline-flex rounded-md border px-2 py-0.5 text-[11px] font-bold capitalize {{ $badgeClass }}">
                      {{ $ev }}
                    </span>
                  </td>
                  <td class="px-5 py-3.5 whitespace-nowrap font-bold text-gray-800">
                    {{ $act->causer ? $act->causer->name : 'Système Automatique' }}
                    @if($act->causer && $act->causer->matricule)
                      <span class="font-mono text-[10px] font-normal text-gray-400 block">({{ $act->causer->matricule }})</span>
                    @endif
                  </td>
                  <td class="px-5 py-3.5 text-gray-600 max-w-md truncate" title="{{ $act->description }}">
                    {{ $act->description }}
                  </td>
                  <td class="px-5 py-3.5 whitespace-nowrap text-right font-mono text-[11px] text-gray-500">
                    {{ $act->properties['ip'] ?? '-' }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if($activities->hasPages())
          <div class="border-t border-gray-100 bg-gray-50/50 px-5 py-3">
            {{ $activities->appends(request()->except('activity_page'))->links() }}
          </div>
        @endif
      @endif
    </div>

  </div>

</div>
@endsection
