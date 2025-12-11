    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 space-y-3">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar..."
                class="px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 text-sm w-full md:w-64" />

                <select wire:model.live="status"
                class="px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 text-sm w-full md:w-auto">
                    <option value="all">Todos</option>
                    <option value="active">Activos</option>
                    <option value="delinquent">Morosos</option>
                    <option value="completed">Completados</option>
                </select>
            </div>

            <div class="flex gap-2 flex-wrap items-center">
                @foreach (['client' => 'Cliente', 'principal' => 'Monto', 'interest' => 'Interés %', 'frequency' => 'Frecuencia'] as $key => $label)
                    <label class="inline-flex items-center gap-2 text-xs bg-slate-100 px-2 py-1 rounded-lg cursor-pointer">
                        <input type="checkbox" wire:click="toggleColumn('{{ $key }}')" @checked(in_array($key, $columns)) />
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Mobile cards --}}
        <div class="grid gap-3 sm:hidden">
            @foreach ($loans as $loan)
                <div class="card border border-slate-200 rounded-xl p-3 shadow-sm">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <a href="{{ route('loans.show', $loan) }}" class="font-semibold text-slate-800 hover:text-sky-600">{{ $loan->client?->name }}</a>
                            <div class="text-xs text-slate-500">Monto: ${{ number_format($loan->principal, 2) }}</div>
                            <div class="text-xs text-slate-500">Interés: {{ number_format($loan->interest_rate, 2) }}%</div>
                            @php
                                $freqLabels = ['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'];
                            @endphp
                            <div class="text-xs text-slate-500">Frecuencia: {{ $freqLabels[$loan->frequency] ?? $loan->frequency }}</div>
                        </div>
                        <a href="{{ route('loans.show', $loan) }}" class="text-sky-600 text-sm hover:underline">Ver</a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Desktop table --}}
        <div class="overflow-auto hidden sm:block">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500">
                        @if (in_array('client', $columns))
                            <th class="py-2 pr-4">Cliente</th>
                        @endif
                        @if (in_array('principal', $columns))
                            <th class="py-2 pr-4">Monto</th>
                        @endif
                        @if (in_array('interest', $columns))
                            <th class="py-2 pr-4">% Interés</th>
                        @endif
                        @if (in_array('frequency', $columns))
                            <th class="py-2 pr-4">Frecuencia</th>
                        @endif
                        <th class="py-2 pr-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($loans as $loan)
                        <tr class="hover:bg-slate-50">
                            @if (in_array('client', $columns))
                                <td class="py-3 pr-4 font-semibold text-slate-800">
                                    <a href="{{ route('loans.show', $loan) }}" class="hover:text-sky-600">{{ $loan->client?->name }}</a>
                                </td>
                            @endif
                            @if (in_array('principal', $columns))
                                <td class="py-3 pr-4">${{ number_format($loan->principal, 2) }}</td>
                            @endif
                            @if (in_array('interest', $columns))
                                <td class="py-3 pr-4">{{ number_format($loan->interest_rate, 2) }}%</td>
                            @endif
                            @if (in_array('frequency', $columns))
                                <td class="py-3 pr-4">
                                    @php
                                        $freqLabels = ['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'];
                                    @endphp
                                    {{ $freqLabels[$loan->frequency] ?? $loan->frequency }}
                                </td>
                            @endif
                            <td class="py-3 pr-4">
                                <a href="{{ route('loans.show', $loan) }}" class="text-sky-600 hover:text-sky-700 text-xs">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    <div>
        {{ $loans->links() }}
    </div>
</div>
