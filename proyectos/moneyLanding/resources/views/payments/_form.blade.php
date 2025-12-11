@csrf
<div class="grid md:grid-cols-2 gap-3">
    <div>
        <label class="block text-sm text-slate-600">Cliente / Préstamo</label>
        <select name="loan_id" x-data @change="$dispatch('loan-changed', $event.target.value)"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 @error('loan_id') border-red-500 @enderror">
            @foreach ($loans as $loan)
                <option value="{{ $loan->id }}"
                    @selected(old('loan_id', optional($payment)->loan_id ?? request('loan_id')) == $loan->id)>
                    {{ $loan->client?->name }} — ${{ number_format($loan->principal, 2) }}
                </option>
            @endforeach
        </select>
        @error('loan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-slate-600">Fecha de pago</label>
        <input type="date" name="paid_at"
            value="{{ old('paid_at', optional(optional($payment)->paid_at ?? now())->toDateString()) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 @error('paid_at') border-red-500 @enderror" required />
        @error('paid_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-slate-600">Monto del pago</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', optional($payment)->amount ?? 0) }}"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 @error('amount') border-red-500 @enderror" placeholder="0.00" required />
        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-slate-600">Método de pago</label>
        <select name="method" class="w-full px-3 py-2 rounded-lg border border-slate-200 @error('method') border-red-500 @enderror">
            @foreach (['cash' => 'Efectivo', 'transfer' => 'Transferencia', 'card' => 'Tarjeta', 'deposit' => 'Depósito'] as $key => $label)
                <option value="{{ $key }}" @selected(old('method', optional($payment)->method ?? 'cash') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-slate-600">Foto del pago <span class="text-xs text-slate-400">(opcional)</span></label>
        <input type="file" name="photo" accept="image/*"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 @error('photo') border-red-500 @enderror" />
        @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        @if (optional($payment)->photo_path)
            <a href="{{ Storage::url($payment->photo_path) }}" target="_blank" class="text-xs text-sky-600 hover:underline mt-1 inline-flex items-center gap-1">
                <x-icon name="photo" class="w-3 h-3" /> Ver foto actual
            </a>
        @endif
    </div>
    <div>
        <label class="block text-sm text-slate-600">Comprobante de pago <span class="text-xs text-slate-400">(opcional, PDF o imagen)</span></label>
        <input type="file" name="receipt" accept="image/*,application/pdf"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 @error('receipt') border-red-500 @enderror" />
        @error('receipt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        @if (optional($payment)->receipt_path)
            <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" class="text-xs text-emerald-600 hover:underline mt-1 inline-flex items-center gap-1">
                <x-icon name="document" class="w-3 h-3" /> Ver comprobante cargado
            </a>
        @endif
    </div>
</div>
<div>
    <label class="block text-sm text-slate-600">Notas</label>
    <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200 @error('notes') border-red-500 @enderror" placeholder="Información adicional sobre el pago...">{{ old('notes', optional($payment)->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Campos ocultos con valores por defecto --}}
<input type="hidden" name="installment_id" value="" />
<input type="hidden" name="principal_amount" value="0" />
<input type="hidden" name="interest_amount" value="0" />
