@extends('errors.layout')

@section('title', '403 — Accès Refusé | ' . setting('app_name', 'ArchiDoc') . ' ' . setting('structure_acronym', 'DGB'))
@section('code', '403')
@section('badge_color', 'bg-rose-500')
@section('icon_bg', 'bg-rose-500/20 text-rose-400 border border-rose-500/30')

@section('icon')
<svg class="h-12 w-12 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
</svg>
@endsection

@section('title_text', 'Accès Refusé / Privilèges Insuffisants')

@section('description')
{{ $exception->getMessage() ?: 'Vous ne disposez pas des privilèges ou autorisations requises pour accéder à cette fonction ou ce document d\'archive.' }}
@endsection

@section('actions')
<a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-brand-800 transition-all">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    <span>Retour à l'accueil</span>
</a>
<button onclick="window.history.back()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-slate-700/80 px-5 py-2.5 text-sm font-bold text-gray-200 border border-slate-600 hover:bg-slate-700 hover:text-white transition-all">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    <span>Page précédente</span>
</button>
@endsection
