@extends('errors.layout')

@section('title', '500 — Erreur Interne | ' . setting('app_name', 'ArchiDoc') . ' ' . setting('structure_acronym', 'DGB'))
@section('code', '500')
@section('badge_color', 'bg-rose-500')
@section('icon_bg', 'bg-rose-500/20 text-rose-300 border border-rose-400/30')

@section('icon')
<svg class="h-12 w-12 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('title_text', 'Anomalie Interne du Serveur')

@section('description')
{{ $exception->getMessage() ?: 'Une erreur inattendue s\'est produite sur le serveur d\'application. L\'événement a été consigné dans le journal d\'activité système.' }}
@endsection

@section('actions')
<button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-brand-800 transition-all">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    <span>Actualiser l'application</span>
</button>
<a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-slate-700/80 px-5 py-2.5 text-sm font-bold text-gray-200 border border-slate-600 hover:bg-slate-700 hover:text-white transition-all">
    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    <span>Retour au tableau de bord</span>
</a>
@endsection
