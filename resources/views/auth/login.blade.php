<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — ArchiDoc DGB</title>
  <meta name="description" content="Connexion au logiciel de gestion d'archivage numérique ArchiDoc, Direction Générale du Budget">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-gradient-to-br from-gray-50 via-gray-100 to-brand-50/20">

  <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    
    <!-- En-tête / Logo -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
      <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-700 text-white shadow-lg ring-4 ring-brand-700/20 mb-4">
        <img src="{{ asset('media/img/logo_archidoc_dgb.png') }}" alt="Logo ARCHIDOC DGB" class="h-12 w-12 rounded-full object-cover">
      </div>
      <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">ARCHIDOC</h1>
      <p class="mt-1 text-xs font-bold tracking-widest text-brand-700 uppercase">Direction Générale du Budget</p>
      <p class="mt-2 text-sm text-gray-500">Connectez-vous pour accéder à la plateforme d'archivage</p>
    </div>

    <!-- Carte du formulaire -->
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-100 sm:px-10">

        <!-- Banner de statut de session / erreurs globales -->
        @if (session('status'))
          <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800" role="status">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-xs text-red-700" role="alert">
            <div class="flex items-center gap-2 font-bold mb-1">
              <svg class="h-4 w-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>Erreur d'authentification</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
          @csrf

          <!-- Adresse e-mail -->
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-800 mb-1.5">
              Adresse e-mail <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input id="email" name="email" type="email" autocomplete="email" required
                value="{{ old('email') }}"
                placeholder="nom.prenom@minfi.cm"
                class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pl-10 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Mot de passe -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label for="password" class="block text-sm font-semibold text-gray-800">
                Mot de passe <span class="text-red-500">*</span>
              </label>
            </div>
            <div class="relative">
              <input id="password" name="password" type="password" autocomplete="current-password" required
                placeholder="••••••••"
                class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pl-10 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Se souvenir de moi -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember" name="remember" type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-brand-700 focus:ring-brand-600">
              <label for="remember" class="ml-2 block text-xs font-medium text-gray-700">
                Se souvenir de moi
              </label>
            </div>
          </div>

          <!-- Bouton de soumission -->
          <div>
            <button type="submit"
              class="flex w-full justify-center items-center gap-2 rounded-xl bg-brand-700 px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-700 focus:ring-offset-2 transition-all">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
              Se connecter
            </button>
          </div>

        </form>

      </div>

      <!-- Pied de carte -->
      <p class="mt-6 text-center text-xs text-gray-400">
        © 2026 ArchiDoc — Direction Générale du Budget, Cameroun
      </p>
    </div>

  </div>

</body>
</html>
