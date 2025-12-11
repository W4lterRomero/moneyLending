    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 space-y-3">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" wire:model.live.debounce.250ms="search" placeholder="Buscar por código o cliente..."
                    class="pl-9 px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 text-sm w-full" />
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-4 h-4">
                        <svg wire:loading.remove wire:target="search" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/></svg>
                        <svg wire:loading wire:target="search" class="w-4 h-4 text-sky-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile cards --}}
        <div class="grid gap-3 sm:hidden" wire:loading.class="opacity-50" wire:target="search">
            @forelse ($loans as $loan)
                <div class="card border border-slate-200 rounded-xl p-3 shadow-sm">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex gap-3">
                            @if($loan->client->photo_path)
                                <img src="{{ Storage::url($loan->client->photo_path) }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $loan->client->name }}">
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold shrink-0">
                                    {{ substr($loan->client->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('loans.show', $loan) }}" class="font-semibold text-slate-800 hover:text-sky-600">{{ $loan->client?->name }}</a>
                                <div class="text-xs text-slate-500">Monto: ${{ number_format($loan->principal, 2) }}</div>
                                <div class="text-xs text-emerald-600 font-medium" title="Total a pagar: ${{ number_format($loan->total_amount, 2) }}">
                                    Ganancia: ${{ number_format($loan->total_amount - $loan->principal, 2) }}
                                </div>
                                <div class="text-xs text-slate-500">Interés: {{ number_format($loan->interest_rate, 2) }}%</div>
                                @php
                                    $freqLabels = ['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'];
                                @endphp
                                <div class="text-xs text-slate-500">Frecuencia: {{ $freqLabels[$loan->frequency] ?? $loan->frequency }}</div>
                            </div>
                        </div>
                        <a href="{{ route('loans.show', $loan) }}" class="text-sky-600 text-sm hover:underline">Ver</a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-500">
                    <p>No se encontraron préstamos.</p>
                </div>
            @endforelse
        </div>

        {{-- Desktop table --}}
        <div class="overflow-auto hidden sm:block" wire:loading.class="opacity-50" wire:target="search">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500">
                        <th class="py-2 pr-4">Cliente</th>
                        <th class="py-2 pr-4">Monto</th>
                        <th class="py-2 pr-4">% Interés</th>
                        <th class="py-2 pr-4">Frecuencia</th>
                        <th class="py-2 pr-4">Ganancia</th>
                        <th class="py-2 pr-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($loans as $loan)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 pr-4 font-semibold text-slate-800">
                                <div class="flex items-center gap-3">
                                    @if($loan->client->photo_path)
                                        <img src="{{ Storage::url($loan->client->photo_path) }}" class="w-8 h-8 rounded-full object-cover" alt="{{ $loan->client->name }}">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold">
                                            {{ substr($loan->client->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <a href="{{ route('loans.show', $loan) }}" class="hover:text-sky-600">{{ $loan->client?->name }}</a>
                                </div>
                            </td>
                            <td class="py-3 pr-4">${{ number_format($loan->principal, 2) }}</td>
                            <td class="py-3 pr-4">{{ number_format($loan->interest_rate, 2) }}%</td>
                            <td class="py-3 pr-4">
                                @php
                                    $freqLabels = ['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'];
                                @endphp
                                {{ $freqLabels[$loan->frequency] ?? $loan->frequency }}
                            </td>
                            <td class="py-3 pr-4 text-emerald-600 font-medium" title="Total a pagar: ${{ number_format($loan->total_amount, 2) }}">
                                ${{ number_format($loan->total_amount - $loan->principal, 2) }}
                            </td>
                            <td class="py-3 pr-4">
                                <a href="{{ route('loans.show', $loan) }}" class="text-sky-600 hover:text-sky-700 text-xs">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                No se encontraron préstamos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    <div>
        {{ $loans->links() }}
    </div>
</div>
