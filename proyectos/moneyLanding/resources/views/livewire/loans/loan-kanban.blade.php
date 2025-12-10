<div class="grid md:grid-cols-4 gap-4">
    @foreach ($columns as $key => $label)
        @php
            $columnLoans = $loans[$key] ?? collect();
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-3 flex flex-col gap-3"
            wire:key="column-{{ $key }}" data-kanban-column="{{ $key }}">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-700">{{ $label }}</h3>
                <span class="text-xs text-slate-500">{{ $columnLoans->count() }}</span>
            </div>
            <div class="space-y-2 min-h-[120px]" data-kanban-list>
                @foreach ($columnLoans as $loan)
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg" data-loan-id="{{ $loan->id }}"
                        wire:key="loan-{{ $loan->id }}-{{ $key }}">
                        <div class="text-sm font-semibold text-slate-800">{{ $loan->code }}</div>
                        <div class="text-xs text-slate-500">{{ $loan->client?->name }}</div>
                        <div class="text-xs text-slate-500">Cuota ${{ number_format($loan->installment_amount, 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
