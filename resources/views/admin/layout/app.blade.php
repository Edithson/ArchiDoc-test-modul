<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('app_name', 'ArchiDoc') . ' ' . setting('structure_acronym', 'DGB'))</title>
    <meta name="description" content="@yield('meta_description', 'Système d\'archivage — ' . setting('app_name', 'ArchiDoc') . ', ' . setting('structure_name', 'Direction Générale du Budget'))">

    @if(setting('favicon'))
        <link rel="icon" href="{{ setting('favicon') }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Injection dynamique de la charte graphique globale définie dans les Paramètres Système -->
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

            /* Notifications, Bannières & Messages d'Échec / Erreur */
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

<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">

    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-2 focus:top-2 focus:z-[60] focus:rounded-md focus:bg-brand-800 focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-white">
        Passer au contenu principal
    </a>

    <!-- En-tête global -->
    @include('admin.layout.header')

    <!-- Rideau mobile -->
    @include('admin.layout.mobile-drawer')

    <!-- Corps de page -->
    <div class="mx-auto flex max-w-[1600px]">
        <!-- Barre latérale desktop -->
        @include('admin.layout.sidebar')

        <!-- Zone de contenu principal -->
        <main id="main-content" tabindex="-1" class="min-w-0 flex-1 focus:outline-none">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
