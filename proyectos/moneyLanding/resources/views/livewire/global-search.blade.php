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
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-4 space-y-3">
            <div class="flex items-center gap-2">
                <input type="text" wire:model.live.debounce.300ms="term" placeholder="Buscar clientes, préstamos, pagos..."
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
                <button @click="open = false" class="text-slate-500 hover:text-slate-700 text-sm">Cerrar</button>
            </div>

            <div class="max-h-80 overflow-auto divide-y divide-slate-100">
                @forelse ($results as $result)
                    <a href="{{ $result['url'] ?? '#' }}" class="block py-3 hover:bg-slate-50">
                        <div class="text-xs uppercase text-slate-400">{{ $result['type'] }}</div>
                        <div class="text-sm font-semibold text-slate-800">{{ $result['title'] }}</div>
                        <div class="text-xs text-slate-500">{{ $result['subtitle'] }}</div>
                    </a>
                @empty
                    <div class="py-3 text-sm text-slate-500">Sin resultados</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
