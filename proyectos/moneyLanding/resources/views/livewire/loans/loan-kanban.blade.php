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
            <div class="space-y-2 min-h-[140px]" data-kanban-list>
                @forelse ($columnLoans as $loan)
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg" data-loan-id="{{ $loan->id }}"
                        wire:key="loan-{{ $loan->id }}-{{ $key }}">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-slate-800">{{ $loan->code }}</div>
                            <span class="text-[10px] px-2 py-1 rounded-full bg-sky-50 text-sky-700">Cuota</span>
                        </div>
                        <div class="text-xs text-slate-500">{{ $loan->client?->name }}</div>
                        <div class="text-xs text-slate-500">USD {{ number_format($loan->installment_amount, 2) }}</div>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 text-center py-6 border border-dashed border-slate-200 rounded-lg">
                        Arrastra préstamos aquí
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
