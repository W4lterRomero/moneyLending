@csrf
<div class="grid md:grid-cols-2 gap-3">
    <div>
        <label class="block text-sm text-slate-600">Cliente</label>
        <select name="client_id" class="w-full px-3 py-2 rounded-lg border border-slate-200">
            @foreach ($clients as $client)
                <option value="{{ $client->id }}"
                    @selected(old('client_id', optional($loan)->client_id ?? request('client_id')) == $client->id)>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm text-slate-600">Monto del Préstamo</label>
        <input type="number" step="0.01" name="principal"
            value="{{ old('principal', optional($loan)->principal ?? 0) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="0.00" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Frecuencia de pago</label>
        <select name="frequency" class="w-full px-3 py-2 rounded-lg border border-slate-200">
            @foreach (['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'] as $key => $label)
                <option value="{{ $key }}" @selected(old('frequency', optional($loan)->frequency ?? 'monthly') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm text-slate-600">Fecha inicio</label>
        <input type="date" name="start_date"
            value="{{ old('start_date', optional(optional($loan)->start_date ?? now())->toDateString()) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
</div>
<div>
    <label class="block text-sm text-slate-600">Notas</label>
    <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="Información adicional del préstamo...">{{ old('notes', optional($loan)->notes ?? '') }}</textarea>
</div>

{{-- Campos ocultos con valores por defecto --}}
<input type="hidden" name="term_months" value="{{ old('term_months', optional($loan)->term_months ?? 12) }}" />
<input type="hidden" name="interest_rate" value="{{ old('interest_rate', optional($loan)->interest_rate ?? 12) }}" />
<input type="hidden" name="late_fee_rate" value="0" />
<input type="hidden" name="penalty_rate" value="0" />
<input type="hidden" name="status" value="{{ old('status', optional($loan)->status instanceof \BackedEnum ? optional($loan)->status->value : (optional($loan)->status ?? 'active')) }}" />
