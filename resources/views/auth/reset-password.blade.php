<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouveau mot de passe — {{ setting('app_name', 'ArchiDoc') }}{{ setting('structure_acronym') ? ' ' . setting('structure_acronym') : '' }}</title>
  <meta name="description" content="Définition d'un nouveau mot de passe — {{ setting('app_name', 'ArchiDoc') }}">

  @if(setting('favicon'))
      <link rel="icon" href="{{ setting('favicon') }}">
  @endif

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
      :root {
          --color-brand-500: {{ setting('accent_color', '#40beb7') }};
          --color-brand-600: {{ setting('accent_color', '#40beb7') }};
          --color-brand-700: {{ setting('primary_color', '#297a75') }};
          --color-brand-800: {{ setting('secondary_color', '#21635f') }};
          --color-brand-900: {{ setting('secondary_color', '#21635f') }};

          /* Notifications & Messages de Succès */
          --color-emerald-500: {{ setting('success_color', '#10b981') }};
          --color-emerald-600: {{ setting('success_color', '#10b981') }};
          --color-emerald-700: {{ setting('success_color', '#10b981') }};
          --color-emerald-800: {{ setting('success_color', '#10b981') }};
          --color-emerald-900: {{ setting('success_color', '#10b981') }};

          /* Notifications & Messages d'Échec */
          --color-rose-500: {{ setting('error_color', '#f43f5e') }};
          --color-rose-600: {{ setting('error_color', '#f43f5e') }};
          --color-rose-700: {{ setting('error_color', '#f43f5e') }};
          --color-rose-800: {{ setting('error_color', '#f43f5e') }};
          --color-red-500: {{ setting('error_color', '#f43f5e') }};
          --color-red-600: {{ setting('error_color', '#f43f5e') }};
          --color-red-700: {{ setting('error_color', '#f43f5e') }};
          --color-red-800: {{ setting('error_color', '#f43f5e') }};
      }
  </style>
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-gradient-to-br from-gray-50 via-gray-100 to-brand-50/20" @if(setting('login_image')) style="background-image: linear-gradient(to bottom right, rgba(249,250,251,0.9), rgba(243,244,246,0.95)), url('{{ setting('login_image') }}'); background-size: cover; background-position: center;" @endif>

  <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    
    <!-- En-tête / Logo -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
      <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-700 text-white shadow-lg ring-4 ring-brand-700/20 mb-4">
        @if(setting('logo'))
          <img src="{{ setting('logo') }}" alt="Logo {{ setting('app_name', 'ArchiDoc') }}" class="h-12 w-12 rounded-full object-cover">
        @else
          <img src="{{ asset('media/img/logo_archidoc_dgb.png') }}" alt="Logo {{ setting('app_name', 'ArchiDoc') }}" class="h-12 w-12 rounded-full object-cover">
        @endif
      </div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">{{ strtoupper(setting('app_name', 'ARCHIDOC')) }}</h1>
      <p class="mt-1 text-xs font-bold tracking-widest text-brand-700 uppercase">{{ setting('structure_name', 'Entreprise Générale') }}</p>
      <h2 class="mt-3 text-lg font-bold text-gray-800">Nouveau mot de passe</h2>
      <p class="mt-1 text-xs text-gray-500 leading-relaxed px-4">
        Définissez votre nouveau mot de passe pour sécuriser l'accès à votre compte.
      </p>
    </div>

    <!-- Carte du formulaire -->
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-100 sm:px-10">

        @if ($errors->any())
          <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-xs text-red-700" role="alert">
            <div class="flex items-center gap-2 font-bold mb-1">
              <svg class="h-4 w-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>Erreur de réinitialisation</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
          @csrf

          <!-- Password Reset Token -->
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <!-- Adresse e-mail -->
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-800 mb-1.5">
              Adresse e-mail <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input id="email" name="email" type="email" autocomplete="email" required
                value="{{ old('email', $request->email) }}"
                placeholder="votre.email@entreprise.com"
                class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pl-10 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Nouveau mot de passe -->
          <div>
            <label for="password" class="block text-sm font-semibold text-gray-800 mb-1.5">
              Nouveau mot de passe <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input id="password" name="password" type="password" autocomplete="new-password" required
                placeholder="••••••••"
                class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pl-10 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </div>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">8 caractères minimum avec sécurité recommandée.</p>
          </div>

          <!-- Confirmation du mot de passe -->
          <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-800 mb-1.5">
              Confirmer le nouveau mot de passe <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                placeholder="••••••••"
                class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pl-10 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Bouton de soumission -->
          <div>
            <button type="submit"
              class="flex w-full justify-center items-center gap-2 rounded-xl bg-brand-700 px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700 focus:ring-offset-2 transition-all">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
              Mettre à jour le mot de passe
            </button>
          </div>

          <!-- Retour à la connexion -->
          <div class="pt-2 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-brand-700 transition-colors">
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
              Retour à l'écran de connexion
            </a>
          </div>

        </form>

      </div>

      <!-- Pied de carte -->
      <div class="mt-6 text-center space-y-2">
        <p class="text-xs text-gray-500 font-medium">
          {{ setting('footer_text', '© ' . date('Y') . ' ' . setting('app_name', 'ArchiDoc') . '. Tous droits réservés.') }}
        </p>

        @php
          $devName = config('app.dev_name') ?? config('developer.name') ?? env('DEV_NAME');
          $devAdr = config('app.dev_adr') ?? config('developer.adr') ?? env('DEV_ADR');
          $isUrl = $devAdr && filter_var($devAdr, FILTER_VALIDATE_URL);
        @endphp

        @if($devName || $devAdr)
          <div class="text-[11px] text-gray-400 flex items-center justify-center gap-1.5 flex-wrap">
            <span>Développé par</span>
            @if($isUrl)
              <a href="{{ $devAdr }}" target="_blank" rel="noopener noreferrer" class="font-extrabold text-brand-700 hover:text-brand-800 hover:underline inline-flex items-center gap-1 transition-colors">
                <span>{{ $devName ?? $devAdr }}</span>
                <svg class="h-3 w-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
              </a>
            @elseif($devAdr)
              <span class="font-bold text-gray-700">{{ $devName }}</span>
              <span class="text-gray-400">({{ $devAdr }})</span>
            @else
              <span class="font-bold text-gray-700">{{ $devName }}</span>
            @endif
          </div>
        @endif
      </div>
    </div>

  </div>

</body>
</html>
