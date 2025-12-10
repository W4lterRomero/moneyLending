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
        <label class="block text-sm text-slate-600">Monto</label>
        <input type="number" step="0.01" name="principal"
            value="{{ old('principal', optional($loan)->principal ?? 0) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Interés anual (%)</label>
        <input type="number" step="0.01" name="interest_rate"
            value="{{ old('interest_rate', optional($loan)->interest_rate ?? 12) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Plazo (meses)</label>
        <input type="number" name="term_months"
            value="{{ old('term_months', optional($loan)->term_months ?? 12) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Frecuencia</label>
        <select name="frequency" class="w-full px-3 py-2 rounded-lg border border-slate-200">
            @foreach (['monthly' => 'Mensual', 'biweekly' => 'Quincenal', 'weekly' => 'Semanal'] as $key => $label)
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
    <div>
        <label class="block text-sm text-slate-600">Tasa mora (%)</label>
        <input type="number" step="0.01" name="late_fee_rate"
            value="{{ old('late_fee_rate', optional($loan)->late_fee_rate ?? 0) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Tasa penalización (%)</label>
        <input type="number" step="0.01" name="penalty_rate"
            value="{{ old('penalty_rate', optional($loan)->penalty_rate ?? 0) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Estado</label>
        <select name="status" class="w-full px-3 py-2 rounded-lg border border-slate-200">
            @foreach (['draft', 'active', 'delinquent', 'completed'] as $status)
                @php $currentStatus = optional($loan)->status instanceof \BackedEnum ? optional($loan)->status->value : optional($loan)->status; @endphp
                <option value="{{ $status }}" @selected(old('status', $currentStatus ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div>
    <label class="block text-sm text-slate-600">Destino / propósito</label>
    <input type="text" name="purpose" value="{{ old('purpose', optional($loan)->purpose ?? '') }}"
        class="w-full px-3 py-2 rounded-lg border border-slate-200" />
</div>
<div>
    <label class="block text-sm text-slate-600">Notas</label>
    <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200">{{ old('notes', optional($loan)->notes ?? '') }}</textarea>
</div>
