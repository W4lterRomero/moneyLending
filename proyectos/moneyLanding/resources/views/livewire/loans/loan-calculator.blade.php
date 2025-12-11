<div x-data="{ open: false }" class="relative">
    <button @click="open = true"
        class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg shadow hover:bg-sky-600">
        Calculadora de préstamo
    </button>

    <div x-show="open" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800">Simulador</h3>
                <button @click="open = false" class="text-slate-500 hover:text-slate-700">&times;</button>
            </div>

            <div class="grid md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs text-slate-500">Monto</label>
                    <input type="number" wire:model.live="principal"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-xs text-slate-500">Interés anual (%)</label>
                    <input type="number" step="0.01" wire:model.live="interest"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-xs text-slate-500">Plazo (meses)</label>
                    <input type="number" wire:model.live="term" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-xs text-slate-500">Frecuencia</label>
                    <select wire:model.live="frequency" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option value="daily">Diario</option>
                        <option value="weekly">Semanal</option>
                        <option value="biweekly">Quincenal</option>
                        <option value="monthly">Mensual</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="calculate"
                    class="px-4 py-2 bg-slate-900 text-white rounded-lg shadow hover:bg-slate-800">Calcular</button>
            </div>

            @if ($result)
                <div class="grid md:grid-cols-3 gap-3">
                    <div class="bg-slate-50 rounded-lg p-3">
                        <div class="text-xs text-slate-500">Cuota estimada</div>
                        <div class="text-lg font-semibold text-slate-800">${{ number_format($result['payment'], 2) }}</div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-3">
                        <div class="text-xs text-slate-500">Interés total</div>
                        <div class="text-lg font-semibold text-slate-800">${{ number_format($result['total_interest'], 2) }}</div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-3">
                        <div class="text-xs text-slate-500">Monto total</div>
                        <div class="text-lg font-semibold text-slate-800">${{ number_format($result['total_amount'], 2) }}</div>
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-slate-600 mb-2">Primeras cuotas</div>
                    <div class="overflow-auto max-h-64">
                        <table class="min-w-full text-xs">
                            <thead>
                                <tr class="text-left text-slate-500">
                                    <th class="py-2 pr-4">#</th>
                                    <th class="py-2 pr-4">Fecha</th>
                                    <th class="py-2 pr-4">Cuota</th>
                                    <th class="py-2 pr-4">Capital</th>
                                    <th class="py-2 pr-4">Interés</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($result['schedule'] ?? [] as $row)
                                    <tr>
                                        <td class="py-2 pr-4">{{ $row['number'] }}</td>
                                        <td class="py-2 pr-4">{{ \Illuminate\Support\Carbon::parse($row['due_date'])->format('d/m/Y') }}</td>
                                        <td class="py-2 pr-4">${{ number_format($row['amount'], 2) }}</td>
                                        <td class="py-2 pr-4">${{ number_format($row['principal_amount'], 2) }}</td>
                                        <td class="py-2 pr-4">${{ number_format($row['interest_amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
