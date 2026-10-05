@extends('admin.layout.app')

@section('title', "Dossier de {$personnel->name} — ArchiDoc DGB")
@section('meta_description', "Consultation et bilan de complétude du dossier d'intégration de {$personnel->name} ({$personnel->matricule}) — ArchiDoc DGB")

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- Fil d'ariane -->
  <nav class="mb-4 flex items-center gap-2 text-xs font-medium text-gray-500">
    <a href="{{ route('archives.index') }}" class="hover:text-brand-700">Accueil</a>
    <span>/</span>
    <a href="{{ route('personnels.index') }}" class="hover:text-brand-700">Dossiers du personnel</a>
    <span>/</span>
    <span class="text-gray-900 font-bold">{{ $personnel->matricule }}</span>
  </nav>

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

  @if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-800 flex items-center gap-2 shadow-xs" role="alert">
      <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
      </svg>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <!-- Carte d'identité de l'agent -->
  <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xs p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      
      <div class="flex items-center gap-4">
        <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-brand-700 text-2xl font-black text-white shadow-md">
          {{ strtoupper(substr($personnel->name, 0, 1)) }}
        </span>
        <div>
          <div class="flex items-center gap-3">
            <h1 class="text-2xl font-black tracking-tight text-gray-900">{{ $personnel->name }}</h1>
            <span class="rounded-lg bg-brand-100 px-2.5 py-1 font-mono text-xs font-bold text-brand-900">
              {{ $personnel->matricule }}
            </span>
          </div>
          <div class="mt-1.5 flex flex-wrap items-center gap-2">
            @if($personnel->department)
              <span class="inline-flex items-center rounded border border-purple-200 bg-purple-50 px-2 py-0.5 text-xs font-bold text-purple-700">
                {{ $personnel->department->name }}
              </span>
            @endif
            @if($personnel->subDepartment)
              <span class="inline-flex items-center gap-1 rounded border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[11px] font-semibold text-indigo-700">
                <svg class="h-3 w-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                {{ $personnel->subDepartment->name }}
              </span>
            @elseif($personnel->department)
              <span class="text-[11px] font-normal text-gray-400">Tous les services</span>
            @endif
          </div>
          <div class="mt-1 flex flex-wrap items-center gap-4 text-xs text-gray-500">
            @if($personnel->email)
              <span class="flex items-center gap-1">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $personnel->email }}
              </span>
            @endif
            @if($personnel->phone)
              <span class="flex items-center gap-1 font-mono">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                {{ $personnel->phone }}
              </span>
            @endif
            @if($personnel->address)
              <span class="flex items-center gap-1">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $personnel->address }}
              </span>
            @endif
          </div>
        </div>
      </div>

      <div class="flex flex-wrap items-center gap-2">
        @if(auth()->user()?->hasPermission('Personnel', 'zip_download'))
        <a href="{{ route('personnels.download-zip', $personnel) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-2xs transition-all">
          <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
          </svg>
          Télécharger le dossier (.zip)
        </a>
        @endif

        @if(auth()->user()?->hasPermission('Personnel', 'update'))
        <a href="{{ route('personnels.edit', $personnel) }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 transition-all">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
          </svg>
          Compléter le dossier
        </a>
        @endif
      </div>

    </div>

    <!-- Barre d'Auteur & Traçabilité Métadonnées -->
    <div class="mt-4 border-t border-gray-100 pt-3.5 flex flex-wrap items-center justify-between gap-4 text-xs text-gray-500">
      <div class="flex flex-wrap items-center gap-4">
        <div>
          <span class="text-gray-400">Créé le :</span>
          <strong class="text-gray-800">{{ $personnel->created_at ? $personnel->created_at->format('d/m/Y à H:i') : 'Inconnu' }}</strong>
          <span class="text-gray-500">par {{ $personnel->creator?->name ?? 'Système' }}</span>
        </div>
        <span class="hidden sm:inline">•</span>
        <div>
          <span class="text-gray-400">Dernière modification :</span>
          <strong class="text-gray-800">{{ $personnel->updated_at ? $personnel->updated_at->format('d/m/Y à H:i') : 'Inconnue' }}</strong>
          <span class="text-gray-500">par {{ $personnel->updater?->name ?? $personnel->creator?->name ?? 'Système' }}</span>
        </div>
      </div>
      <span class="font-mono text-[11px] text-gray-400">Agent ID #{{ $personnel->id }}</span>
    </div>
  </div>

  <!-- Jauge de Taux d'Achèvement du Dossier -->
  @php
    $taux = $personnel->taux_achevement;
    $missing = $personnel->missing_obligatory_pieces_count;
    $isComplete = $personnel->is_complete;
  @endphp
  <div class="mb-6 rounded-2xl border p-6 shadow-xs transition-all {{ $isComplete ? 'border-emerald-200 bg-emerald-50/40' : 'border-amber-200 bg-amber-50/40' }}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-lg font-extrabold text-gray-900">Bilan de Complétude du Dossier</h2>
          @if($isComplete)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-3 py-0.5 text-xs font-black uppercase text-white shadow-2xs">
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Dossier Complet
            </span>
          @else
            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-600 px-3 py-0.5 text-xs font-black uppercase text-white shadow-2xs">
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              Dossier Incomplet
            </span>
          @endif
        </div>
        <p class="mt-1 text-xs font-medium text-gray-600">
          @if($isComplete)
            Toutes les pièces d'intégration obligatoires exigées ont été jointes et validées.
          @else
            Il manque actuellement <strong>{{ $missing }} pièce{{ $missing > 1 ? 's' : '' }} obligatoire{{ $missing > 1 ? 's' : '' }}</strong> pour finaliser l'intégration.
          @endif
        </p>
      </div>

      <div class="text-right">
        <span class="text-3xl font-black {{ $taux == 100 ? 'text-emerald-700' : ($taux >= 50 ? 'text-amber-700' : 'text-stone-700') }}">
          {{ $taux }}%
        </span>
        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-500">Taux d'achèvement</span>
      </div>
    </div>

    <!-- Barre de Progression Géante -->
    <div class="h-3 w-full overflow-hidden rounded-full bg-gray-200/80">
      <div class="h-full rounded-full transition-all duration-500 {{ $taux == 100 ? 'bg-emerald-500' : ($taux >= 50 ? 'bg-amber-500' : 'bg-stone-500') }}" style="width: {{ $taux }}%"></div>
    </div>
  </div>


  <!-- BLOCK 1: PIÈCES OBLIGATOIRES (Soft Warm Amber Theme) -->
  <div class="mb-6 rounded-2xl border border-amber-200/80 bg-white overflow-hidden shadow-xs">
    <div class="bg-amber-50/60 border-b border-amber-100 px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100/90 text-amber-900 font-extrabold text-xs">1</span>
        <h3 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider">Block 1 : Pièces Obligatoires</h3>
      </div>
      <span class="text-xs font-bold text-amber-900 bg-amber-100/70 px-2.5 py-0.5 rounded-full border border-amber-200/70">
        {{ $obligatoryPieces->filter(fn($p) => isset($uploadedFiles[$p->id]) && !empty($uploadedFiles[$p->id]->file_paths))->count() }} / {{ $obligatoryPieces->count() }} pièces fournies
      </span>
    </div>

    <div class="divide-y divide-amber-100/50">
      @foreach($obligatoryPieces as $piece)
        @php
          $fileRecord = $uploadedFiles[$piece->id] ?? null;
          $hasFile = $fileRecord && !empty($fileRecord->file_paths);
          $filePath = $hasFile ? $fileRecord->file_paths[0] : null;
        @endphp
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-amber-50/20 transition-colors">
          
          <div class="flex items-start gap-3">
            @if($hasFile)
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              </span>
            @else
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100/80 text-amber-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              </span>
            @endif

            <div>
              <div class="flex items-center gap-2">
                <p class="text-sm font-extrabold text-gray-900">{{ $piece->name }}</p>
                <span class="rounded bg-amber-100/80 px-1.5 py-0.5 text-[10px] font-bold text-amber-900 uppercase border border-amber-200/60">Obligatoire</span>
              </div>
              <p class="mt-0.5 text-xs text-gray-500">{{ $piece->description ?? 'Pièce obligatoire d\'intégration' }}</p>
            </div>
          </div>

          <div class="flex items-center gap-3 self-end sm:self-center">
            @if($hasFile && $filePath)
              <a href="{{ asset('storage/' . $filePath) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-800 hover:bg-emerald-100">
                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Consulter le document
              </a>
            @else
              <span class="text-xs font-bold text-amber-900 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                Pièce Manquante
              </span>
              <a href="{{ route('personnels.edit', $personnel) }}" class="inline-flex items-center gap-1 rounded-xl bg-amber-700 px-3 py-1.5 text-xs font-bold text-white shadow-2xs hover:bg-amber-800">
                Joindre
              </a>
            @endif
          </div>

        </div>
      @endforeach
    </div>
  </div>


  <!-- BLOCK 2: PIÈCES FACULTATIVES (Soft Slate Theme) -->
  <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-xs">
    <div class="bg-slate-50/60 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-800 font-extrabold text-xs">2</span>
        <h3 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider">Block 2 : Pièces Facultatives</h3>
      </div>
      <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-full border border-slate-200">
        {{ $optionalPieces->filter(fn($p) => isset($uploadedFiles[$p->id]) && !empty($uploadedFiles[$p->id]->file_paths))->count() }} / {{ $optionalPieces->count() }} pièces fournies
      </span>
    </div>

    <div class="divide-y divide-slate-100">
      @foreach($optionalPieces as $piece)
        @php
          $fileRecord = $uploadedFiles[$piece->id] ?? null;
          $hasFile = $fileRecord && !empty($fileRecord->file_paths);
          $filePath = $hasFile ? $fileRecord->file_paths[0] : null;
        @endphp
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/30 transition-colors">
          
          <div class="flex items-start gap-3">
            @if($hasFile)
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              </span>
            @else
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
              </span>
            @endif

            <div>
              <div class="flex items-center gap-2">
                <p class="text-sm font-extrabold text-gray-900">{{ $piece->name }}</p>
                <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-bold text-slate-700 uppercase border border-slate-200">Optionnel</span>
              </div>
              <p class="mt-0.5 text-xs text-gray-500">{{ $piece->description ?? 'Pièce d\'accompagnement facultative' }}</p>
            </div>
          </div>

          <div class="flex items-center gap-3 self-end sm:self-center">
            @if($hasFile && $filePath)
              <a href="{{ asset('storage/' . $filePath) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-800 hover:bg-slate-100">
                <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Consulter le document
              </a>
            @else
              <span class="text-xs font-medium text-gray-400 italic">
                Non fournie
              </span>
            @endif
          </div>

        </div>
      @endforeach
    </div>
  </div>

  <!-- ==================== SECTION COMPTEURS ET HISTORIQUE D'ACTIONS ==================== -->
  <div class="mt-8 space-y-6">

    <!-- En-tête de section avec compteurs KPI & Exportation -->
    <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-xs">
      <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-4">
        <div>
          <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
            <svg class="h-5 w-5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Historique & Traçabilité du Dossier Personnel
          </h2>
          <p class="text-xs text-gray-500 font-medium">Bilan statistique et journal individuel des accès, téléchargements et mises à jour pour cet agent.</p>
        </div>

        <div class="flex items-center gap-2.5">
          <!-- Dropdown d'exportation de l'historique de cet agent -->
          <div class="relative inline-block text-left">
            <button id="export-personnel-trigger" type="button" onclick="toggleExportMenuPersonnel()" class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-300 bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-800 hover:bg-emerald-100 transition shadow-2xs focus:outline-none">
              <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              Exporter cet historique
              <svg class="h-3 w-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div id="export-personnel-menu" class="absolute right-0 z-30 mt-1.5 hidden w-48 origin-top-right rounded-xl border border-gray-100 bg-white p-1.5 shadow-xl ring-1 ring-black/5">
              <div class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Choisir le Format</div>
              <a href="{{ route('activity-logs.export', ['subject_type' => get_class($personnel), 'subject_id' => $personnel->id, 'format' => 'csv']) }}" class="flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-xs font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">
                <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-extrabold text-emerald-800">CSV</span>
                Export Excel (CSV)
              </a>
              <a href="{{ route('activity-logs.export', ['subject_type' => get_class($personnel), 'subject_id' => $personnel->id, 'format' => 'json']) }}" class="flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-800">
                <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-extrabold text-blue-800">JSON</span>
                Payload API (JSON)
              </a>
              <a href="{{ route('activity-logs.export', ['subject_type' => get_class($personnel), 'subject_id' => $personnel->id, 'format' => 'txt']) }}" class="flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                <span class="rounded bg-gray-200 px-1.5 py-0.5 text-[10px] font-extrabold text-gray-800">TXT</span>
                Journal Texte (Syslog)
              </a>
            </div>
          </div>

          <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1 text-xs font-bold text-brand-800 border border-brand-200">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            {{ number_format($stats['total']) }} action(s)
          </span>
        </div>
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
          <span class="block text-[11px] font-bold uppercase tracking-wider text-teal-700">Téléchargements ZIP</span>
          <div class="mt-1 flex items-center justify-between">
            <span class="text-2xl font-black text-teal-900">{{ number_format($stats['downloads']) }}</span>
            <span class="rounded-lg bg-teal-100 p-2 text-teal-700">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </span>
          </div>
        </div>

        <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-3.5">
          <span class="block text-[11px] font-bold uppercase tracking-wider text-indigo-700">Modifications / Pièces</span>
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

    <!-- Tableau d'Historique des Actions sur l'agent -->
    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-xs">
      @if($activities->isEmpty())
        <div class="py-10 text-center">
          <p class="text-sm font-bold text-gray-700">Aucune activité enregistrée pour cet agent pour le moment.</p>
          <p class="text-xs text-gray-400 mt-0.5">Les consultations, téléchargements ZIP et mises à jour s'afficheront automatiquement ici.</p>
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
                <th scope="col" class="px-5 py-3">Adresse IP</th>
                <th scope="col" class="px-5 py-3 text-right">Détails</th>
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
                <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer" onclick="inspectActivity({{ $act->id }})">
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
                  <td class="px-5 py-3.5 whitespace-nowrap font-mono text-[11px] text-gray-500">
                    {{ $act->properties['ip'] ?? '-' }}
                  </td>
                  <td class="px-5 py-3.5 whitespace-nowrap text-right" onclick="event.stopPropagation()">
                    <button type="button" onclick="inspectActivity({{ $act->id }})" class="inline-flex items-center gap-1 rounded-lg border border-brand-200 bg-brand-50/70 px-2.5 py-1 text-xs font-bold text-brand-700 hover:bg-brand-100 transition focus:outline-none shadow-2xs">
                      <svg class="h-3.5 w-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                      Inspecter
                    </button>
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

<!-- Modal Inspecteur d'Événement (JSON / Diff) -->
<div id="inspector-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-xs transition-opacity" aria-modal="true" role="dialog">
  <div class="flex min-h-screen items-center justify-center p-4">
    <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl transition-all">
      
      <!-- En-tête du Modal -->
      <div class="flex items-center justify-between border-b border-gray-100 bg-brand-900 px-6 py-4 text-white">
        <div class="flex items-center gap-2.5">
          <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-700 text-brand-200">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
          </span>
          <div>
            <h3 class="text-base font-bold text-white" id="modal-title">Inspection de l'Événement</h3>
            <p class="text-xs text-brand-200" id="modal-subtitle">Variations détaillées et données système</p>
          </div>
        </div>
        <button type="button" onclick="closeInspectorModal()" class="rounded-lg p-1.5 text-brand-200 hover:bg-brand-800 hover:text-white focus:outline-none">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <!-- Corps du Modal -->
      <div class="p-6 space-y-6">
        
        <div class="grid grid-cols-2 gap-4 rounded-xl bg-gray-50 p-4 text-xs sm:grid-cols-4">
          <div>
            <span class="block font-bold text-gray-500 uppercase">Auteur</span>
            <span class="font-bold text-gray-900" id="modal-causer">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Horodatage</span>
            <span class="font-bold text-gray-900" id="modal-date">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Catégorie</span>
            <span class="font-bold text-gray-900" id="modal-logname">—</span>
          </div>
          <div>
            <span class="block font-bold text-gray-500 uppercase">Entité</span>
            <span class="font-bold text-gray-900" id="modal-subject">—</span>
          </div>
        </div>

        <!-- Section Diff de variation -->
        <div id="diff-section" class="hidden space-y-3">
          <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
            <svg class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Différence des Données (Anciennes vs Nouvelles Valeurs)
          </h4>
          <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-rose-200 bg-rose-50/50 p-4">
              <span class="block mb-2 text-xs font-bold uppercase text-rose-800">Ancienne Valeur (Avant)</span>
              <pre id="modal-old-json" class="styled-scroll max-h-60 overflow-x-auto rounded-lg bg-white p-3 font-mono text-xs text-rose-900 shadow-xs border border-rose-100">{}</pre>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-4">
              <span class="block mb-2 text-xs font-bold uppercase text-emerald-800">Nouvelle Valeur (Après)</span>
              <pre id="modal-new-json" class="styled-scroll max-h-60 overflow-x-auto rounded-lg bg-white p-3 font-mono text-xs text-emerald-900 shadow-xs border border-emerald-100">{}</pre>
            </div>
          </div>
        </div>

        <div class="space-y-2">
          <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700">Payload Complet & Métadonnées Propriétés (JSON)</h4>
          <pre id="modal-properties-json" class="styled-scroll max-h-72 overflow-x-auto rounded-xl bg-gray-900 p-4 font-mono text-xs text-emerald-400 shadow-inner">{}</pre>
        </div>

      </div>

      <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-6 py-3">
        <button type="button" onclick="closeInspectorModal()" class="rounded-xl bg-gray-200 px-5 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300">
          Fermer
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function toggleExportMenuPersonnel() {
  const menu = document.getElementById('export-personnel-menu');
  menu.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
  const trigger = document.getElementById('export-personnel-trigger');
  const menu = document.getElementById('export-personnel-menu');
  if (trigger && menu && !trigger.contains(e.target) && !menu.contains(e.target)) {
    menu.classList.add('hidden');
  }
});

function inspectActivity(activityId) {
  fetch(`/activity-logs/${activityId}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById('modal-title').textContent = data.description || 'Inspection de l\'Événement';
      document.getElementById('modal-subtitle').textContent = `ID Événement #${data.id} • ${data.event || 'action'}`;
      document.getElementById('modal-causer').textContent = data.causer ? `${data.causer.name} (${data.causer.matricule})` : 'Système';
      document.getElementById('modal-date').textContent = `${data.created_at} (${data.created_at_human})`;
      document.getElementById('modal-logname').textContent = data.log_name || 'default';
      document.getElementById('modal-subject').textContent = data.subject ? `${data.subject.type} #${data.subject.id}` : 'Système';

      const properties = data.properties || {};
      const diffSection = document.getElementById('diff-section');
      
      if (properties.old || properties.attributes) {
        diffSection.classList.remove('hidden');
        document.getElementById('modal-old-json').textContent = JSON.stringify(properties.old || {}, null, 2);
        document.getElementById('modal-new-json').textContent = JSON.stringify(properties.attributes || {}, null, 2);
      } else {
        diffSection.classList.add('hidden');
      }

      document.getElementById('modal-properties-json').textContent = JSON.stringify(properties, null, 2);

      const modal = document.getElementById('inspector-modal');
      modal.classList.remove('hidden');
    })
    .catch(error => {
      alert('Erreur lors du chargement des détails de l\'événement.');
      console.error(error);
    });
}

function closeInspectorModal() {
  document.getElementById('inspector-modal').classList.add('hidden');
}
</script>
@endsection
