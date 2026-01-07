<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 space-y-3">
    {{-- Filters and Controls --}}
    <div class="space-y-3">
        {{-- Row 1: Search --}}
        <div class="relative w-full">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/></svg>
            <input type="text" wire:model.live.debounce.250ms="search" placeholder="Buscar por código o cliente..."
                class="w-full pl-10 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:border-sky-400 focus:ring focus:ring-sky-100 dark:focus:ring-sky-900 text-sm" />
        </div>

        {{-- Row 2: Filters - Scrollable on mobile --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-1 px-1">
            {{-- Frequency Filter --}}
            <select wire:model.live="filterFrequency" 
                class="text-xs rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 py-2 px-2 sm:px-3 focus:ring-sky-500 whitespace-nowrap shrink-0">
                <option value="">Frecuencia</option>
                <option value="daily">Diario</option>
                <option value="weekly">Semanal</option>
                <option value="biweekly">Quincenal</option>
                <option value="monthly">Mensual</option>
            </select>

            {{-- Per Page --}}
            <select wire:model.live="perPage" 
                class="text-xs rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 py-2 px-2 sm:px-3 focus:ring-sky-500 whitespace-nowrap shrink-0">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
            </select>

            {{-- Column Visibility Toggle --}}
            <div x-data="{ open: false }" class="relative shrink-0">
                <button @click="open = !open" type="button" 
                    class="flex items-center gap-1 px-2 sm:px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="hidden sm:inline">Columnas</span>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 py-2 z-50">
                    <div class="px-3 py-1 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Mostrar columnas</div>
                    @foreach(['cliente' => 'Cliente', 'monto' => 'Monto', 'interes' => '% Interés', 'frecuencia' => 'Frecuencia', 'ganancia' => 'Ganancia', 'acciones' => 'Acciones'] as $key => $label)
                        <label class="flex items-center gap-2 px-3 py-1.5 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">
                            <input type="checkbox" wire:click="toggleColumn('{{ $key }}')" {{ $visibleColumns[$key] ? 'checked' : '' }}
                                class="rounded border-slate-300 dark:border-slate-600 text-sky-500 focus:ring-sky-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="grid gap-3 sm:hidden" wire:loading.class="opacity-50" wire:target="search, filterFrequency, perPage">
        @forelse ($loans as $loan)
            <div class="card border border-slate-200 dark:border-slate-700 rounded-xl p-3 shadow-sm bg-white dark:bg-slate-800">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex gap-3">
                        @if($loan->client->photo_path)
                            <img src="{{ Storage::url($loan->client->photo_path) }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $loan->client->name }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-600 flex items-center justify-center text-slate-500 dark:text-slate-300 text-xs font-bold shrink-0">
                                {{ substr($loan->client->name, 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('loans.show', $loan) }}" class="font-semibold text-slate-800 dark:text-white hover:text-sky-600 dark:hover:text-sky-400">{{ $loan->client?->name }}</a>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Monto: ${{ number_format($loan->principal, 2) }}</div>
                            <div class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                                Ganancia: ${{ number_format($loan->total_amount - $loan->principal, 2) }}
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Interés: {{ number_format($loan->interest_rate, 2) }}%</div>
                            @php
                                $freqLabels = ['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'];
                            @endphp
                            <div class="text-xs text-slate-500 dark:text-slate-400">Frecuencia: {{ $freqLabels[$loan->frequency] ?? $loan->frequency }}</div>
                        </div>
                    </div>
                    <a href="{{ route('loans.show', $loan) }}" class="text-sky-600 dark:text-sky-400 text-sm hover:underline">Ver</a>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                <p>No se encontraron préstamos.</p>
            </div>
        @endforelse
    </div>

    {{-- Desktop table --}}
    <div class="overflow-auto hidden sm:block" wire:loading.class="opacity-50" wire:target="search, filterFrequency, perPage, sortBy">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 dark:text-slate-400">
                    @if($visibleColumns['cliente'])
                        <th class="py-2 pr-4 cursor-pointer hover:text-sky-600 dark:hover:text-sky-400 select-none" wire:click="sortBy('client_id')">
                            Cliente
                            @if($sortField === 'client_id')
                                <span class="text-sky-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    @endif
                    @if($visibleColumns['monto'])
                        <th class="py-2 pr-4 cursor-pointer hover:text-sky-600 dark:hover:text-sky-400 select-none" wire:click="sortBy('principal')">
                            Monto
                            @if($sortField === 'principal')
                                <span class="text-sky-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    @endif
                    @if($visibleColumns['interes'])
                        <th class="py-2 pr-4 cursor-pointer hover:text-sky-600 dark:hover:text-sky-400 select-none" wire:click="sortBy('interest_rate')">
                            % Interés
                            @if($sortField === 'interest_rate')
                                <span class="text-sky-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    @endif
                    @if($visibleColumns['frecuencia'])
                        <th class="py-2 pr-4 cursor-pointer hover:text-sky-600 dark:hover:text-sky-400 select-none" wire:click="sortBy('frequency')">
                            Frecuencia
                            @if($sortField === 'frequency')
                                <span class="text-sky-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    @endif
                    @if($visibleColumns['ganancia'])
                        <th class="py-2 pr-4 cursor-pointer hover:text-sky-600 dark:hover:text-sky-400 select-none" wire:click="sortBy('total_amount')">
                            Ganancia
                            @if($sortField === 'total_amount')
                                <span class="text-sky-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    @endif
                    @if($visibleColumns['acciones'])
                        <th class="py-2 pr-4">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse ($loans as $loan)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-200">
                        @if($visibleColumns['cliente'])
                            <td class="py-3 pr-4 font-semibold text-slate-800 dark:text-white">
                                <div class="flex items-center gap-3">
                                    @if($loan->client->photo_path)
                                        <img src="{{ Storage::url($loan->client->photo_path) }}" class="w-8 h-8 rounded-full object-cover" alt="{{ $loan->client->name }}">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-600 flex items-center justify-center text-slate-500 dark:text-slate-300 text-xs font-bold">
                                            {{ substr($loan->client->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <a href="{{ route('loans.show', $loan) }}" class="hover:text-sky-600 dark:hover:text-sky-400">{{ $loan->client?->name }}</a>
                                </div>
                            </td>
                        @endif
                        @if($visibleColumns['monto'])
                            <td class="py-3 pr-4 text-slate-700 dark:text-slate-300">${{ number_format($loan->principal, 2) }}</td>
                        @endif
                        @if($visibleColumns['interes'])
                            <td class="py-3 pr-4 text-slate-700 dark:text-slate-300">{{ number_format($loan->interest_rate, 2) }}%</td>
                        @endif
                        @if($visibleColumns['frecuencia'])
                            <td class="py-3 pr-4 text-slate-700 dark:text-slate-300">
                                @php
                                    $freqLabels = ['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ 
                                    $loan->frequency === 'monthly' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 
                                    ($loan->frequency === 'biweekly' ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300' : 
                                    ($loan->frequency === 'weekly' ? 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300' : 
                                    'bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-300')) }}">
                                    {{ $freqLabels[$loan->frequency] ?? $loan->frequency }}
                                </span>
                            </td>
                        @endif
                        @if($visibleColumns['ganancia'])
                            <td class="py-3 pr-4 text-emerald-600 dark:text-emerald-400 font-medium" title="Total a pagar: ${{ number_format($loan->total_amount, 2) }}">
                                ${{ number_format($loan->total_amount - $loan->principal, 2) }}
                            </td>
                        @endif
                        @if($visibleColumns['acciones'])
                            <td class="py-3 pr-4">
                                <a href="{{ route('loans.show', $loan) }}" class="text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 text-xs font-medium">Ver</a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count(array_filter($visibleColumns)) }}" class="py-8 text-center text-slate-500 dark:text-slate-400">
                            No se encontraron préstamos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div>
        @if($loans instanceof \Illuminate\Pagination\LengthAwarePaginator && $loans->hasPages())
            {{ $loans->links() }}
        @endif
    </div>
</div>
