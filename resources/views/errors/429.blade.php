@extends('errors.layout')

@section('title', '429 — Trop de Requêtes | ' . setting('app_name', 'ArchiDoc') . ' ' . setting('structure_acronym', 'DGB'))
@section('code', '429')
@section('badge_color', 'bg-purple-400')
@section('icon_bg', 'bg-purple-500/20 text-purple-300 border border-purple-400/30')

@section('icon')
<svg class="h-12 w-12 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>
@endsection

@section('title_text', 'Nombre de Requêtes Excessive')

@section('description')
Vous avez soumis un nombre trop élevé de requêtes dans un court intervalle de temps. Afin de garantir la stabilité de l'application, l'accès est temporairement limité.
@endsection

@section('actions')
<button onclick="setTimeout(() => window.location.reload(), 1000)" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-brand-800 transition-all">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    <span>Réessayer dans quelques instants</span>
</button>
<a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-slate-700/80 px-5 py-2.5 text-sm font-bold text-gray-200 border border-slate-600 hover:bg-slate-700 hover:text-white transition-all">
    <svg class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    <span>Accueil</span>
</a>
@endsection
