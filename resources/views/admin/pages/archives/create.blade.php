<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nouvelle archive — ArchiDoc DGB</title>
<meta name="description" content="Création d'une nouvelle archive — ArchiDoc, Direction Générale du Budget">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        },
        colors: {
          brand: {
            50:  '#eff9f9',
            100: '#dcf3f2',
            200: '#bee9e6',
            300: '#94dbd6',
            400: '#66cbc5',
            500: '#40beb7',
            600: '#339892',
            700: '#297a75',
            800: '#21635f',
            900: '#194c49',
            950: '#113533',
          },
        },
      },
    },
  };
</script>

<style>
  :root { color-scheme: light; }
  html { scroll-behavior: smooth; }
  body { font-feature-settings: "cv02","cv03","cv04","cv11"; }

  /* Barre de défilement discrète pour les listes déroulantes */
  .styled-scroll::-webkit-scrollbar { width: 8px; }
  .styled-scroll::-webkit-scrollbar-track { background: transparent; }
  .styled-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 9999px; }

  input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; opacity: 0.6; }
  input[type="date"]::-webkit-calendar-picker-indicator:hover { opacity: 1; }

  /* Respecte les préférences de mouvement réduit */
  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
      animation-duration: 0.001ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.001ms !important;
      scroll-behavior: auto !important;
    }
  }
</style>
</head>

<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">

<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-2 focus:top-2 focus:z-[60] focus:rounded-md focus:bg-brand-800 focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-white">
  Passer au contenu principal
</a>

<!-- ============================= HEADER ============================= -->
<header class="sticky top-0 z-30 border-b border-gray-200 bg-white">
  <div class="mx-auto flex h-16 max-w-[1600px] items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">

    <div class="flex min-w-0 items-center gap-3">
      <!-- Bouton menu mobile -->
      <button id="mobile-menu-btn" type="button"
        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2 xl:hidden"
        aria-label="Ouvrir le menu de navigation" aria-expanded="false" aria-controls="mobile-drawer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>

      <!-- Logo -->
      <a href="#" class="flex shrink-0 items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-700 text-white shadow-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
            <path d="M3 7.2 5 4h14l2 3.2"/><rect x="3" y="7.2" width="18" height="12.3" rx="1.5"/><path d="M8 11.5h8"/>
          </svg>
        </span>
        <span class="hidden flex-col leading-none sm:flex">
          <span class="text-sm font-bold tracking-tight text-gray-900">ArchiDoc</span>
          <span class="text-[11px] font-medium tracking-wide text-gray-400">DGB</span>
        </span>
      </a>

      <!-- Navigation desktop -->
      <nav aria-label="Navigation principale" class="hidden min-w-0 items-center gap-1 whitespace-nowrap pl-2 text-sm text-gray-500 xl:flex">
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Accueil</a>
        <span class="text-gray-300" aria-hidden="true">/</span>

        <div class="relative">
          <button id="creations-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="creations-menu"
            class="inline-flex items-center gap-1 rounded px-2 py-1.5 font-semibold text-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">
            Créations
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 opacity-70 transition-transform" data-chevron aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div id="creations-menu"
            class="absolute left-0 z-40 mt-2 hidden w-72 origin-top-left rounded-lg border border-gray-100 bg-white p-1.5 shadow-lg">
            <ul id="header-creations-list" class="space-y-0.5 text-sm"></ul>
          </div>
        </div>

        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Consultations</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Réglementation Archives</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Organigramme DGB</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="max-w-[13rem] truncate rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600" title="Organisation des archives de la DGB">Organisation des archives de la DGB</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Service Archives</a>
        <span class="text-gray-300" aria-hidden="true">/</span>
        <a href="#" class="rounded px-2 py-1.5 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600">Administration</a>
      </nav>
    </div>

    <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
      <!-- Notifications -->
      <div class="relative">
        <button id="notif-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="notif-panel"
          class="relative inline-flex h-9 w-9 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2"
          aria-label="Notifications">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
            <path d="M6 8a6 6 0 0 1 12 0c0 4 1.5 5.5 2.5 6.7.3.3.1.8-.3.8H3.8c-.4 0-.6-.5-.3-.8C4.5 13.5 6 12 6 8Z"/><path d="M10 19a2 2 0 0 0 4 0"/>
          </svg>
        </button>
        <div id="notif-panel" class="absolute right-0 z-40 mt-2 hidden w-64 rounded-lg border border-gray-100 bg-white p-4 text-sm text-gray-500 shadow-lg">
          Aucune nouvelle notification pour le moment.
        </div>
      </div>

      <!-- Avatar / compte -->
      <div class="relative">
        <button id="avatar-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="avatar-menu"
          class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2 sm:pr-3">
          <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-800">S</span>
          <span class="hidden text-sm font-medium text-gray-700 md:inline">Service</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div id="avatar-menu" class="absolute right-0 z-40 mt-2 hidden w-56 rounded-lg border border-gray-100 bg-white p-1.5 shadow-lg">
          <div class="border-b border-gray-100 px-3 py-2.5">
            <p class="text-sm font-semibold text-gray-900">Service</p>
            <p class="text-xs text-gray-500">DGB · Rôle Super</p>
          </div>
          <a href="#" class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Mon profil</a>
          <a href="#" class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Paramètres</a>
          <a href="#" class="block rounded-md px-3 py-2 text-sm text-red-600 hover:bg-red-50">Déconnexion</a>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- ==================== RIDEAU DE NAVIGATION MOBILE ==================== -->
<div id="mobile-drawer-backdrop" class="fixed inset-0 z-40 hidden bg-gray-900/50 xl:hidden" aria-hidden="true"></div>
<div id="mobile-drawer"
  class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] -translate-x-full bg-brand-700 text-white shadow-xl transition-transform duration-200 ease-out xl:hidden"
  role="dialog" aria-modal="true" aria-label="Menu de navigation">
  <div class="flex h-16 items-center justify-between border-b border-white/15 px-4">
    <span class="flex items-center gap-2 font-semibold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M3 7.2 5 4h14l2 3.2"/><rect x="3" y="7.2" width="18" height="12.3" rx="1.5"/><path d="M8 11.5h8"/></svg>
      ArchiDoc
    </span>
    <button id="mobile-drawer-close" type="button" class="rounded-md p-2 hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Fermer le menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
  <nav class="styled-scroll h-[calc(100%-4rem)] overflow-y-auto px-3 py-4">
    <ul id="mobile-toplevel-list" class="space-y-0.5 text-sm"></ul>
    <div class="mb-2 mt-5 px-3 text-xs font-semibold uppercase tracking-wider text-brand-100/80">Créations</div>
    <ul id="mobile-creations-list" class="space-y-1"></ul>
  </nav>
</div>

<!-- ============================= CORPS ============================= -->
<div class="mx-auto flex max-w-[1600px]">

  <!-- Barre latérale (desktop) -->
  <aside class="sticky top-16 hidden h-[calc(100vh-4rem)] w-72 shrink-0 flex-col bg-brand-700 text-white xl:flex">
    <div class="border-b border-white/15 px-6 py-5">
      <p class="text-xs font-semibold uppercase tracking-wider text-brand-100/80">Créations</p>
    </div>
    <nav class="styled-scroll flex-1 overflow-y-auto px-3 py-4" aria-label="Sous-navigation Créations">
      <ul id="sidebar-creations-list" class="space-y-1"></ul>
    </nav>
  </aside>

  <!-- Contenu principal -->
  <main id="main-content" tabindex="-1" class="min-w-0 flex-1 focus:outline-none">
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 sm:py-10 lg:px-10">

      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5 sm:px-8">
          <h1 class="text-xl font-bold tracking-tight text-brand-700 sm:text-2xl">Nouvelle archive</h1>
          <p class="mt-1 text-sm text-gray-500">Renseignez les informations ci-dessous pour créer une nouvelle archive.</p>
        </div>

        <div class="px-6 py-6 sm:px-8 sm:py-8">

          <!-- Indicateur d'étapes -->
          <div class="mb-8">
            <ol class="flex items-center">
              <li class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-700 text-sm font-semibold text-white">1</span>
                <span class="hidden text-sm font-semibold text-brand-700 sm:inline">Informations</span>
              </li>
              <li class="mx-3 h-px flex-1 bg-gray-200" aria-hidden="true"></li>
              <li class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-gray-200 text-sm font-semibold text-gray-400">2</span>
                <span class="hidden text-sm font-medium text-gray-400 sm:inline">Étape 2</span>
              </li>
              <li class="mx-3 h-px flex-1 bg-gray-200" aria-hidden="true"></li>
              <li class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-gray-200 text-sm font-semibold text-gray-400">3</span>
                <span class="hidden text-sm font-medium text-gray-400 sm:inline">Étape 3</span>
              </li>
            </ol>
            <p class="mt-3 text-sm font-medium text-gray-600">Étape 1 sur 3 — Création d'une archive</p>
          </div>

          <!-- Bandeau de confirmation (masqué par défaut) -->
          <div id="success-panel" tabindex="-1" class="mb-6 hidden rounded-lg border border-emerald-200 bg-emerald-50 p-4 focus:outline-none" role="status">
            <div class="flex gap-3">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
              <div>
                <p class="text-sm font-semibold text-emerald-800">Étape 1 validée</p>
                <p class="mt-1 text-sm text-emerald-700">Les informations saisies sont correctes. Les étapes 2 et 3 ne font pas partie de cette maquette et restent à implémenter.</p>
              </div>
            </div>
          </div>

          <form id="archive-form" novalidate>
            <div class="space-y-6">

              <!-- Format du document -->
              <div>
                <label for="format" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Format du document <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>
                <div class="relative">
                  <select id="format" name="format" required aria-required="true"
                    class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                    <option value="" disabled selected>Sélectionner le format...</option>
                    <option value="Document PDF">Document PDF</option>
                    <option value="Image">Image</option>
                    <option value="Document Papier">Document Papier</option>
                  </select>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </div>
                <p id="format-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Type d'archives (combobox avec autocomplétion) -->
              <div>
                <label for="typearchive" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Type d'archives <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>
                <div class="relative">
                  <input type="text" id="typearchive" name="typearchive" autocomplete="off" required aria-required="true"
                    role="combobox" aria-expanded="false" aria-controls="typearchive-listbox" aria-autocomplete="list"
                    placeholder="Rechercher ou saisir un type d'archive..."
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                  <ul id="typearchive-listbox" role="listbox" aria-label="Types d'archives" class="styled-scroll absolute z-20 mt-1 hidden max-h-56 w-full overflow-auto rounded-lg border border-gray-200 bg-white py-1 text-sm shadow-lg"></ul>
                </div>
                <p id="typearchive-hint" class="mt-1 hidden text-xs text-gray-500"></p>
                <p id="typearchive-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Objet de l'archive -->
              <div>
                <div class="mb-1.5 flex items-baseline justify-between gap-2">
                  <label for="description" class="block text-sm font-medium text-gray-700">
                    Objet de l'archive <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                  </label>
                  <span id="description-count" class="shrink-0 text-xs text-gray-400">0/30</span>
                </div>
                <textarea id="description" name="description" rows="3" maxlength="30" required aria-required="true" aria-describedby="description-count"
                  placeholder="Résumé court de l'objet de l'archive..."
                  class="block w-full resize-y rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30"></textarea>
                <p id="description-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Date signature -->
              <div>
                <label for="date_doc" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Date signature <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>
                <input type="date" id="date_doc" name="date_doc" required aria-required="true"
                  class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                <p id="date_doc-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

              <!-- Emplacement physique / virtuel -->
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label for="emplacement" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Emplacement physique <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                  </label>
                  <div class="relative">
                    <select id="emplacement" name="emplacement" required aria-required="true"
                      class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                      <option value="" disabled selected>Sélectionner l'emplacement...</option>
                      <option value="FOUDA">FOUDA — Centre d'excellence DGB</option>
                      <option value="DGB">DGB — Direction Générale du Budget</option>
                      <option value="IMPRIMERIE NATIONALE">Imprimerie Nationale</option>
                    </select>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                  </div>
                  <p id="emplacement-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
                </div>

                <div>
                  <label for="emplacement2" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Emplacement virtuel <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                  </label>
                  <div class="relative">
                    <select id="emplacement2" name="emplacement2" required aria-required="true"
                      class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                      <option value="" disabled selected>Sélectionner l'emplacement...</option>
                      <option value="Serveur">Serveur</option>
                    </select>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                  </div>
                  <p id="emplacement2-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
                </div>
              </div>

              <!-- Rayon / Travée -->
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label for="rayon" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Rayon <span class="text-gray-400">(optionnel)</span>
                  </label>
                  <input type="text" id="rayon" name="rayon" autocomplete="off"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                </div>
                <div>
                  <label for="travee" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Travée <span class="text-gray-400">(optionnel)</span>
                  </label>
                  <input type="text" id="travee" name="travee" autocomplete="off"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                </div>
              </div>

              <!-- Cote de boite d'archives -->
              <div>
                <label for="cote" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Cote de boîte d'archives <span class="text-gray-400">(optionnel)</span>
                </label>
                <input type="text" id="cote" name="cote" autocomplete="off" placeholder="Ex. B-2026-014"
                  class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
              </div>

              <!-- Groupe d'accès -->
              <div>
                <label for="departement" class="mb-1.5 block text-sm font-medium text-gray-700">
                  Groupe d'accès <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>
                </label>
                <div class="relative">
                  <select id="departement" name="departement" required aria-required="true"
                    class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-900 shadow-sm focus:border-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-600/30">
                    <option value="" disabled selected>Sélectionner le groupe d'accès...</option>
                  </select>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </div>
                <p id="departement-error" class="mt-1 hidden text-sm text-red-600" role="alert"></p>
              </div>

            </div>

            <!-- Pied de formulaire -->
            <div class="mt-8 border-t border-gray-100 pt-6">
              <p class="mb-4 text-sm italic text-gray-500"><span class="text-red-500" aria-hidden="true">*</span> Les champs marqués d'un astérisque sont obligatoires.</p>
              <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="reset" id="reset-btn"
                  class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-600 focus-visible:ring-offset-2">
                  Réinitialiser
                </button>
                <button type="submit"
                  class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-700 focus-visible:ring-offset-2">
                  Suivant
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>

      <p class="mt-4 text-center text-xs text-gray-400">ArchiDoc — Direction Générale du Budget</p>
    </div>
  </main>
</div>

<script>
(function () {
  "use strict";

  /* ------------------------------------------------------------------ */
  /* Données réelles réutilisées à plusieurs endroits (menu, listes...) */
  /* ------------------------------------------------------------------ */

  var ARCHIVE_TYPES = [
    "ARRETE","ATTESTATION","AUTRES TYPES DE DOCUMENTS","BONS D'ENGAGEMENT","BORDEREAUX",
    "CARNETS D'ENGAGEMENT","CERTIFICATS","CIRCULAIRE","COMMUNIQUES","COMPTE ADMINISTRATIF",
    "COMPTE D'EMPLOI","COMPTE-RENDU","CONSTITUTION","CONVOCATIONS","COURRIERS","DECISIONS",
    "DECRET","ETATS DE SOMMES DUES","FONDS DE DOSSIER","INVITATIONS","LETTRE CIRCULAIRE",
    "LETTRE DE MISSION","LOI","MEMO","MEMOIRES DE DEPENSE","MESSAGE-FAX","MESSAGE-PORTE",
    "NOTE","NOTE DE SERVICE","ORDONNANCES","PROCES-VERBAL","SOIT-TRANSMIS"
  ];

  var GROUPES = [
    { sigle: "CAB DGB", nom: "Cabinet DGB" },
    { sigle: "DCOB", nom: "Division du Contrôle Budgétaire, de l'Audit et de la Qualité de la Dépense" },
    { sigle: "DDPP", nom: "Direction de la Dépense du Personnel et des Pensions" },
    { sigle: "DI", nom: "Division Informatique" },
    { sigle: "DPB", nom: "Division de la Préparation du Budget" },
    { sigle: "DPC", nom: "Division de Participation et Contribution" },
    { sigle: "DREF", nom: "Division de la Réforme Budgétaire" },
    { sigle: "PUBLIC", nom: "Public" },
    { sigle: "S-DAG", nom: "Sous-Direction des Affaires Générales" },
    { sigle: "S-DCF", nom: "Sous-Direction du Contrôle Financier" },
    { sigle: "SGCCC", nom: "Service de Gestion des Crédits des Chapitres Communs" },
    { sigle: "SGDB", nom: "Service de Gestion des Documents Budgétaires" },
    { sigle: "SO", nom: "Service d'Ordre" }
  ];

  var ICONS = {
    home: '<path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v9a1 1 0 0 0 1 1h3v-6h6v6h3a1 1 0 0 0 1-1v-9"/>',
    tag: '<path d="M4 4h7l9 9-7 7-9-9V4Z"/><circle cx="8.5" cy="8.5" r="1.2"/>',
    pin: '<path d="M12 21s7-6.7 7-12a7 7 0 1 0-14 0c0 5.3 7 12 7 12Z"/><circle cx="12" cy="9" r="2.4"/>',
    users: '<circle cx="9" cy="8" r="3.2"/><path d="M2.3 20c0-3.6 3-6.2 6.7-6.2s6.7 2.6 6.7 6.2"/><path d="M15.8 8.3a3 3 0 1 1 3.5 2.9"/><path d="M21.7 20c0-2.8-1.8-5-4.3-5.8"/>',
    archivePlus: '<path d="M3 7.2 5 4h14l2 3.2"/><rect x="3" y="7.2" width="18" height="12.3" rx="1.5"/><path d="M12 11v5M9.5 13.5h5"/>',
    userPlus: '<circle cx="9" cy="7.5" r="3.5"/><path d="M2 20c0-3.9 3.1-6.3 7-6.3"/><path d="M17.5 12.5v6M14.5 15.5h6"/>'
  };

  var CREATION_LINKS = [
    { href: "#", label: "Accueil", icon: ICONS.home, active: false },
    { href: "#", label: "Nouveau Type d'archive", icon: ICONS.tag, active: false },
    { href: "#", label: "Nouvel Emplacement", icon: ICONS.pin, active: false },
    { href: "#", label: "Nouveau Groupe d'accès", icon: ICONS.users, active: false },
    { href: "#", label: "Nouvelle Archive", icon: ICONS.archivePlus, active: true },
    { href: "#", label: "Nouveau Dossier du personnel", icon: ICONS.userPlus, active: false }
  ];

  var TOP_LEVEL_LINKS = [
    "Accueil", "Consultations", "Réglementation Archives", "Organigramme DGB",
    "Organisation des archives de la DGB", "Service Archives", "Administration"
  ];

  function svg(pathInner, cls) {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' + cls + '" aria-hidden="true">' + pathInner + '</svg>';
  }

  /* ------------------------------------------------------------------ */
  /* Rendu des listes de navigation "Créations" (3 emplacements)         */
  /* ------------------------------------------------------------------ */

  function renderSidebarStyle(container) {
    var html = "";
    CREATION_LINKS.forEach(function (item) {
      if (item.active) {
        html += '<li><a href="' + item.href + '" aria-current="page" class="flex items-center gap-3 rounded-lg border-l-4 border-brand-600 bg-white px-3 py-2.5 text-sm font-semibold text-brand-800 shadow-sm">' +
          svg(item.icon, "h-4 w-4 shrink-0") + '<span class="truncate">' + item.label + "</span></a></li>";
      } else {
        html += '<li><a href="' + item.href + '" class="flex items-center gap-3 rounded-lg border-l-4 border-transparent px-3 py-2.5 text-sm text-brand-50 transition-colors hover:bg-white/10 hover:text-white">' +
          svg(item.icon, "h-4 w-4 shrink-0") + '<span class="truncate">' + item.label + "</span></a></li>";
      }
    });
    container.innerHTML = html;
  }

  function renderDropdownStyle(container) {
    var html = "";
    CREATION_LINKS.forEach(function (item) {
      var stateCls = item.active
        ? "flex items-center gap-2.5 rounded-md bg-brand-50 px-3 py-2 text-sm font-semibold text-brand-700"
        : "flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-50";
      html += '<li><a href="' + item.href + '" class="' + stateCls + '">' + svg(item.icon, "h-4 w-4 shrink-0 text-current") + "<span>" + item.label + "</span></a></li>";
    });
    container.innerHTML = html;
  }

  renderSidebarStyle(document.getElementById("sidebar-creations-list"));
  renderDropdownStyle(document.getElementById("header-creations-list"));
  renderSidebarStyle(document.getElementById("mobile-creations-list"));

  var topLevelHtml = "";
  TOP_LEVEL_LINKS.forEach(function (label) {
    topLevelHtml += '<li><a href="#" class="block rounded-lg px-3 py-2.5 text-brand-50 hover:bg-white/10 hover:text-white">' + label + "</a></li>";
  });
  document.getElementById("mobile-toplevel-list").innerHTML = topLevelHtml;

  var departementSelect = document.getElementById("departement");
  GROUPES.forEach(function (g) {
    var opt = document.createElement("option");
    opt.value = g.sigle;
    opt.textContent = g.sigle + " — " + g.nom;
    departementSelect.appendChild(opt);
  });

  /* ------------------------------------------------------------------ */
  /* Menus déroulants réutilisables (Créations, Notifications, Avatar)   */
  /* ------------------------------------------------------------------ */

  function setupDropdown(triggerId, panelId) {
    var trigger = document.getElementById(triggerId);
    var panel = document.getElementById(panelId);
    if (!trigger || !panel) return;

    function close() {
      panel.classList.add("hidden");
      trigger.setAttribute("aria-expanded", "false");
      var chev = trigger.querySelector("[data-chevron]");
      if (chev) chev.style.transform = "";
    }
    function open() {
      closeAllDropdowns();
      panel.classList.remove("hidden");
      trigger.setAttribute("aria-expanded", "true");
      var chev = trigger.querySelector("[data-chevron]");
      if (chev) chev.style.transform = "rotate(180deg)";
    }
    trigger.addEventListener("click", function (e) {
      e.stopPropagation();
      var isOpen = trigger.getAttribute("aria-expanded") === "true";
      isOpen ? close() : open();
    });
    trigger._closeDropdown = close;
    dropdownClosers.push(close);
  }

  var dropdownClosers = [];
  function closeAllDropdowns() {
    dropdownClosers.forEach(function (close) { close(); });
  }

  setupDropdown("creations-trigger", "creations-menu");
  setupDropdown("notif-trigger", "notif-panel");
  setupDropdown("avatar-trigger", "avatar-menu");

  document.addEventListener("click", closeAllDropdowns);
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeAllDropdowns();
  });

  /* ------------------------------------------------------------------ */
  /* Tiroir de navigation mobile                                        */
  /* ------------------------------------------------------------------ */

  var drawer = document.getElementById("mobile-drawer");
  var backdrop = document.getElementById("mobile-drawer-backdrop");
  var menuBtn = document.getElementById("mobile-menu-btn");
  var closeBtn = document.getElementById("mobile-drawer-close");

  function openDrawer() {
    drawer.classList.remove("-translate-x-full");
    backdrop.classList.remove("hidden");
    document.documentElement.classList.add("overflow-hidden");
    menuBtn.setAttribute("aria-expanded", "true");
    closeBtn.focus();
  }
  function closeDrawer() {
    drawer.classList.add("-translate-x-full");
    backdrop.classList.add("hidden");
    document.documentElement.classList.remove("overflow-hidden");
    menuBtn.setAttribute("aria-expanded", "false");
    menuBtn.focus();
  }
  menuBtn.addEventListener("click", openDrawer);
  closeBtn.addEventListener("click", closeDrawer);
  backdrop.addEventListener("click", closeDrawer);
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !drawer.classList.contains("-translate-x-full")) closeDrawer();
  });
  drawer.addEventListener("click", function (e) {
    var link = e.target.closest("a");
    if (link) { e.preventDefault(); closeDrawer(); }
  });
  document.getElementById("creations-menu").addEventListener("click", function (e) {
    var link = e.target.closest("a");
    if (link) e.preventDefault();
  });

  /* ------------------------------------------------------------------ */
  /* Combobox "Type d'archives" avec autocomplétion                     */
  /* ------------------------------------------------------------------ */

  var typeInput = document.getElementById("typearchive");
  var typeListbox = document.getElementById("typearchive-listbox");
  var typeHint = document.getElementById("typearchive-hint");
  var activeIndex = -1;
  var currentMatches = [];

  function renderTypeOptions(query) {
    var q = query.trim().toLowerCase();
    currentMatches = q === ""
      ? ARCHIVE_TYPES.slice()
      : ARCHIVE_TYPES.filter(function (t) { return t.toLowerCase().indexOf(q) !== -1; });

    if (currentMatches.length === 0) {
      typeListbox.innerHTML = '<li class="px-3 py-2 text-sm text-gray-400">Aucun type existant ne correspond.</li>';
    } else {
      typeListbox.innerHTML = currentMatches.map(function (t, i) {
        return '<li role="option" id="type-opt-' + i + '" data-value="' + t.replace(/"/g, "&quot;") + '" class="cursor-pointer px-3 py-1.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700">' + t + "</li>";
      }).join("");
    }
    activeIndex = -1;
    updateHint(query);
  }

  function updateHint(query) {
    var trimmed = query.trim();
    if (trimmed === "") {
      typeHint.classList.add("hidden");
      return;
    }
    var exact = ARCHIVE_TYPES.some(function (t) { return t.toLowerCase() === trimmed.toLowerCase(); });
    if (!exact) {
      typeHint.textContent = "Nouveau type — sera proposé à l'ajout dans la liste.";
      typeHint.classList.remove("hidden");
    } else {
      typeHint.classList.add("hidden");
    }
  }

  function openTypeListbox() {
    typeListbox.classList.remove("hidden");
    typeInput.setAttribute("aria-expanded", "true");
  }
  function closeTypeListbox() {
    typeListbox.classList.add("hidden");
    typeInput.setAttribute("aria-expanded", "false");
    activeIndex = -1;
  }

  function highlightActive() {
    var items = typeListbox.querySelectorAll('li[role="option"]');
    items.forEach(function (li, i) {
      if (i === activeIndex) {
        li.classList.add("bg-brand-50", "text-brand-700");
        li.scrollIntoView({ block: "nearest" });
      } else {
        li.classList.remove("bg-brand-50", "text-brand-700");
      }
    });
  }

  typeInput.addEventListener("input", function () {
    renderTypeOptions(typeInput.value);
    openTypeListbox();
  });
  typeInput.addEventListener("focus", function () {
    renderTypeOptions(typeInput.value);
    openTypeListbox();
  });
  typeInput.addEventListener("keydown", function (e) {
    var items = typeListbox.querySelectorAll('li[role="option"]');
    if (e.key === "ArrowDown") {
      e.preventDefault();
      if (typeListbox.classList.contains("hidden")) { renderTypeOptions(typeInput.value); openTypeListbox(); return; }
      activeIndex = Math.min(activeIndex + 1, items.length - 1);
      highlightActive();
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      activeIndex = Math.max(activeIndex - 1, 0);
      highlightActive();
    } else if (e.key === "Enter") {
      if (activeIndex >= 0 && currentMatches[activeIndex]) {
        e.preventDefault();
        typeInput.value = currentMatches[activeIndex];
        closeTypeListbox();
        updateHint(typeInput.value);
      }
    } else if (e.key === "Escape") {
      closeTypeListbox();
    }
  });
  typeListbox.addEventListener("click", function (e) {
    var li = e.target.closest('li[role="option"]');
    if (li && li.dataset.value) {
      typeInput.value = li.dataset.value;
      closeTypeListbox();
      updateHint(typeInput.value);
      typeInput.focus();
    }
  });
  document.addEventListener("click", function (e) {
    if (!e.target.closest("#typearchive") && !e.target.closest("#typearchive-listbox")) closeTypeListbox();
  });

  /* ------------------------------------------------------------------ */
  /* Compteur de caractères — Objet de l'archive                        */
  /* ------------------------------------------------------------------ */

  var descriptionInput = document.getElementById("description");
  var descriptionCount = document.getElementById("description-count");
  descriptionInput.addEventListener("input", function () {
    var len = descriptionInput.value.length;
    descriptionCount.textContent = len + "/30";
    descriptionCount.classList.toggle("text-red-500", len >= 30);
    descriptionCount.classList.toggle("text-amber-600", len >= 25 && len < 30);
    descriptionCount.classList.toggle("text-gray-400", len < 25);
  });

  /* Date signature : pas de date future */
  var dateInput = document.getElementById("date_doc");
  dateInput.max = new Date().toISOString().split("T")[0];

  /* ------------------------------------------------------------------ */
  /* Validation et soumission du formulaire (étape 1)                   */
  /* ------------------------------------------------------------------ */

  var form = document.getElementById("archive-form");
  var successPanel = document.getElementById("success-panel");

  var REQUIRED_FIELDS = [
    { id: "format", message: "Veuillez sélectionner un format de document." },
    { id: "typearchive", message: "Veuillez indiquer un type d'archives." },
    { id: "description", message: "Veuillez renseigner l'objet de l'archive." },
    { id: "date_doc", message: "Veuillez indiquer la date de signature." },
    { id: "emplacement", message: "Veuillez sélectionner l'emplacement physique." },
    { id: "emplacement2", message: "Veuillez sélectionner l'emplacement virtuel." },
    { id: "departement", message: "Veuillez sélectionner un groupe d'accès." }
  ];

  function clearErrors() {
    REQUIRED_FIELDS.forEach(function (f) {
      var el = document.getElementById(f.id);
      var err = document.getElementById(f.id + "-error");
      el.classList.remove("border-red-400", "focus:border-red-500", "focus:ring-red-500/30");
      el.removeAttribute("aria-invalid");
      if (err) { err.textContent = ""; err.classList.add("hidden"); }
    });
  }

  function showError(id, message) {
    var el = document.getElementById(id);
    var err = document.getElementById(id + "-error");
    el.classList.add("border-red-400", "focus:border-red-500", "focus:ring-red-500/30");
    el.setAttribute("aria-invalid", "true");
    if (err) { err.textContent = message; err.classList.remove("hidden"); }
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    clearErrors();
    successPanel.classList.add("hidden");

    var firstInvalid = null;
    var valid = true;

    REQUIRED_FIELDS.forEach(function (f) {
      var el = document.getElementById(f.id);
      if (!el.value || !el.value.trim()) {
        valid = false;
        showError(f.id, f.message);
        if (!firstInvalid) firstInvalid = el;
      }
    });

    if (!valid) {
      firstInvalid.focus();
      return;
    }

    successPanel.classList.remove("hidden");
    successPanel.scrollIntoView({ behavior: "smooth", block: "center" });
    successPanel.focus();
  });

  form.addEventListener("reset", function () {
    window.setTimeout(function () {
      clearErrors();
      successPanel.classList.add("hidden");
      descriptionCount.textContent = "0/30";
      descriptionCount.className = "shrink-0 text-xs text-gray-400";
      typeHint.classList.add("hidden");
    }, 0);
  });

})();
</script>

</body>
</html>
