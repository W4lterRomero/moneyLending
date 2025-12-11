<div x-data="{ 
    hideBalances: localStorage.getItem('hideBalances') === 'true',
    toggleHide() {
        this.hideBalances = !this.hideBalances;
        localStorage.setItem('hideBalances', this.hideBalances);
    }
}" class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <a href="{{ route('finance.index') }}" class="inline-flex items-center gap-1 text-sm text-sky-600 hover:text-sky-700 dark:text-sky-400 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
            <h1 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white">Análisis Financiero</h1>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" wire:click="$refresh" 
                class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300"
                title="Actualizar">
                <svg wire:loading.class="animate-spin" wire:target="$refresh" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
            <button type="button" @click="toggleHide()" 
                class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300">
                <svg x-show="!hideBalances" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg x-show="hideBalances" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3 sm:p-4">
        <div class="flex flex-col gap-3">
            {{-- Quick Range Buttons --}}
            <div class="flex flex-wrap gap-2">
                <span class="text-xs text-slate-500 dark:text-slate-400 self-center mr-1">Rápido:</span>
                @foreach(['week' => '7 días', 'month' => 'Este mes', 'year' => 'Este año', 'all' => 'Todo'] as $key => $label)
                    <button type="button" wire:click="setQuickRange('{{ $key }}')" 
                        class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            
            {{-- Date Range Inputs --}}
            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                <div class="flex items-center gap-2 flex-1">
                    <label class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Desde:</label>
                    <input type="date" wire:model.live="startDate" 
                        class="flex-1 text-sm rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 py-2 px-3">
                </div>
                <div class="flex items-center gap-2 flex-1">
                    <label class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Hasta:</label>
                    <input type="date" wire:model.live="endDate" 
                        class="flex-1 text-sm rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 py-2 px-3">
                </div>
                <select wire:model.live="accountFilter" 
                    class="text-sm rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 py-2 px-3">
                    <option value="">Todas las cuentas</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- Period Label --}}
            <div class="text-xs text-slate-400 dark:text-slate-500">
                Mostrando: {{ $periodLabel }}
            </div>
        </div>
    </div>

    {{-- Summary Cards - 2x2 on mobile, 4 cols on desktop --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
        {{-- Balance --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3 sm:p-4">
            <div class="flex items-center gap-2 mb-1 sm:mb-2">
                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center shrink-0" style="background-color: #0ea5e9;">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 uppercase">Balance</span>
            </div>
            <div class="text-lg sm:text-2xl font-bold {{ $totalBalance >= 0 ? 'text-slate-900 dark:text-white' : 'text-red-600' }}">
                <span x-show="!hideBalances">${{ number_format($totalBalance, 0) }}</span>
                <span x-show="hideBalances" x-cloak class="text-slate-400">****</span>
            </div>
        </div>
        
        {{-- Income --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3 sm:p-4">
            <div class="flex items-center gap-2 mb-1 sm:mb-2">
                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center shrink-0" style="background-color: #10b981;">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 uppercase">Ingresos</span>
            </div>
            <div class="text-lg sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                <span x-show="!hideBalances">+${{ number_format($totalIncome, 0) }}</span>
                <span x-show="hideBalances" x-cloak class="text-slate-400">****</span>
            </div>
        </div>
        
        {{-- Expenses --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3 sm:p-4">
            <div class="flex items-center gap-2 mb-1 sm:mb-2">
                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center shrink-0" style="background-color: #ef4444;">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 uppercase">Gastos</span>
            </div>
            <div class="text-lg sm:text-2xl font-bold text-red-600 dark:text-red-400">
                <span x-show="!hideBalances">-${{ number_format($totalExpense, 0) }}</span>
                <span x-show="hideBalances" x-cloak class="text-slate-400">****</span>
            </div>
        </div>
        
        {{-- Net Flow --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3 sm:p-4">
            <div class="flex items-center gap-2 mb-1 sm:mb-2">
                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center shrink-0" style="background-color: #8b5cf6;">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 uppercase">Neto</span>
            </div>
            <div class="text-lg sm:text-2xl font-bold {{ $netFlow >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                <span x-show="!hideBalances">{{ $netFlow >= 0 ? '+' : '' }}${{ number_format($netFlow, 0) }}</span>
                <span x-show="hideBalances" x-cloak class="text-slate-400">****</span>
            </div>
        </div>
    </div>

    {{-- Line Chart (Trading Style) --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3 sm:p-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
            <h3 class="font-medium text-slate-900 dark:text-white text-sm sm:text-base">Flujo de Efectivo</h3>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1 text-slate-500 dark:text-slate-400">
                    <span class="w-3 h-0.5 rounded" style="background-color: #10b981;"></span> Ingresos
                </span>
                <span class="flex items-center gap-1 text-slate-500 dark:text-slate-400">
                    <span class="w-3 h-0.5 rounded" style="background-color: #ef4444;"></span> Gastos
                </span>
            </div>
        </div>
        
        @php
            $chartWidth = 100;
            $chartHeight = 100;
            $dataCount = count($dailyData);
            $maxValue = collect($dailyData)->map(fn($d) => max($d['income'], $d['expense']))->max() ?: 1;
            
            // Build SVG path points
            $incomePoints = [];
            $expensePoints = [];
            
            foreach ($dailyData as $i => $day) {
                $x = $dataCount > 1 ? ($i / ($dataCount - 1)) * $chartWidth : 50;
                $incomeY = $chartHeight - (($day['income'] / $maxValue) * ($chartHeight - 10));
                $expenseY = $chartHeight - (($day['expense'] / $maxValue) * ($chartHeight - 10));
                $incomePoints[] = round($x, 2) . ',' . round($incomeY, 2);
                $expensePoints[] = round($x, 2) . ',' . round($expenseY, 2);
            }
            
            $incomePathLine = implode(' ', $incomePoints);
            $expensePathLine = implode(' ', $expensePoints);
            
            // Area fill paths (close the path at bottom)
            $incomeAreaPath = 'M0,' . $chartHeight . ' L' . str_replace(' ', ' L', $incomePathLine) . ' L' . $chartWidth . ',' . $chartHeight . ' Z';
            $expenseAreaPath = 'M0,' . $chartHeight . ' L' . str_replace(' ', ' L', $expensePathLine) . ' L' . $chartWidth . ',' . $chartHeight . ' Z';
        @endphp
        
        <div class="relative h-36 sm:h-44">
            <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" preserveAspectRatio="none" class="w-full h-full">
                {{-- Gradient definitions --}}
                <defs>
                    <linearGradient id="incomeGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#10b981;stop-opacity:0.3"/>
                        <stop offset="100%" style="stop-color:#10b981;stop-opacity:0"/>
                    </linearGradient>
                    <linearGradient id="expenseGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#ef4444;stop-opacity:0.3"/>
                        <stop offset="100%" style="stop-color:#ef4444;stop-opacity:0"/>
                    </linearGradient>
                </defs>
                
                {{-- Grid lines --}}
                <line x1="0" y1="25" x2="{{ $chartWidth }}" y2="25" stroke="currentColor" class="text-slate-200 dark:text-slate-700" stroke-width="0.3"/>
                <line x1="0" y1="50" x2="{{ $chartWidth }}" y2="50" stroke="currentColor" class="text-slate-200 dark:text-slate-700" stroke-width="0.3"/>
                <line x1="0" y1="75" x2="{{ $chartWidth }}" y2="75" stroke="currentColor" class="text-slate-200 dark:text-slate-700" stroke-width="0.3"/>
                
                {{-- Income area fill --}}
                <path d="{{ $incomeAreaPath }}" fill="url(#incomeGradient)"/>
                
                {{-- Expense area fill --}}
                <path d="{{ $expenseAreaPath }}" fill="url(#expenseGradient)"/>
                
                {{-- Income line --}}
                <polyline 
                    points="{{ $incomePathLine }}" 
                    fill="none" 
                    stroke="#10b981" 
                    stroke-width="1.5" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"/>
                
                {{-- Expense line --}}
                <polyline 
                    points="{{ $expensePathLine }}" 
                    fill="none" 
                    stroke="#ef4444" 
                    stroke-width="1.5" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"/>
            </svg>
            
            {{-- Y-axis labels --}}
            <div class="absolute left-0 top-0 h-full flex flex-col justify-between text-[9px] text-slate-400 dark:text-slate-500 pointer-events-none">
                <span>${{ number_format($maxValue, 0) }}</span>
                <span>${{ number_format($maxValue * 0.5, 0) }}</span>
                <span>$0</span>
            </div>
        </div>
        
        {{-- X-axis labels --}}
        <div class="flex justify-between mt-1 text-[9px] text-slate-400 dark:text-slate-500 px-1">
            @if(count($dailyData) > 0)
                <span>{{ $dailyData[0]['label'] ?? '' }}</span>
                @if(count($dailyData) > 2)
                    <span>{{ $dailyData[intval(count($dailyData)/2)]['label'] ?? '' }}</span>
                @endif
                <span>{{ $dailyData[count($dailyData)-1]['label'] ?? '' }}</span>
            @endif
        </div>
    </div>

    {{-- Categories - Stack on mobile --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        {{-- Top Income --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3 sm:p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-5 h-5 rounded flex items-center justify-center" style="background-color: #10b981;">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
                <h3 class="font-medium text-slate-900 dark:text-white text-sm">Top Ingresos</h3>
            </div>
            
            @forelse($incomeByCategory as $category => $amount)
                @php $pct = $totalIncome > 0 ? ($amount / $totalIncome) * 100 : 0; @endphp
                <div class="mb-2 last:mb-0">
                    <div class="flex justify-between text-xs sm:text-sm mb-1">
                        <span class="text-slate-700 dark:text-slate-300 truncate mr-2">{{ $category }}</span>
                        <span class="font-medium text-emerald-600 dark:text-emerald-400 shrink-0" x-show="!hideBalances">${{ number_format($amount, 0) }}</span>
                        <span class="text-slate-400 shrink-0" x-show="hideBalances" x-cloak>****</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width: {{ $pct }}%; background-color: #10b981;"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-3">Sin ingresos en este período</p>
            @endforelse
        </div>
        
        {{-- Top Expenses --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3 sm:p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-5 h-5 rounded flex items-center justify-center" style="background-color: #ef4444;">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
                <h3 class="font-medium text-slate-900 dark:text-white text-sm">Top Gastos</h3>
            </div>
            
            @forelse($expenseByCategory as $category => $amount)
                @php $pct = $totalExpense > 0 ? ($amount / $totalExpense) * 100 : 0; @endphp
                <div class="mb-2 last:mb-0">
                    <div class="flex justify-between text-xs sm:text-sm mb-1">
                        <span class="text-slate-700 dark:text-slate-300 truncate mr-2">{{ $category }}</span>
                        <span class="font-medium text-red-600 dark:text-red-400 shrink-0" x-show="!hideBalances">${{ number_format($amount, 0) }}</span>
                        <span class="text-slate-400 shrink-0" x-show="hideBalances" x-cloak>****</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width: {{ $pct }}%; background-color: #ef4444;"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-3">Sin gastos en este período</p>
            @endforelse
        </div>
    </div>

    {{-- Footer --}}
    <div class="text-center text-xs text-slate-400 dark:text-slate-500 py-1">
        {{ $transactionCount }} transacciones | {{ $accounts->count() }} cuentas
    </div>
</div>
