@extends('admin.layout.app')

@section('title', 'Diagnostic & Erreurs Système — ArchiDoc DGB')
@section('meta_description', 'Journal d\'exceptions et diagnostics d\'erreurs système capturés par la boîte noire d\'ArchiDoc DGB.')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2.5">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 text-white shadow-md shadow-amber-700/20">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </span>
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Boîte Noire — Diagnostic System & Erreurs</h1>
          <p class="text-xs text-gray-500 font-medium">Journalisation automatique des exceptions, pannes et alertes techniques du système.</p>
        </div>
      </div>
    </div>

    <!-- Actions & Export -->
    <div class="flex items-center gap-2.5">
      <!-- Dropdown Exportation -->
      <div class="relative inline-block text-left" x-data="{ open: false }">
        <button type="button" @click="open = !open" @click.away="open = false" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-700/20 hover:from-emerald-700 hover:to-emerald-800 transition-all focus:outline-none">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
          Exporter
          <svg class="h-3.5 w-3.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>

        <div x-show="open" x-transition class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-xl border border-gray-100 bg-white p-2 shadow-xl ring-1 ring-black/5" style="display: none;">
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['log_name' => 'system', 'format' => 'csv'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">
            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-extrabold text-emerald-800">CSV</span>
            Export Excel
          </a>
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['log_name' => 'system', 'format' => 'json'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-800">
            <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-extrabold text-blue-800">JSON</span>
            Format JSON
          </a>
          <a href="{{ route('activity-logs.export', array_merge(request()->query(), ['log_name' => 'system', 'format' => 'txt'])) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-100 hover:text-gray-900">
            <span class="rounded bg-gray-200 px-1.5 py-0.5 text-[10px] font-extrabold text-gray-800">TXT</span>
            Fichier Texte
          </a>
        </div>
      </div>

      <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-xs hover:bg-gray-50">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour à l'Arbre
      </a>
    </div>
  </div>

  <!-- Cartes KPI Erreurs -->
  <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
    <!-- Total Erreurs Système -->
    <div class="rounded-2xl border border-amber-200 bg-amber-50/40 p-5 shadow-sm transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-amber-900">Total Erreurs Capturées</p>
        <span class="rounded-xl bg-amber-100 p-2.5 text-amber-800 shadow-xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-amber-950">{{ number_format($totalSystemErrors) }}</p>
      <p class="mt-1 text-xs text-amber-800 font-semibold">Exceptions non gérées ou interceptées</p>
    </div>

    <!-- Erreurs Aujourd'hui -->
    <div class="rounded-2xl border border-rose-200 bg-rose-50/40 p-5 shadow-sm transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-rose-900">Erreurs Aujourd'hui</p>
        <span class="rounded-xl bg-rose-100 p-2.5 text-rose-800 shadow-xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-rose-950">{{ number_format($todayErrorsCount) }}</p>
      <p class="mt-1 text-xs text-rose-800 font-semibold">Anomalies dans les dernières 24 heures</p>
    </div>
  </div>

  <!-- Filtre de recherche Erreurs -->
  <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm">
    <form method="GET" action="{{ route('activity-logs.system') }}" class="p-4 sm:p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div>
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Recherche dans les erreurs</label>
          <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Class, fichier, message d'erreur..."
            class="form-input-styled block w-full">
        </div>
        <div>
          <label for="date_range" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Période</label>
          <select name="date_range" id="date_range" class="form-select-styled block w-full">
            <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>Toutes les dates</option>
            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
            <option value="7days" {{ $dateRange === '7days' ? 'selected' : '' }}>7 derniers jours</option>
            <option value="30days" {{ $dateRange === '30days' ? 'selected' : '' }}>30 derniers jours</option>
          </select>
        </div>
        <div class="flex items-end gap-2">
          <button type="submit" class="w-full rounded-xl bg-amber-700 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-amber-800">
            Filtrer
          </button>
          <a href="{{ route('activity-logs.system') }}" class="rounded-xl border border-gray-300 px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100">
            Effacer
          </a>
        </div>
      </div>
    </form>
  </div>

  <!-- Liste des Erreurs Système -->
  <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm">
    <div class="border-b border-gray-100 p-4 sm:px-6">
      <h2 class="text-base font-bold text-gray-900">Registre des anomalies & Exceptions</h2>
    </div>

    @if($activities->isEmpty())
      <div class="py-12 text-center">
        <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-800">Aucune erreur système à déceler !</p>
        <p class="mt-1 text-xs text-gray-500">La plateforme fonctionne normalement sans anomalie enregistrée.</p>
      </div>
    @else
      <div class="divide-y divide-gray-100">
        @foreach($activities as $activity)
          @php
            $properties = $activity->properties ?? [];
            $exceptionClass = $properties['exception_class'] ?? 'Exception';
            $file = $properties['file'] ?? '—';
            $line = $properties['line'] ?? '—';
            $trace = $properties['trace'] ?? [];
          @endphp
          <div class="p-4 sm:p-6 hover:bg-amber-50/30 transition">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <div class="flex items-center gap-2">
                  <span class="rounded bg-rose-100 px-2 py-0.5 font-mono text-xs font-bold text-rose-800">
                    {{ class_basename($exceptionClass) }}
                  </span>
                  <span class="text-xs text-gray-400 font-mono">{{ $file }}:{{ $line }}</span>
                </div>
                <h3 class="mt-2 text-sm font-bold text-gray-900">{{ $activity->description }}</h3>
              </div>
              <div class="text-xs text-gray-500 shrink-0">
                <span class="font-semibold text-gray-900">{{ $activity->created_at->format('d/m/Y H:i:s') }}</span>
                <div>{{ $activity->created_at->diffForHumans() }}</div>
              </div>
            </div>

            @if(!empty($trace))
              <div class="mt-3">
                <details class="group">
                  <summary class="cursor-pointer text-xs font-bold text-amber-700 hover:text-amber-900">
                    Afficher la pile d'exécution (Stack trace - {{ count($trace) }} frames)
                  </summary>
                  <pre class="styled-scroll mt-2 max-h-48 overflow-x-auto rounded-lg bg-gray-900 p-3 font-mono text-xs text-gray-200">@foreach(array_slice($trace, 0, 10) as $frame){{ $frame }}
@endforeach</pre>
                </details>
              </div>
            @endif
          </div>
        @endforeach
      </div>

      <div class="border-t border-gray-100 p-4">
        {{ $activities->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
