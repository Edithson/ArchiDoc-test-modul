@extends('errors.layout')

@section('title', '419 — Session Expirée | ' . setting('app_name', 'ArchiDoc') . ' ' . setting('structure_acronym', 'DGB'))
@section('code', '419')
@section('badge_color', 'bg-sky-400')
@section('icon_bg', 'bg-sky-500/20 text-sky-300 border border-sky-400/30')

@section('icon')
<svg class="h-12 w-12 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('title_text', 'Session ou Formulaire Expiré')

@section('description')
Votre session d'inactivité a expiré ou le jeton de sécurité de votre formulaire n'est plus valide. Veuillez rafraîchir la page ou vous reconnecter pour poursuivre.
@endsection

@section('actions')
<button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-brand-800 transition-all">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    <span>Actualiser la page</span>
</button>
<a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-slate-700/80 px-5 py-2.5 text-sm font-bold text-gray-200 border border-slate-600 hover:bg-slate-700 hover:text-white transition-all">
    <svg class="h-4 w-4 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
    <span>Page de connexion</span>
</a>
@endsection
