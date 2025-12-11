<div x-data="{ 
    hideBalances: localStorage.getItem('hideBalances') === 'true',
    toggleHide() {
        this.hideBalances = !this.hideBalances;
        localStorage.setItem('hideBalances', this.hideBalances);
    }
}" class="space-y-4">
    {{-- Header with Balance Toggle --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xs uppercase text-slate-500 dark:text-slate-400">Control Financiero</div>
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white">Finanzas</h1>
        </div>
        <div class="flex items-center gap-1">
            {{-- Analytics Dashboard Link --}}
            <a href="{{ route('finance.analytics') }}" 
                class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition-colors"
                title="Ver Análisis">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </a>
            {{-- Eye Toggle Button --}}
            <button type="button" @click="toggleHide()" 
                class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition-colors"
                :title="hideBalances ? 'Mostrar saldos' : 'Ocultar saldos'">
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
    </div>

    {{-- Action Buttons - Using inline styles for guaranteed visibility --}}
    <div class="flex gap-2">
        <button type="button" wire:click="openTransactionModal('income')" 
            style="background-color: #10b981;"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-white rounded-xl shadow transition-colors font-medium hover:opacity-90">
            <span class="text-lg">+</span>
            <span>Ingreso</span>
        </button>
        <button type="button" wire:click="openTransactionModal('expense')" 
            style="background-color: #ef4444;"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-white rounded-xl shadow transition-colors font-medium hover:opacity-90">
            <span class="text-lg">−</span>
            <span>Gasto</span>
        </button>
    </div>

    {{-- Total Balance Card - Adapts to light/dark mode --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-sm mb-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Balance Total
        </div>
        <div class="text-3xl sm:text-4xl font-bold tracking-tight {{ $totalBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
            <template x-if="!hideBalances">
                <span>${{ number_format($totalBalance, 2) }}</span>
            </template>
            <template x-if="hideBalances">
                <span class="text-slate-400">****</span>
            </template>
        </div>
        <div class="flex gap-6 mt-4 text-sm">
            <div>
                <div class="text-slate-500 dark:text-slate-400">Hoy</div>
                <div class="flex items-center gap-1">
                    <span class="text-emerald-500">↑</span>
                    <template x-if="!hideBalances">
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">${{ number_format($todayIncome, 2) }}</span>
                    </template>
                    <template x-if="hideBalances">
                        <span class="text-slate-400">****</span>
                    </template>
                </div>
            </div>
            <div>
                <div class="text-slate-500 dark:text-slate-400">&nbsp;</div>
                <div class="flex items-center gap-1">
                    <span class="text-red-500">↓</span>
                    <template x-if="!hideBalances">
                        <span class="text-red-600 dark:text-red-400 font-medium">${{ number_format($todayExpense, 2) }}</span>
                    </template>
                    <template x-if="hideBalances">
                        <span class="text-slate-400">****</span>
                    </template>
                </div>
            </div>
            <div class="ml-auto text-right">
                <div class="text-slate-500 dark:text-slate-400">Cuentas</div>
                <div class="text-slate-800 dark:text-white font-medium">{{ $accounts->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Accounts Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Mis Cuentas</h2>
            <button type="button" wire:click="openAccountModal()" 
                class="px-3 py-1.5 text-sm bg-sky-500 hover:bg-sky-600 text-white rounded-lg flex items-center gap-1 transition-colors">
                <span>+</span> Nueva
            </button>
        </div>
        
        @if($accounts->isEmpty())
            <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                <div class="text-4xl mb-2">💰</div>
                <p>No hay cuentas aún</p>
                <p class="text-sm">Crea tu primera cuenta para empezar</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($accounts as $account)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0 shadow-sm"
                             style="background-color: {{ $account->color }}">
                            {{ strtoupper(substr($account->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-slate-800 dark:text-white truncate">{{ $account->name }}</div>
                            <div class="text-xl font-bold {{ $account->current_balance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                <template x-if="!hideBalances">
                                    <span>${{ number_format($account->current_balance, 2) }}</span>
                                </template>
                                <template x-if="hideBalances">
                                    <span class="text-slate-400">****</span>
                                </template>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button type="button" wire:click="openTransactionModal('income', {{ $account->id }})" 
                                class="w-9 h-9 flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-lg font-bold hover:bg-emerald-200 dark:hover:bg-emerald-900 transition-colors">
                                +
                            </button>
                            <button type="button" wire:click="openTransactionModal('expense', {{ $account->id }})" 
                                class="w-9 h-9 flex items-center justify-center bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 rounded-lg font-bold hover:bg-red-200 dark:hover:bg-red-900 transition-colors">
                                −
                            </button>
                            <button type="button" wire:click="openAccountModal({{ $account->id }})" 
                                class="w-9 h-9 flex items-center justify-center bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500 transition-colors">
                                ⚙️
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Transactions Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Historial</h2>
            <div class="flex gap-1 bg-slate-100 dark:bg-slate-700 p-1 rounded-lg">
                <button type="button" wire:click="$set('filterType', '')" 
                    class="px-3 py-1 text-xs rounded-md transition-colors {{ $filterType === '' ? 'bg-white dark:bg-slate-600 shadow text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                    Todo
                </button>
                <button type="button" wire:click="$set('filterType', 'income')" 
                    class="px-3 py-1 text-xs rounded-md transition-colors {{ $filterType === 'income' ? 'bg-emerald-500 text-white shadow' : 'text-slate-500 dark:text-slate-400' }}">
                    Ingresos
                </button>
                <button type="button" wire:click="$set('filterType', 'expense')" 
                    class="px-3 py-1 text-xs rounded-md transition-colors {{ $filterType === 'expense' ? 'bg-red-500 text-white shadow' : 'text-slate-500 dark:text-slate-400' }}">
                    Gastos
                </button>
            </div>
        </div>

        @if($transactions->isEmpty())
            <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                <div class="text-4xl mb-2">📊</div>
                <p>Sin movimientos</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($transactions as $tx)
                    <div class="flex items-center gap-3 p-3 rounded-xl {{ $tx->type === 'income' ? 'bg-emerald-50 dark:bg-emerald-900/20' : 'bg-red-50 dark:bg-red-900/20' }}">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $tx->type === 'income' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400' }}">
                            {{ $tx->type === 'income' ? '↑' : '↓' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-slate-800 dark:text-white truncate">{{ $tx->category }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $tx->account->name }} • {{ $tx->transaction_date->format('d M Y') }}
                                @if($tx->description) • {{ $tx->description }} @endif
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-bold {{ $tx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                <template x-if="!hideBalances">
                                    <span>{{ $tx->type === 'income' ? '+' : '-' }}${{ number_format($tx->amount, 2) }}</span>
                                </template>
                                <template x-if="hideBalances">
                                    <span class="text-slate-400">****</span>
                                </template>
                            </div>
                        </div>
                        <button type="button" wire:click="deleteTransaction({{ $tx->id }})" 
                            wire:confirm="¿Eliminar este movimiento?"
                            class="text-slate-300 dark:text-slate-500 hover:text-red-500 dark:hover:text-red-400 p-1 shrink-0 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Account Modal --}}
    @if($showAccountModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.5);">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6" @click.outside="$wire.set('showAccountModal', false)">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
                        {{ $editingAccountId ? 'Editar Cuenta' : 'Nueva Cuenta' }}
                    </h3>
                    <button type="button" wire:click="$set('showAccountModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nombre de la cuenta</label>
                        <input type="text" wire:model="accountName" placeholder="Ej: Efectivo, Banco..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                        @error('accountName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Saldo Inicial</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                            <input type="number" step="0.01" wire:model="accountInitialBalance" placeholder="0.00"
                                class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-xl font-bold" />
                        </div>
                        @error('accountInitialBalance') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Color</label>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#6366f1'] as $color)
                                <button type="button" wire:click="$set('accountColor', '{{ $color }}')"
                                    class="w-10 h-10 rounded-xl border-2 transition-all {{ $accountColor === $color ? 'border-slate-800 dark:border-white scale-110 shadow-lg' : 'border-transparent' }}"
                                    style="background-color: {{ $color }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col gap-2 mt-6">
                    <button type="button" wire:click="saveAccount" 
                        class="w-full py-3 bg-sky-500 text-white rounded-xl font-medium hover:bg-sky-600 transition-colors">
                        {{ $editingAccountId ? 'Actualizar' : 'Crear Cuenta' }}
                    </button>
                    @if($editingAccountId)
                        <button type="button" wire:click="deleteAccount({{ $editingAccountId }})" 
                            wire:confirm="¿Eliminar esta cuenta y todos sus movimientos?"
                            class="w-full py-3 text-red-600 dark:text-red-400 rounded-xl font-medium hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                            Eliminar Cuenta
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Transaction Modal --}}
    @if($showTransactionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.5);">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6" @click.outside="$wire.set('showTransactionModal', false)">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold {{ $transactionType === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $transactionType === 'income' ? '+ Nuevo Ingreso' : '− Nuevo Gasto' }}
                    </h3>
                    <button type="button" wire:click="$set('showTransactionModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    {{-- Amount Input --}}
                    <div class="text-center py-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <label class="block text-sm text-slate-500 dark:text-slate-400 mb-2">Monto</label>
                        <div class="relative inline-flex items-center">
                            <span class="text-2xl text-slate-400 mr-1">$</span>
                            <input type="number" step="0.01" wire:model="transactionAmount" placeholder="0.00" inputmode="decimal"
                                class="w-40 text-center text-3xl font-bold border-0 bg-transparent focus:ring-0 {{ $transactionType === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}" />
                        </div>
                        @error('transactionAmount') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cuenta</label>
                        <select wire:model="transactionAccountId" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            <option value="">Seleccionar cuenta...</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                        @error('transactionAccountId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Categoría</label>
                        <input type="text" wire:model="transactionCategory" placeholder="Ej: Comida, Transporte..." list="cat-list"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                        <datalist id="cat-list">
                            @foreach($categories[$transactionType] ?? [] as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                        @error('transactionCategory') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Fecha</label>
                            <input type="date" wire:model="transactionDate"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nota</label>
                            <input type="text" wire:model="transactionDescription" placeholder="Opcional..."
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500" />
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="button" wire:click="saveTransaction" 
                        class="w-full py-4 text-white rounded-xl font-bold text-lg transition-colors {{ $transactionType === 'income' ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-red-500 hover:bg-red-600' }}">
                        Guardar {{ $transactionType === 'income' ? 'Ingreso' : 'Gasto' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
