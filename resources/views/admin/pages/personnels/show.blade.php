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
        <a href="{{ route('personnels.download-zip', $personnel) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-2xs transition-all">
          <svg class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
          </svg>
          Télécharger le dossier (.zip)
        </a>

        <a href="{{ route('personnels.edit', $personnel) }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-brand-800 transition-all">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
          </svg>
          Compléter le dossier
        </a>
      </div>

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

</div>
@endsection
