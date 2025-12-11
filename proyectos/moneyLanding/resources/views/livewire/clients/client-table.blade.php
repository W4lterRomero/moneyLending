<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
        <div class="flex items-center gap-2 flex-1">
            <div class="relative flex items-center justify-center w-4 h-4">
                <svg wire:loading.remove wire:target="search" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/></svg>
                <svg wire:loading wire:target="search" class="w-4 h-4 text-sky-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
            <input type="text" wire:model.live.debounce.250ms="search" placeholder="Buscar por nombre, empresa, documento..."
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100 text-sm" />
        </div>
    </div>

    <div class="grid gap-3 sm:hidden" wire:loading.class="opacity-50" wire:target="search">
        @forelse ($clients as $client)
            <div class="card border border-slate-200 rounded-xl p-3 shadow-sm">
                <div class="flex items-center gap-3">
                    @if($client->photo_path)
                        <img src="{{ Storage::url($client->photo_path) }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $client->name }}">
                    @else
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-sky-50 text-sky-700 text-sm font-bold">
                            {{ strtoupper(substr($client->name, 0, 1)) }}
                        </span>
                    @endif
                    <div class="flex-1">
                        <a href="{{ route('clients.show', $client) }}" class="font-semibold text-slate-800 hover:text-sky-600">{{ $client->name }}</a>
                        <div class="text-xs text-slate-500">{{ $client->email }}</div>
                        <div class="text-xs text-slate-500">{{ $client->phone }}</div>
                    </div>
                    <span class="status-badge {{ $client->status }}">{{ ucfirst($client->status) }}</span>
                </div>
                <div class="flex items-center gap-3 mt-3 text-sm">
                    <a href="{{ route('clients.show', $client) }}" class="text-sky-600 hover:underline">Ver</a>
                    <a href="{{ route('clients.edit', $client) }}" class="text-slate-500 hover:text-slate-700">Editar</a>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-500">
                <p>No se encontraron clientes.</p>
            </div>
        @endforelse
    </div>

    <div class="overflow-auto hidden sm:block" wire:loading.class="opacity-50" wire:target="search">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500">
                    <th class="py-2 pr-4">Nombre</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4">Teléfono</th>
                    <th class="py-2 pr-4">Estado</th>
                    <th class="py-2 pr-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($clients as $client)
                    <tr>
                        <td class="py-3 pr-4 font-semibold text-slate-800">
                            <a href="{{ route('clients.show', $client) }}" class="flex items-center gap-2">
                                @if($client->photo_path)
                                    <img src="{{ Storage::url($client->photo_path) }}" class="w-8 h-8 rounded-full object-cover" alt="{{ $client->name }}">
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-sky-50 text-sky-700 text-sm font-bold">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </span>
                                @endif
                                <span>{{ $client->name }}</span>
                            </a>
                        </td>
                        <td class="py-3 pr-4">{{ $client->email }}</td>
                        <td class="py-3 pr-4">{{ $client->phone }}</td>
                        <td class="py-3 pr-4">
                            <span class="status-badge {{ $client->status }}">{{ ucfirst($client->status) }}</span>
                        </td>
                        <td class="py-3 pr-4">
                            <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center gap-1 text-sky-600 hover:underline text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.414 1.414-9.9 9.9-2.475.353.353-2.475zM19.5 7.125L17.375 5 19.5 7.125z"/></svg>
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">
                            No se encontraron clientes.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-1">
        {{ $clients->links() }}
    </div>
</div>
