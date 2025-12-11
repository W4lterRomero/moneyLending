<div x-data="{ open: false }" x-init="
    window.addEventListener('keydown', (event) => {
        if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
            open = true;
            event.preventDefault();
        }
    });
    window.addEventListener('open-search', () => open = true);
">
    <div x-show="open" x-cloak class="fixed inset-0 bg-black/40 z-50 flex items-start justify-center pt-24">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-4 space-y-3 card">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-4 h-4">
                        <svg wire:loading.remove wire:target="term" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/></svg>
                        <svg wire:loading wire:target="term" class="w-4 h-4 text-sky-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="term" placeholder="Buscar clientes, préstamos, pagos..."
                        class="input-apple w-full pl-9" />
                </div>
                <button @click="open = false" class="btn-outline-apple">Cerrar</button>
            </div>

            <div class="max-h-80 overflow-auto divide-y divide-slate-100">
                @if (strlen(trim($term)) < $minLength)
                    <div class="py-3 text-sm text-slate-500 text-center">Escribe al menos {{ $minLength }} caracteres para buscar.</div>
                @else
                    <div wire:loading wire:target="term" class="py-8 text-center text-slate-500">
                        Buscando...
                    </div>
                    <div wire:loading.remove wire:target="term">
                        @forelse ($results as $result)
                            <a href="{{ $result['url'] ?? '#' }}" class="block py-3 hover:bg-slate-50 px-2 rounded-lg transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-slate-800">{{ $result['title'] }}</div>
                                    <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $result['type'] }}</div>
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $result['subtitle'] }}</div>
                            </a>
                        @empty
                            <div class="py-8 text-center text-slate-500">
                                No se encontraron resultados.
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
