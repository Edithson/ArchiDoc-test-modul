<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ArchiDoc DGB')</title>
    <meta name="description" content="@yield('meta_description', 'Système d\'archivage — ArchiDoc, Direction Générale du Budget')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
