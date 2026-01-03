<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm p-4 space-y-4">
    {{-- Header with Date Range Badge --}}
    <div class="flex flex-col gap-3">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Pagos</h2>
            {{-- Date Range Badge --}}
            <div class="flex items-center gap-2 px-3 py-1.5 bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 rounded-lg text-xs font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $this->getDateRangeLabel() }}</span>
            </div>
        </div>
        
        {{-- Date Range Selector --}}
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex gap-1 p-1 bg-slate-100 dark:bg-slate-700 rounded-lg">
                <button type="button" wire:click="$set('dateRange', 'this_month')" 
                    class="px-3 py-1.5 text-xs rounded-md transition-colors font-medium {{ $dateRange === 'this_month' ? 'bg-sky-500 text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                    Este mes
                </button>
                <button type="button" wire:click="$set('dateRange', 'last_30')" 
                    class="px-3 py-1.5 text-xs rounded-md transition-colors font-medium {{ $dateRange === 'last_30' ? 'bg-sky-500 text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                    Últimos 30d
                </button>
                <button type="button" wire:click="$set('dateRange', 'all')" 
                    class="px-3 py-1.5 text-xs rounded-md transition-colors font-medium {{ $dateRange === 'all' ? 'bg-sky-500 text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                    Todo
                </button>
                <button type="button" wire:click="$set('dateRange', 'custom')" 
                    class="px-3 py-1.5 text-xs rounded-md transition-colors font-medium {{ $dateRange === 'custom' ? 'bg-sky-500 text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                    Rango
                </button>
            </div>
            
            {{-- Custom Date Range Inputs --}}
            @if($dateRange === 'custom')
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="startDate" 
                        class="px-2 py-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500" />
                    <span class="text-slate-400 text-xs">→</span>
                    <input type="date" wire:model.live="endDate" 
                        class="px-2 py-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500" />
                </div>
            @endif
        </div>

        {{-- Search --}}
        <div class="flex items-center gap-2">
            <div class="relative flex items-center justify-center w-4 h-4">
                <svg wire:loading.remove wire:target="search" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/></svg>
                <svg wire:loading wire:target="search" class="w-4 h-4 text-sky-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
            <input type="text" wire:model.live.debounce.250ms="search" placeholder="Buscar por referencia o cliente..."
                class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring focus:ring-sky-100 dark:focus:ring-sky-900 text-sm" />
        </div>
    </div>


    {{-- Mobile cards --}}
    <div class="grid gap-3 sm:hidden" wire:loading.class="opacity-50" wire:target="search">
        @forelse ($payments as $payment)
            @php
                $method = $payment->method instanceof \BackedEnum ? $payment->method->value : $payment->method;
            @endphp
            <div class="card border border-slate-200 rounded-xl p-3 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex-1">
                        <a href="{{ route('payments.show', $payment) }}" class="font-semibold text-slate-800 hover:text-sky-600 block">
                            ${{ number_format($payment->amount, 2) }}
                        </a>
                        <div class="text-xs text-slate-500">{{ $payment->paid_at->format('d/m/Y') }}</div>
                        <a href="{{ route('loans.show', $payment->loan) }}" class="text-xs text-sky-600 hover:underline">
                            {{ $payment->loan?->client?->name ?? 'Préstamo' }}
                        </a>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @class([
                            'bg-emerald-50 text-emerald-700' => $method === 'cash' || $method === 'transfer',
                            'bg-indigo-50 text-indigo-700' => $method === 'card',
                            'bg-slate-100 text-slate-700' => !in_array($method, ['cash','transfer','card']),
                        ])">
                        {{ ucfirst($method) }}
                    </span>
                </div>
                <div class="flex items-center gap-3 mt-3 text-sm">
                    <a href="{{ route('payments.show', $payment) }}" class="text-sky-600 hover:underline">Ver</a>
                    <a href="{{ route('payments.edit', $payment) }}" class="text-slate-500 hover:text-slate-700">Editar</a>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-500">
                <p>No se encontraron pagos.</p>
            </div>
        @endforelse
    </div>

    {{-- Desktop table --}}
    <div class="overflow-auto hidden sm:block" wire:loading.class="opacity-50" wire:target="search">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500">
                    <th class="py-2 pr-4">Préstamo</th>
                    <th class="py-2 pr-4">Fecha</th>
                    <th class="py-2 pr-4">Monto</th>
                    <th class="py-2 pr-4">Método</th>
                    <th class="py-2 pr-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($payments as $payment)
                    @php
                        $method = $payment->method instanceof \BackedEnum ? $payment->method->value : $payment->method;
                    @endphp
                    <tr>
                        <td class="py-3 pr-4">
                            <a href="{{ route('loans.show', $payment->loan) }}" class="inline-flex items-center gap-2 text-sky-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 10h18M5 14h14M7 18h10"/></svg>
                                <span class="font-medium">{{ $payment->loan?->client?->name ?? 'Préstamo' }}</span>
                                <span class="text-slate-500">${{ number_format($payment->loan?->principal ?? 0, 2) }}</span>
                            </a>
                        </td>
                        <td class="py-3 pr-4">{{ $payment->paid_at->format('d/m/Y') }}</td>
                        <td class="py-3 pr-4 font-semibold text-slate-800">${{ number_format($payment->amount, 2) }}</td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @class([
                                    'bg-emerald-50 text-emerald-700' => $method === 'cash' || $method === 'transfer',
                                    'bg-indigo-50 text-indigo-700' => $method === 'card',
                                    'bg-slate-100 text-slate-700' => !in_array($method, ['cash','transfer','card']),
                                ])">
                                {{ ucfirst($method) }}
                            </span>
                        </td>
                        <td class="py-3 pr-4">
                            <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center gap-1 text-sm text-sky-600 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">
                            No se encontraron pagos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-1">
        {{ $payments->links() }}
    </div>
</div>
