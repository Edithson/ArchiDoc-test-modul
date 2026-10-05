@extends('errors.layout')

@section('title', '503 — Service en Maintenance | ' . setting('app_name', 'ArchiDoc') . ' ' . setting('structure_acronym', 'DGB'))
@section('code', '503')
@section('badge_color', 'bg-indigo-400')
@section('icon_bg', 'bg-indigo-500/20 text-indigo-300 border border-indigo-400/30')

@section('icon')
<svg class="h-12 w-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
</svg>
@endsection

@section('title_text', 'Application en Cours de Maintenance')

@section('description')
{{ $exception->getMessage() ?: 'L\'application est temporairement indisponible en raison d\'opérations de maintenance ou de mise à jour programmée.' }}
@endsection

@section('actions')
<button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-brand-800 transition-all">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    <span>Actualiser l'application</span>
</button>
@endsection
