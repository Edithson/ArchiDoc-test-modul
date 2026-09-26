@extends('admin.layout.app')

@section('title', 'Gestion du compte — ArchiDoc DGB')
@section('meta_description', 'Gestion des informations personnelles, matricule et mot de passe de l\'utilisateur courant — ArchiDoc DGB')

@section('content')
<div class="mx-auto max-w-[1200px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-8">
    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-700 mb-1">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      Compte Utilisateur
    </div>
    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Profil & Sécurité</h1>
    <p class="mt-1 text-sm text-gray-500">Mettez à jour vos informations de compte, vos préférences et votre mot de passe d'accès.</p>
  </div>

  <div class="space-y-8">

    <!-- Card 1: Information de profil -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
          <svg class="h-5 w-5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6"/>
          </svg>
          Informations Personnelles & Professionnelles
        </h2>
      </div>

      <div class="p-6 sm:p-8">
        @include('profile.partials.update-profile-information-form')
      </div>
    </div>

    <!-- Card 2: Modification de mot de passe -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
          <svg class="h-5 w-5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
          Sécurité & Mot de Passe
        </h2>
      </div>

      <div class="p-6 sm:p-8">
        @include('profile.partials.update-password-form')
      </div>
    </div>

  </div>

</div>
@endsection
