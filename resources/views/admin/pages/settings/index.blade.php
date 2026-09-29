@extends('admin.layout.app')

@section('title', 'Paramètres de l\'Application — ArchiDoc DGB')
@section('meta_description', 'Configuration et personnalisation des paramètres d\'identité, de branding, d\'archivage et de sécurité.')

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

  <!-- En-tête de page -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="flex items-center gap-2.5">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 text-white shadow-md shadow-brand-700/20">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </span>
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Paramètres du Système</h1>
          <p class="text-xs text-gray-500 font-medium">Personnalisation de l'identité visuelle, des règles d'archivage et de la sécurité de la plateforme.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Messages Flash Success / Error -->
  @if(session('success'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 text-sm font-bold text-emerald-800 flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2.5">
        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50/80 p-4 text-sm font-bold text-rose-800 shadow-xs">
      <div class="flex items-center gap-2 mb-2">
        <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span>Des erreurs de validation ont été détectées :</span>
      </div>
      <ul class="list-disc pl-8 text-xs font-semibold text-rose-700 space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Formulaire Général des Paramètres -->
  <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="space-y-6">
      
      <!-- Section 1 : Identité & Branding de l'Organisation -->
      <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm">
        <div class="border-b border-gray-100 bg-gray-50/60 px-6 py-4 flex items-center gap-2">
          <svg class="h-5 w-5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0v-4a1 1 0 011-1h2a1 1 0 011 1v4m-4 0h4"/></svg>
          <h2 class="text-base font-extrabold text-gray-900">Identité & Branding Institutionnel</h2>
        </div>

        <div class="p-6 space-y-6">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            
            <!-- Nom du logiciel -->
            <div>
              <label for="app_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Nom de l'application</label>
              <input type="text" name="app_name" id="app_name" value="{{ setting('branding.app_name', 'ArchiDoc') }}" class="form-input-styled block w-full">
              <p class="mt-1 text-[11px] text-gray-400">Nom principal affiché dans l'en-tête et les titres.</p>
            </div>

            <!-- Nom de la structure -->
            <div>
              <label for="structure_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Nom de l'organisation / Structure</label>
              <input type="text" name="structure_name" id="structure_name" value="{{ setting('branding.structure_name', 'Direction Générale du Budget') }}" class="form-input-styled block w-full">
              <p class="mt-1 text-[11px] text-gray-400">Raison sociale complète de l'institution réceptrice.</p>
            </div>

            <!-- Sigle / Acronyme -->
            <div>
              <label for="structure_acronym" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Sigle / Acronyme</label>
              <input type="text" name="structure_acronym" id="structure_acronym" value="{{ setting('branding.structure_acronym', 'DGB') }}" class="form-input-styled block w-full">
              <p class="mt-1 text-[11px] text-gray-400">Diminutif ou acronyme officiel (ex: DGB).</p>
            </div>

          </div>

          <!-- Section Charte Graphique & Couleurs par défaut DGB -->
          <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-800 mb-3 flex items-center gap-2">
              <svg class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
              Palette & Couleurs de la Charte Graphique (Par défaut DGB Cameroun)
            </h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
              
              <!-- Couleur Primaire -->
              <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-3">
                <label for="primary_color" class="block text-xs font-bold text-gray-700 mb-1.5">Primaire (Principal)</label>
                <div class="flex items-center gap-2">
                  <input type="color" name="primary_color" id="primary_color" value="{{ setting('branding.primary_color', '#297a75') }}" class="h-9 w-9 rounded-lg border-0 cursor-pointer p-0">
                  <span class="font-mono text-xs font-bold text-gray-800">{{ setting('branding.primary_color', '#297a75') }}</span>
                </div>
              </div>

              <!-- Couleur Secondaire -->
              <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-3">
                <label for="secondary_color" class="block text-xs font-bold text-gray-700 mb-1.5">Secondaire (Sombre)</label>
                <div class="flex items-center gap-2">
                  <input type="color" name="secondary_color" id="secondary_color" value="{{ setting('branding.secondary_color', '#21635f') }}" class="h-9 w-9 rounded-lg border-0 cursor-pointer p-0">
                  <span class="font-mono text-xs font-bold text-gray-800">{{ setting('branding.secondary_color', '#21635f') }}</span>
                </div>
              </div>

              <!-- Couleur Accent -->
              <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-3">
                <label for="accent_color" class="block text-xs font-bold text-gray-700 mb-1.5">Accent (Lumineux)</label>
                <div class="flex items-center gap-2">
                  <input type="color" name="accent_color" id="accent_color" value="{{ setting('branding.accent_color', '#40beb7') }}" class="h-9 w-9 rounded-lg border-0 cursor-pointer p-0">
                  <span class="font-mono text-xs font-bold text-gray-800">{{ setting('branding.accent_color', '#40beb7') }}</span>
                </div>
              </div>

              <!-- Couleur Succès -->
              <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-3">
                <label for="success_color" class="block text-xs font-bold text-gray-700 mb-1.5">Succès (Valide)</label>
                <div class="flex items-center gap-2">
                  <input type="color" name="success_color" id="success_color" value="{{ setting('branding.success_color', '#10b981') }}" class="h-9 w-9 rounded-lg border-0 cursor-pointer p-0">
                  <span class="font-mono text-xs font-bold text-gray-800">{{ setting('branding.success_color', '#10b981') }}</span>
                </div>
              </div>

              <!-- Couleur Erreur -->
              <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-3">
                <label for="error_color" class="block text-xs font-bold text-gray-700 mb-1.5">Erreur (Alerte)</label>
                <div class="flex items-center gap-2">
                  <input type="color" name="error_color" id="error_color" value="{{ setting('branding.error_color', '#f43f5e') }}" class="h-9 w-9 rounded-lg border-0 cursor-pointer p-0">
                  <span class="font-mono text-xs font-bold text-gray-800">{{ setting('branding.error_color', '#f43f5e') }}</span>
                </div>
              </div>

            </div>
          </div>

          <!-- Section Coordonnées & Pied de page -->
          <div class="border-t border-gray-100 pt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div>
              <label for="contact_email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Email de contact</label>
              <input type="email" name="contact_email" id="contact_email" value="{{ setting('branding.contact_email', 'contact@dgb.cm') }}" class="form-input-styled block w-full">
            </div>
            <div>
              <label for="contact_phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Téléphone de contact</label>
              <input type="text" name="contact_phone" id="contact_phone" value="{{ setting('branding.contact_phone', '+237 222 22 00 00') }}" class="form-input-styled block w-full">
            </div>
            <div>
              <label for="contact_address" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Adresse physique</label>
              <input type="text" name="contact_address" id="contact_address" value="{{ setting('branding.contact_address', 'Yaoundé, Cameroun — Ministère des Finances') }}" class="form-input-styled block w-full">
            </div>
          </div>

          <div>
            <label for="footer_text" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Texte du Pied de page (Footer)</label>
            <input type="text" name="footer_text" id="footer_text" value="{{ setting('branding.footer_text', '© 2026 Direction Générale du Budget — MINFI Cameroun. Tous droits réservés.') }}" class="form-input-styled block w-full">
          </div>

        </div>
      </div>

      <!-- Section 2 : Quotas & Règles d'Archivage -->
      <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm">
        <div class="border-b border-gray-100 bg-gray-50/60 px-6 py-4 flex items-center gap-2">
          <svg class="h-5 w-5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
          <h2 class="text-base font-extrabold text-gray-900">Règles & Quotas d'Archivage</h2>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div>
              <label for="max_upload_size_mb" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Taille maximale des fichiers (MB)</label>
              <input type="number" name="max_upload_size_mb" id="max_upload_size_mb" value="{{ setting('archivage.max_upload_size_mb', 20) }}" class="form-input-styled block w-full">
            </div>

            <div>
              <label for="allowed_extensions" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Extensions autorisées</label>
              <input type="text" name="allowed_extensions" id="allowed_extensions" value="{{ setting('archivage.allowed_extensions', 'pdf, docx, xlsx, png, jpg, zip') }}" class="form-input-styled block w-full">
            </div>

            <div>
              <label for="retention_period_years" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Durée de conservation (Années)</label>
              <input type="number" name="retention_period_years" id="retention_period_years" value="{{ setting('archivage.retention_period_years', 10) }}" class="form-input-styled block w-full">
            </div>
          </div>
        </div>
      </div>

      <!-- Section 3 : Sécurité & Connexions -->
      <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm">
        <div class="border-b border-gray-100 bg-gray-50/60 px-6 py-4 flex items-center gap-2">
          <svg class="h-5 w-5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
          <h2 class="text-base font-extrabold text-gray-900">Politiques de Sécurité & Sessions</h2>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div>
              <label for="max_login_attempts" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Tentatives de connexion max</label>
              <input type="number" name="max_login_attempts" id="max_login_attempts" value="{{ setting('securite.max_login_attempts', 5) }}" class="form-input-styled block w-full">
            </div>

            <div>
              <label for="lockout_duration_minutes" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Durée de verrouillage IP (Minutes)</label>
              <input type="number" name="lockout_duration_minutes" id="lockout_duration_minutes" value="{{ setting('securite.lockout_duration_minutes', 15) }}" class="form-input-styled block w-full">
            </div>

            <div>
              <label for="session_timeout_minutes" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Inactivité session max (Minutes)</label>
              <input type="number" name="session_timeout_minutes" id="session_timeout_minutes" value="{{ setting('securite.session_timeout_minutes', 120) }}" class="form-input-styled block w-full">
            </div>
          </div>
        </div>
      </div>

      <!-- Bouton d'enregistrement général -->
      <div class="flex items-center justify-end gap-3 pt-2">
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700 transition">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Enregistrer les modifications
        </button>
      </div>

    </div>
  </form>

</div>
@endsection
