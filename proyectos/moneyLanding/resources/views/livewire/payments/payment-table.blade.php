<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
        <div class="flex items-center gap-2 flex-1">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por cliente..."
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100 text-sm" />
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="grid gap-3 sm:hidden">
        @foreach ($payments as $payment)
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
        @endforeach
    </div>

    {{-- Desktop table --}}
    <div class="overflow-auto hidden sm:block">
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
                @foreach ($payments as $payment)
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
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-1">
        {{ $payments->links() }}
    </div>
</div>
