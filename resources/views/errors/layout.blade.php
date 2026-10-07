@php
    try {
        $appName = setting('app_name', 'ArchiDoc');
        $structureAcronym = setting('structure_acronym', 'ARCHIDOC');
        $structureName = setting('structure_name', 'Entreprise Générale');
        $logo = setting('logo');
        $favicon = setting('favicon');
        $primaryColor = setting('primary_color', '#297a75');
        $secondaryColor = setting('secondary_color', '#21635f');
        $accentColor = setting('accent_color', '#40beb7');
        $successColor = setting('success_color', '#10b981');
        $errorColor = setting('error_color', '#f43f5e');
    } catch (\Throwable $e) {
        $appName = 'ArchiDoc';
        $structureAcronym = 'ARCHIDOC';
        $structureName = 'Entreprise Générale';
        $logo = null;
        $favicon = null;
        $primaryColor = '#297a75';
        $secondaryColor = '#21635f';
        $accentColor = '#40beb7';
        $successColor = '#10b981';
        $errorColor = '#f43f5e';
    }
@endphp
<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Anomalie Système — ' . $appName . ' ' . $structureAcronym)</title>
    <meta name="description" content="@yield('description', 'Une anomalie a été rencontrée lors du traitement de votre requête sur ' . $appName . ' ' . $structureAcronym . '.')">

    @if($favicon)
        <link rel="icon" href="{{ $favicon }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Injection dynamique de la charte graphique depuis les Paramètres Système -->
    <style>
        :root {
            --color-brand-500: {{ $accentColor }};
            --color-brand-600: {{ $accentColor }};
            --color-brand-700: {{ $primaryColor }};
            --color-brand-800: {{ $secondaryColor }};
            --color-brand-900: {{ $secondaryColor }};

            /* Notifications & Succès */
            --color-emerald-500: {{ $successColor }};
            --color-emerald-600: {{ $successColor }};
            --color-emerald-700: {{ $successColor }};

            /* Erreurs & Alertes */
            --color-rose-500: {{ $errorColor }};
            --color-rose-600: {{ $errorColor }};
            --color-rose-700: {{ $errorColor }};
            --color-red-500: {{ $errorColor }};
            --color-red-600: {{ $errorColor }};
        }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-100 bg-slate-900 flex flex-col justify-between selection:bg-brand-500 selection:text-white relative overflow-x-hidden">

    <!-- Cercles décoratifs d'arrière-plan avec couleur de charte -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden z-0">
        <div class="absolute -top-32 -right-32 h-96 w-96 rounded-full bg-brand-700/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-brand-800/30 blur-3xl"></div>
    </div>

    <!-- En-tête minimaliste avec branding dynamic -->
    <header class="relative z-10 mx-auto w-full max-w-7xl px-6 py-6 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-700 text-white shadow-lg ring-2 ring-brand-500/30 group-hover:scale-105 transition-all">
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo {{ $appName }}" class="h-8 w-8 rounded-lg object-cover">
                @else
                    <img src="{{ asset('media/img/logo_archidoc_dgb.png') }}" alt="Logo {{ $appName }}" class="h-8 w-8 rounded-lg object-cover">
                @endif
            </span>
            <span class="flex flex-col leading-none">
                <span class="text-lg font-extrabold tracking-tight text-white">{{ strtoupper($appName) }}</span>
                <span class="text-[10px] font-bold tracking-widest text-brand-500 uppercase">{{ $structureAcronym }}</span>
            </span>
        </a>

        <div>
            @auth
                <a href="{{ route('archives.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800/80 px-4 py-2 text-xs font-bold text-gray-200 border border-slate-700 hover:bg-slate-700/80 hover:text-white transition-all shadow-sm">
                    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Tableau de bord</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-brand-800 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Se connecter</span>
                </a>
            @endauth
        </div>
    </header>

    <!-- Carte Principale d'Erreur -->
    <main class="relative z-10 my-auto px-4 py-8">
        <div class="mx-auto max-w-xl text-center">
            
            <!-- Conteneur Carte avec Bordure Brand Dynamic -->
            <div class="rounded-3xl border border-slate-700/80 bg-slate-800/90 p-8 sm:p-12 shadow-2xl backdrop-blur-md">
                
                <!-- Badge Code Erreur -->
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-slate-600 bg-slate-900/60 px-4 py-1.5 backdrop-blur-md">
                    <span class="h-2.5 w-2.5 rounded-full @yield('badge_color', 'bg-amber-400') animate-pulse"></span>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-gray-200">
                        Code Erreur @yield('code', 'HTTP')
                    </span>
                </div>

                <!-- Illustration Icone SVG -->
                <div class="mb-6 flex justify-center">
                    <div class="flex h-24 w-24 items-center justify-center rounded-3xl @yield('icon_bg', 'bg-brand-700/20 text-brand-500 border border-brand-500/30') shadow-inner">
                        @yield('icon')
                    </div>
                </div>

                <!-- Titre & Description -->
                <h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl mb-3">
                    @yield('title_text', 'Une anomalie est survenue')
                </h1>
                <p class="text-sm leading-relaxed text-gray-300 mb-8">
                    @yield('description', 'La page demandée n\'a pas pu être affichée. Veuillez vérifier votre requête ou réessayer ultérieurement.')
                </p>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    @yield('actions')
                </div>

            </div>

            <p class="mt-6 text-xs text-gray-400">
                {{ $appName }} · Système de Gestion des Archives Numériques de {{ $structureName }}
            </p>
        </div>
    </main>

    <!-- Pied de page -->
    <footer class="relative z-10 py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} {{ $structureName }}. Tous droits réservés.
    </footer>

</body>
</html>
