<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 space-y-3">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="flex items-center gap-2">
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar..."
                class="px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 text-sm w-64" />

            <select wire:model.live="status"
                class="px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 text-sm">
                <option value="all">Todos</option>
                <option value="active">Activos</option>
                <option value="delinquent">Morosos</option>
                <option value="completed">Completados</option>
            </select>
        </div>

        <div class="flex gap-2 flex-wrap items-center">
            @foreach (['code' => 'Código', 'client' => 'Cliente', 'principal' => 'Principal', 'interest_rate' => '% interés', 'status' => 'Estado', 'next_due_date' => 'Próx. vencimiento'] as $key => $label)
                <label class="inline-flex items-center gap-2 text-xs bg-slate-100 px-2 py-1 rounded-lg cursor-pointer">
                    <input type="checkbox" wire:click="toggleColumn('{{ $key }}')" @checked(in_array($key, $columns)) />
                    {{ $label }}
                </label>
            @endforeach
            @if (Route::has('reports.loans'))
                <a href="{{ route('reports.loans') }}"
                    class="px-3 py-2 bg-slate-900 text-white rounded-lg text-xs shadow hover:bg-slate-800">Exportar Excel</a>
            @endif
        </div>
    </div>

    <div class="overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500">
                    @if (in_array('code', $columns))
                        <th class="py-2 pr-4">Código</th>
                    @endif
                    @if (in_array('client', $columns))
                        <th class="py-2 pr-4">Cliente</th>
                    @endif
                    @if (in_array('principal', $columns))
                        <th class="py-2 pr-4">Principal</th>
                    @endif
                    @if (in_array('interest_rate', $columns))
                        <th class="py-2 pr-4">% interés</th>
                    @endif
                    @if (in_array('status', $columns))
                        <th class="py-2 pr-4">Estado</th>
                    @endif
                    @if (in_array('next_due_date', $columns))
                        <th class="py-2 pr-4">Próx. vencimiento</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($loans as $loan)
                    <tr class="hover:bg-slate-50">
                        @if (in_array('code', $columns))
                            <td class="py-3 pr-4 font-semibold text-slate-800">
                                <a href="{{ route('loans.show', $loan) }}" class="hover:text-sky-600">{{ $loan->code }}</a>
                            </td>
                        @endif
                        @if (in_array('client', $columns))
                            <td class="py-3 pr-4">{{ $loan->client?->name }}</td>
                        @endif
                        @if (in_array('principal', $columns))
                            <td class="py-3 pr-4">${{ number_format($loan->principal, 2) }}</td>
                        @endif
                        @if (in_array('interest_rate', $columns))
                            <td class="py-3 pr-4">{{ $loan->interest_rate }}%</td>
                        @endif
                        @if (in_array('status', $columns))
                            @php
                                $status = $loan->status instanceof \BackedEnum ? $loan->status->value : $loan->status;
                            @endphp
                            <td class="py-3 pr-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @class([
                                        'bg-emerald-100 text-emerald-700' => $status === 'active',
                                        'bg-amber-100 text-amber-700' => $status === 'delinquent',
                                        'bg-slate-100 text-slate-700' => $status === 'draft',
                                        'bg-blue-100 text-blue-700' => $status === 'completed',
                                        'bg-rose-100 text-rose-700' => $status === 'cancelled',
                                    ])">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        @endif
                        @if (in_array('next_due_date', $columns))
                            <td class="py-3 pr-4">{{ optional($loan->next_due_date)->format('d/m/Y') }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $loans->links() }}
    </div>
</div>
