<div class="grid md:grid-cols-4 gap-4" data-kanban-id="{{ $this->getId() }}">
    @foreach ($columns as $key => $label)
        @php
            $columnLoans = $loans[$key] ?? collect();
            $total = $columnCounts[$key] ?? 0;
            $colors = [
                'draft' => 'bg-slate-50 text-slate-700 border-slate-200',
                'active' => 'bg-sky-50 text-sky-700 border-sky-100',
                'delinquent' => 'bg-amber-50 text-amber-700 border-amber-100',
                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            ];
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-3 flex flex-col gap-3"
            wire:key="column-{{ $key }}" data-kanban-column="{{ $key }}">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ str_replace('text', 'bg', $colors[$key] ?? 'bg-slate-200') }}"></span>
                    <h3 class="text-sm font-semibold text-slate-700">{{ $label }}</h3>
                </div>
                <span class="text-xs text-slate-500">{{ $total }}</span>
            </div>
            <div class="space-y-2 min-h-[180px] max-h-[420px] overflow-y-auto pr-1" data-kanban-list>
                @forelse ($columnLoans as $loan)
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg hover:border-sky-200 transition" data-loan-id="{{ $loan->id }}"
                        wire:key="loan-{{ $loan->id }}-{{ $key }}">
                        <div class="flex items-center justify-between gap-2">
                            <div class="text-sm font-semibold text-slate-800">{{ $loan->code }}</div>
                            <span class="text-[10px] px-2 py-1 rounded-full {{ $colors[$key] ?? 'bg-slate-100 text-slate-700' }}">Cuota</span>
                        </div>
                        <div class="text-xs text-slate-500 truncate">{{ $loan->client?->name }}</div>
                        <div class="text-xs text-slate-500">USD {{ number_format($loan->installment_amount, 2) }}</div>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 text-center py-6 border border-dashed border-slate-200 rounded-lg">
                        Arrastra préstamos aquí
                    </div>
                @endforelse
            </div>
            @if($total > $columnLoans->count())
                <div class="text-[11px] text-slate-500 text-center border border-dashed border-slate-200 rounded-lg py-2">
                    +{{ $total - $columnLoans->count() }} más (usa filtros o tabla para ver todos)
                </div>
            @endif
        </div>
    @endforeach
</div>
