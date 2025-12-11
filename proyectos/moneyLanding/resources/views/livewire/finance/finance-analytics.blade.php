<div x-data="{ 
    hideBalances: localStorage.getItem('hideBalances') === 'true',
    toggleHide() {
        this.hideBalances = !this.hideBalances;
        localStorage.setItem('hideBalances', this.hideBalances);
    }
}" class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('finance.index') }}" class="text-xs text-sky-500 hover:text-sky-600 flex items-center gap-1">
                ← Volver a Finanzas
            </a>
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white">Análisis Financiero</h1>
        </div>
        <button type="button" @click="toggleHide()" 
            class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition-colors">
            <template x-if="!hideBalances">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </template>
            <template x-if="hideBalances">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </template>
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2">
        {{-- Period Filter --}}
        <div class="flex gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg">
            <button type="button" wire:click="setPeriod('week')" 
                class="px-3 py-1.5 text-xs rounded-md transition-colors {{ $period === 'week' ? 'bg-white dark:bg-slate-700 shadow text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                Semana
            </button>
            <button type="button" wire:click="setPeriod('month')" 
                class="px-3 py-1.5 text-xs rounded-md transition-colors {{ $period === 'month' ? 'bg-white dark:bg-slate-700 shadow text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                Mes
            </button>
            <button type="button" wire:click="setPeriod('year')" 
                class="px-3 py-1.5 text-xs rounded-md transition-colors {{ $period === 'year' ? 'bg-white dark:bg-slate-700 shadow text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                Año
            </button>
            <button type="button" wire:click="setPeriod('all')" 
                class="px-3 py-1.5 text-xs rounded-md transition-colors {{ $period === 'all' ? 'bg-white dark:bg-slate-700 shadow text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                Todo
            </button>
        </div>
        
        {{-- Account Filter --}}
        <select wire:model.live="accountFilter" 
            class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300">
            <option value="">Todas las cuentas</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <div class="text-xs text-slate-500 dark:text-slate-400 uppercase">Balance Total</div>
            <div class="text-2xl font-bold {{ $totalBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                <template x-if="!hideBalances"><span>${{ number_format($totalBalance, 2) }}</span></template>
                <template x-if="hideBalances"><span class="text-slate-400">****</span></template>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <div class="text-xs text-slate-500 dark:text-slate-400 uppercase">Ingresos</div>
            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                <template x-if="!hideBalances"><span>+${{ number_format($totalIncome, 2) }}</span></template>
                <template x-if="hideBalances"><span class="text-slate-400">****</span></template>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <div class="text-xs text-slate-500 dark:text-slate-400 uppercase">Gastos</div>
            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                <template x-if="!hideBalances"><span>-${{ number_format($totalExpense, 2) }}</span></template>
                <template x-if="hideBalances"><span class="text-slate-400">****</span></template>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <div class="text-xs text-slate-500 dark:text-slate-400 uppercase">Flujo Neto</div>
            <div class="text-2xl font-bold {{ $netFlow >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                <template x-if="!hideBalances"><span>{{ $netFlow >= 0 ? '+' : '' }}${{ number_format($netFlow, 2) }}</span></template>
                <template x-if="hideBalances"><span class="text-slate-400">****</span></template>
            </div>
        </div>
    </div>

    {{-- Simple Bar Chart (CSS-based, no JS library) --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-4">
            Flujo de {{ $period === 'year' ? 'Meses' : 'Días' }}
        </h3>
        <div class="flex items-end gap-1 h-32 overflow-x-auto pb-2">
            @php
                $maxValue = collect($dailyData)->map(fn($d) => max($d['income'], $d['expense']))->max() ?: 1;
            @endphp
            @foreach($dailyData as $day)
                <div class="flex flex-col items-center gap-1 min-w-[2rem]">
                    <div class="flex gap-0.5 items-end h-24">
                        @if($day['income'] > 0)
                            <div class="w-2 rounded-t transition-all duration-300" 
                                style="height: {{ ($day['income'] / $maxValue) * 100 }}%; background-color: #10b981;"
                                title="Ingreso: ${{ number_format($day['income'], 0) }}"></div>
                        @endif
                        @if($day['expense'] > 0)
                            <div class="w-2 rounded-t transition-all duration-300" 
                                style="height: {{ ($day['expense'] / $maxValue) * 100 }}%; background-color: #ef4444;"
                                title="Gasto: ${{ number_format($day['expense'], 0) }}"></div>
                        @endif
                        @if($day['income'] == 0 && $day['expense'] == 0)
                            <div class="w-2 h-1 bg-slate-200 dark:bg-slate-600 rounded"></div>
                        @endif
                    </div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
        <div class="flex gap-4 mt-2 text-xs text-slate-500 dark:text-slate-400">
            <span class="flex items-center gap-1">
                <span class="w-2 h-2 rounded" style="background-color: #10b981;"></span> Ingresos
            </span>
            <span class="flex items-center gap-1">
                <span class="w-2 h-2 rounded" style="background-color: #ef4444;"></span> Gastos
            </span>
        </div>
    </div>

    {{-- Category Breakdown --}}
    <div class="grid md:grid-cols-2 gap-4">
        {{-- Income by Category --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full" style="background-color: #10b981;"></span>
                Top Ingresos
            </h3>
            @if($incomeByCategory->isEmpty())
                <p class="text-slate-400 text-sm">Sin ingresos en este período</p>
            @else
                <div class="space-y-2">
                    @foreach($incomeByCategory as $category => $amount)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-700 dark:text-slate-300 truncate">{{ $category }}</span>
                            <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                <template x-if="!hideBalances"><span>${{ number_format($amount, 0) }}</span></template>
                                <template x-if="hideBalances"><span class="text-slate-400">****</span></template>
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full" style="width: {{ ($amount / $totalIncome) * 100 }}%; background-color: #10b981;"></div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        {{-- Expense by Category --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full" style="background-color: #ef4444;"></span>
                Top Gastos
            </h3>
            @if($expenseByCategory->isEmpty())
                <p class="text-slate-400 text-sm">Sin gastos en este período</p>
            @else
                <div class="space-y-2">
                    @foreach($expenseByCategory as $category => $amount)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-700 dark:text-slate-300 truncate">{{ $category }}</span>
                            <span class="text-sm font-medium text-red-600 dark:text-red-400">
                                <template x-if="!hideBalances"><span>${{ number_format($amount, 0) }}</span></template>
                                <template x-if="hideBalances"><span class="text-slate-400">****</span></template>
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full" style="width: {{ ($amount / $totalExpense) * 100 }}%; background-color: #ef4444;"></div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Stats Footer --}}
    <div class="text-center text-xs text-slate-400 dark:text-slate-500 py-2">
        {{ $transactionCount }} transacciones en este período
    </div>
</div>
