@php
    $devName = config('app.dev_name') ?? config('developer.name') ?? env('DEV_NAME');
    $devAdr = config('app.dev_adr') ?? config('developer.adr') ?? env('DEV_ADR');
    $isUrl = $devAdr && filter_var($devAdr, FILTER_VALIDATE_URL);
@endphp

<footer class="mt-auto border-t border-gray-200/80 bg-white/80 py-4 px-4 sm:px-6 lg:px-8 shadow-xs">
    <div class="mx-auto max-w-[1600px] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
        <div class="flex items-center gap-2 text-center sm:text-left flex-wrap justify-center sm:justify-start">
            <span class="font-bold text-gray-700">{{ setting('app_name', 'ArchiDoc') }}</span>
            <span class="text-gray-300">·</span>
            <span>{{ setting('footer_text', '© ' . date('Y') . ' ' . setting('app_name', 'ArchiDoc') . '. Tous droits réservés.') }}</span>
        </div>

        @if($devName || $devAdr)
            <div class="flex items-center gap-1.5 text-gray-500">
                <span class="text-gray-400">Développé par</span>
                @if($isUrl)
                    <a href="{{ $devAdr }}" target="_blank" rel="noopener noreferrer" class="font-extrabold text-brand-700 hover:text-brand-800 hover:underline inline-flex items-center gap-1 transition-colors">
                        <span>{{ $devName ?? $devAdr }}</span>
                        <svg class="h-3 w-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @elseif($devAdr)
                    <span class="font-bold text-gray-700">{{ $devName }}</span>
                    <span class="text-gray-400">({{ $devAdr }})</span>
                @else
                    <span class="font-bold text-gray-700">{{ $devName }}</span>
                @endif
            </div>
        @endif
    </div>
</footer>
