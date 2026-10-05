@extends('errors.layout')

@section('title', '422 — Validation Invalide | ' . setting('app_name', 'ArchiDoc') . ' ' . setting('structure_acronym', 'DGB'))
@section('code', '422')
@section('badge_color', 'bg-orange-400')
@section('icon_bg', 'bg-orange-500/20 text-orange-300 border border-orange-400/30')

@section('icon')
<svg class="h-12 w-12 text-orange-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
</svg>
@endsection

@section('title_text', 'Données de Formulaire Invalides')

@section('description')
{{ $exception->getMessage() ?: 'Les champs transmis ne satisfont pas les contraintes de format, de taille ou d\'intégrité requises pour enregistrer l\'opération.' }}
@endsection

@section('actions')
<button onclick="window.history.back()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-brand-800 transition-all">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    <span>Corriger les saisies</span>
</button>
<a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-slate-700/80 px-5 py-2.5 text-sm font-bold text-gray-200 border border-slate-600 hover:bg-slate-700 hover:text-white transition-all">
    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    <span>Tableau de bord</span>
</a>
@endsection
