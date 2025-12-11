<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white">Financiamientos</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Productos financiados a clientes</p>
        </div>
        <button type="button" wire:click="openModal()" 
            class="px-4 py-2 text-sm font-medium text-white rounded-lg flex items-center gap-2 justify-center shrink-0"
            style="background-color: #8b5cf6;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo
        </button>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background-color: #8b5cf6;">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4"/>
                    </svg>
                </div>
                <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase">Activos</span>
            </div>
            <div class="text-xl font-bold text-slate-900 dark:text-white">{{ $totalActive }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background-color: #ef4444;">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase">Por Cobrar</span>
            </div>
            <div class="text-xl font-bold text-red-600 dark:text-red-400">${{ number_format($totalOwed, 0) }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background-color: #10b981;">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase">Cobrado</span>
            </div>
            <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($totalCollected, 0) }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background-color: #0ea5e9;">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase">Avance</span>
            </div>
            <div class="text-xl font-bold text-sky-600 dark:text-sky-400">{{ number_format($avgProgress, 0) }}%</div>
        </div>
    </div>

    {{-- Filters + View Toggle --}}
    <div class="flex flex-col sm:flex-row gap-2">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar..."
                class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white">
        </div>
        
        {{-- Status Filter --}}
        <div class="flex gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg">
            <button type="button" wire:click="$set('statusFilter', 'active')" 
                class="px-3 py-1.5 text-xs rounded-md {{ $statusFilter === 'active' ? 'bg-white dark:bg-slate-700 shadow text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                Activos
            </button>
            <button type="button" wire:click="$set('statusFilter', 'paid')" 
                class="px-3 py-1.5 text-xs rounded-md {{ $statusFilter === 'paid' ? 'bg-white dark:bg-slate-700 shadow text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                Pagados
            </button>
            <button type="button" wire:click="$set('statusFilter', '')" 
                class="px-3 py-1.5 text-xs rounded-md {{ $statusFilter === '' ? 'bg-white dark:bg-slate-700 shadow text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                Todos
            </button>
        </div>
        
        {{-- View Toggle --}}
        <div class="flex gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg">
            <button type="button" wire:click="$set('viewMode', 'list')" 
                class="p-1.5 rounded-md {{ $viewMode === 'list' ? 'bg-white dark:bg-slate-700 shadow' : '' }}"
                title="Vista Lista">
                <svg class="w-4 h-4 {{ $viewMode === 'list' ? 'text-slate-800 dark:text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <button type="button" wire:click="$set('viewMode', 'gallery')" 
                class="p-1.5 rounded-md {{ $viewMode === 'gallery' ? 'bg-white dark:bg-slate-700 shadow' : '' }}"
                title="Vista Galería">
                <svg class="w-4 h-4 {{ $viewMode === 'gallery' ? 'text-slate-800 dark:text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Products --}}
    @if($financings->isEmpty())
        <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-slate-500 dark:text-slate-400">No hay financiamientos</p>
            <button type="button" wire:click="openModal()" class="mt-3 text-sm text-violet-600 hover:text-violet-700">
                Crear el primero
            </button>
        </div>
    @else
        {{-- LIST VIEW --}}
        @if($viewMode === 'list')
            <div class="space-y-3">
                @foreach($financings as $financing)
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
                        <div class="flex gap-4">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg bg-slate-100 dark:bg-slate-700 overflow-hidden shrink-0">
                                @if($financing->product_image)
                                    <img src="{{ asset('storage/' . $financing->product_image) }}" alt="{{ $financing->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-slate-900 dark:text-white truncate">{{ $financing->product_name }}</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $financing->client->name }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-[10px] font-medium rounded-full shrink-0
                                        {{ $financing->status === 'active' ? 'bg-violet-100 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400' : 
                                           ($financing->status === 'paid' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 
                                           'bg-slate-100 dark:bg-slate-700 text-slate-500') }}">
                                        {{ $financing->status === 'active' ? 'Activo' : ($financing->status === 'paid' ? 'Pagado' : 'Cancelado') }}
                                    </span>
                                </div>
                                <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm">
                                    <div><span class="text-slate-500 dark:text-slate-400">Precio:</span> <span class="font-medium text-slate-800 dark:text-white">${{ number_format($financing->product_price, 2) }}</span></div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-slate-500 dark:text-slate-400">Debe: $</span>
                                        <input type="number" step="0.01" value="{{ $financing->balance }}" wire:change="updateBalance({{ $financing->id }}, $event.target.value)"
                                            class="w-20 font-bold text-violet-600 dark:text-violet-400 bg-transparent border-0 border-b border-dashed border-slate-300 dark:border-slate-600 focus:ring-0 p-0 text-sm"
                                            {{ $financing->status !== 'active' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400 mb-1">
                                        <span>Pagado</span><span>{{ number_format($financing->progress_percent, 0) }}%</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full" style="width: {{ $financing->progress_percent }}%; background-color: #8b5cf6;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1 shrink-0">
                                <button type="button" wire:click="openModal({{ $financing->id }})" class="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button type="button" wire:click="delete({{ $financing->id }})" wire:confirm="¿Eliminar?" class="p-2 text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- GALLERY VIEW --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($financings as $financing)
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden group">
                        <div class="aspect-square bg-slate-100 dark:bg-slate-700 relative">
                            @if($financing->product_image)
                                <img src="{{ asset('storage/' . $financing->product_image) }}" alt="{{ $financing->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @endif
                            <span class="absolute top-2 right-2 px-2 py-0.5 text-[10px] font-medium rounded-full
                                {{ $financing->status === 'active' ? 'bg-violet-500 text-white' : ($financing->status === 'paid' ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white') }}">
                                {{ $financing->status === 'active' ? 'Activo' : ($financing->status === 'paid' ? 'Pagado' : 'Canc.') }}
                            </span>
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button type="button" wire:click="openModal({{ $financing->id }})" class="p-2 bg-white rounded-lg text-slate-700 hover:bg-slate-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button type="button" wire:click="delete({{ $financing->id }})" wire:confirm="¿Eliminar?" class="p-2 bg-red-500 rounded-lg text-white hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-3">
                            <h3 class="font-medium text-sm text-slate-900 dark:text-white truncate">{{ $financing->product_name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $financing->client->name }}</p>
                            <div class="mt-2 flex justify-between text-xs">
                                <span class="text-slate-500">${{ number_format($financing->product_price, 0) }}</span>
                                <span class="font-bold text-violet-600 dark:text-violet-400">Debe: ${{ number_format($financing->balance, 0) }}</span>
                            </div>
                            <div class="mt-2 h-1 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="width: {{ $financing->progress_percent }}%; background-color: #8b5cf6;"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="mt-4">{{ $financings->links() }}</div>
    @endif

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.5);">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto" @click.outside="$wire.set('showModal', false)">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ $editingId ? 'Editar' : 'Nuevo Financiamiento' }}</h3>
                        <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cliente</label>
                            <select wire:model="clientId" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm">
                                <option value="">Seleccionar...</option>
                                @foreach($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach
                            </select>
                            @error('clientId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Producto</label>
                            <input type="text" wire:model="productName" placeholder="Ej: iPhone 15 Pro" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm">
                            @error('productName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Foto</label>
                            @if($existingImage && !$productImage)<img src="{{ asset('storage/' . $existingImage) }}" class="h-16 rounded mb-2 object-cover">@endif
                            @if($productImage)<img src="{{ $productImage->temporaryUrl() }}" class="h-16 rounded mb-2 object-cover">@endif
                            <input type="file" wire:model="productImage" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-violet-50 file:text-violet-600">
                            <div wire:loading wire:target="productImage" class="text-xs text-slate-500 mt-1">Subiendo...</div>
                            @error('productImage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Precio</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                                    <input type="number" step="0.01" wire:model="productPrice" placeholder="0.00" class="w-full pl-7 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm">
                                </div>
                                @error('productPrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Saldo</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                                    <input type="number" step="0.01" wire:model="balance" placeholder="0.00" class="w-full pl-7 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm">
                                </div>
                                @error('balance') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notas</label>
                            <textarea wire:model="notes" rows="2" placeholder="Opcional..." class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm resize-none"></textarea>
                        </div>
                        @if($editingId)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Estado</label>
                            <select wire:model="status" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm">
                                <option value="active">Activo</option><option value="paid">Pagado</option><option value="cancelled">Cancelado</option>
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="mt-5">
                        <button type="button" wire:click="save" wire:loading.attr="disabled" class="w-full py-2.5 text-white rounded-lg font-medium text-sm" style="background-color: #8b5cf6;">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Actualizar' : 'Crear' }}</span>
                            <span wire:loading wire:target="save">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
