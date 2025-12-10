@csrf
<div class="grid md:grid-cols-2 gap-3">
    <div>
        <label class="block text-sm text-slate-600">Préstamo</label>
        <select name="loan_id" x-data @change="$dispatch('loan-changed', $event.target.value)"
            class="w-full px-3 py-2 rounded-lg border border-slate-200">
            @foreach ($loans as $loan)
                <option value="{{ $loan->id }}"
                    @selected(old('loan_id', optional($payment)->loan_id ?? request('loan_id')) == $loan->id)>
                    {{ $loan->code }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm text-slate-600">Cuota</label>
        <select name="installment_id" class="w-full px-3 py-2 rounded-lg border border-slate-200">
            <option value="">Sin asignar</option>
            @foreach ($installments as $installment)
                <option value="{{ $installment->id }}"
                    @selected(old('installment_id', optional($payment)->installment_id ?? null) == $installment->id)>
                    #{{ $installment->number }} - {{ $installment->due_date->format('d/m/Y') }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm text-slate-600">Fecha de pago</label>
        <input type="date" name="paid_at"
            value="{{ old('paid_at', optional(optional($payment)->paid_at ?? now())->toDateString()) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Monto</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', optional($payment)->amount ?? 0) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Capital</label>
        <input type="number" step="0.01" name="principal_amount"
            value="{{ old('principal_amount', optional($payment)->principal_amount ?? 0) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Interés</label>
        <input type="number" step="0.01" name="interest_amount"
            value="{{ old('interest_amount', optional($payment)->interest_amount ?? 0) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
    <div>
        <label class="block text-sm text-slate-600">Método</label>
        <select name="method" class="w-full px-3 py-2 rounded-lg border border-slate-200">
            @foreach (['cash' => 'Efectivo', 'transfer' => 'Transferencia', 'card' => 'Tarjeta', 'deposit' => 'Depósito'] as $key => $label)
                <option value="{{ $key }}" @selected(old('method', optional($payment)->method ?? 'cash') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm text-slate-600">Referencia</label>
        <input type="text" name="reference" value="{{ old('reference', optional($payment)->reference ?? '') }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200" />
    </div>
</div>
<div>
    <label class="block text-sm text-slate-600">Notas</label>
    <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200">{{ old('notes', optional($payment)->notes ?? '') }}</textarea>
</div>
