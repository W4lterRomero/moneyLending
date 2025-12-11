<div x-data="{ open: false }" x-init="
    window.addEventListener('keydown', (event) => {
        if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
            open = true;
            event.preventDefault();
        }
        if (event.key === 'Escape') {
            open = false;
        }
    });
    window.addEventListener('open-search', () => open = true);
">
    {{-- Backdrop --}}
    <div x-show="open" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-black/50 z-[100]">
    </div>

    {{-- Modal Content --}}
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.stop
         class="fixed inset-x-4 top-24 mx-auto max-w-2xl z-[101]">
        <div class="bg-white rounded-2xl shadow-2xl p-4 space-y-3">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg wire:loading.remove wire:target="term" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/></svg>
                        <svg wire:loading wire:target="term" class="w-4 h-4 text-sky-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="term" placeholder="Buscar clientes, préstamos, pagos..."
                        class="w-full pl-10 pr-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100 text-sm" 
                        x-ref="searchInput"
                        @keydown.escape="open = false" />
                </div>
                <button type="button" @click="open = false" class="px-3 py-2 text-sm text-slate-600 hover:text-slate-800 border border-slate-200 rounded-lg hover:bg-slate-50">Cerrar</button>
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
