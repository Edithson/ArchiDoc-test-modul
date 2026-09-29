@extends('admin.layout.app')

@section('title', 'Journal de Sécurité & Accès — ArchiDoc DGB')
@section('meta_description', 'Suivi avancé de la sécurité, des tentatives d\'authentification, connexions et déconnexions de la plateforme.')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-purple-100 text-purple-700 shadow-xs">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </span>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Boîte Noire — Sécurité & Accès</h1>
      </div>
      <p class="mt-1 text-sm text-gray-500">Supervision dédiée des accès, tentatives de connexion, déconnexions et sécurité des sessions.</p>
    </div>

    <div>
      <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 shadow-xs hover:bg-gray-50">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour à l'Arbre complet
      </a>
    </div>
  </div>

  <!-- Cartes KPI Sécurité -->
  <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Événements Sécurité -->
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Audit Sécurité</p>
        <span class="rounded-lg bg-purple-50 p-2 text-purple-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($totalAuthCount) }}</p>
      <p class="mt-1 text-xs text-purple-600 font-semibold">Traçabilité complète des sessions</p>
    </div>

    <!-- Connexions Réussies -->
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Connexions Réussies</p>
        <span class="rounded-lg bg-emerald-50 p-2 text-emerald-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($successfulLoginsCount) }}</p>
      <p class="mt-1 text-xs text-emerald-600 font-semibold">Authentifications autorisées</p>
    </div>

    <!-- Échecs de Connexion -->
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Échecs de Connexion</p>
        <span class="rounded-lg bg-rose-50 p-2 text-rose-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($failedLoginsCount) }}</p>
      <p class="mt-1 text-xs text-rose-600 font-semibold">Tentatives rejetées</p>
    </div>

    <!-- Verrouillages (Lockouts) -->
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs transition hover:shadow-md">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Verrouillages / Alerte</p>
        <span class="rounded-lg bg-amber-50 p-2 text-amber-700">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </span>
      </div>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($lockoutsCount) }}</p>
      <p class="mt-1 text-xs text-amber-600 font-semibold">Trop de tentatives infructueuses</p>
    </div>
  </div>

  <!-- Filtre de recherche Sécurité -->
  <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <form method="GET" action="{{ route('activity-logs.auth') }}" class="p-4 sm:p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div>
          <label for="search" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Recherche utilisateur / IP</label>
          <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nom, email, matricule..."
            class="mt-1.5 block w-full rounded-xl border-gray-300 text-sm focus:border-brand-600 focus:ring-brand-600">
        </div>
        <div>
          <label for="date_range" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Période</label>
          <select name="date_range" id="date_range" class="mt-1.5 block w-full rounded-xl border-gray-300 text-sm focus:border-brand-600 focus:ring-brand-600">
            <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>Toutes les dates</option>
            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
            <option value="7days" {{ $dateRange === '7days' ? 'selected' : '' }}>7 derniers jours</option>
            <option value="30days" {{ $dateRange === '30days' ? 'selected' : '' }}>30 derniers jours</option>
          </select>
        </div>
        <div class="flex items-end gap-2">
          <button type="submit" class="w-full rounded-xl bg-purple-700 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-purple-800">
            Filtrer
          </button>
          <a href="{{ route('activity-logs.auth') }}" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-100">
            Effacer
          </a>
        </div>
      </div>
    </form>
  </div>

  <!-- Tableau des accès sécurité -->
  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-100 p-4 sm:px-6">
      <h2 class="text-base font-bold text-gray-900">Registre des accès & Authentifications</h2>
    </div>

    @if($activities->isEmpty())
      <div class="py-12 text-center">
        <p class="text-sm font-bold text-gray-600">Aucun événement de sécurité enregistré.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
          <thead class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500">
            <tr>
              <th scope="col" class="px-6 py-3">Événement</th>
              <th scope="col" class="px-6 py-3">Utilisateur</th>
              <th scope="col" class="px-6 py-3">Adresse IP</th>
              <th scope="col" class="px-6 py-3">Navigateur / User-Agent</th>
              <th scope="col" class="px-6 py-3">Horodatage</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
            @foreach($activities as $activity)
              @php
                $properties = $activity->properties ?? [];
                $ip = $properties['ip'] ?? '—';
                $userAgent = $properties['user_agent'] ?? '—';
                $event = $activity->event ?? 'auth';
                
                $badgeClass = 'bg-purple-100 text-purple-800 border-purple-200';
                if ($event === 'auth.login') {
                    $badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                } elseif ($event === 'auth.failed_login') {
                    $badgeClass = 'bg-rose-100 text-rose-800 border-rose-200';
                } elseif ($event === 'auth.logout') {
                    $badgeClass = 'bg-gray-100 text-gray-800 border-gray-200';
                } elseif ($event === 'auth.lockout') {
                    $badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                }
              @endphp
              <tr class="hover:bg-gray-50/80">
                <td class="whitespace-nowrap px-6 py-4">
                  <span class="inline-flex rounded-md border px-2.5 py-1 text-xs font-bold {{ $badgeClass }}">
                    {{ $activity->description }}
                  </span>
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                  <div class="font-bold text-gray-900">
                    {{ $activity->causer ? $activity->causer->name : ($properties['email'] ?? 'Visiteur') }}
                  </div>
                  @if($activity->causer && $activity->causer->email)
                    <div class="text-xs text-gray-500">{{ $activity->causer->email }}</div>
                  @endif
                </td>
                <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-gray-700">
                  {{ $ip }}
                </td>
                <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate" title="{{ $userAgent }}">
                  {{ $userAgent }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-xs text-gray-500">
                  <div class="font-semibold text-gray-900">{{ $activity->created_at->format('d/m/Y H:i:s') }}</div>
                  <div class="text-[11px] text-gray-400">{{ $activity->created_at->diffForHumans() }}</div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="border-t border-gray-100 p-4">
        {{ $activities->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
