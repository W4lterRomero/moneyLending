<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Referencias ({{ $references->count() }})</h3>
        <button wire:click="openModal" class="px-3 py-2 bg-sky-500 text-white rounded-lg text-sm">+ Añadir Referencia</button>
    </div>

    @if(session()->has('success'))
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if($references->count() > 0)
    <div class="space-y-3">
        @foreach($references as $reference)
        <div class="border border-slate-200 rounded-lg p-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-semibold text-slate-900">{{ $reference->name }}</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-full text-xs">{{ $reference->type_name }}</span>
                        @if($reference->verified)
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs">✓ Verificada</span>
                        @endif
                    </div>
                    <div class="grid md:grid-cols-2 gap-2 text-sm text-slate-600">
                        <div><strong>Relación:</strong> {{ $reference->relationship }}</div>
                        <div><strong>Teléfono:</strong> {{ $reference->phone }}</div>
                        @if($reference->email)
                        <div><strong>Email:</strong> {{ $reference->email }}</div>
                        @endif
                        @if($reference->occupation)
                        <div><strong>Ocupación:</strong> {{ $reference->occupation }}</div>
                        @endif
                    </div>
                    @if($reference->notes)
                    <div class="mt-2 text-xs text-slate-500 bg-slate-50 p-2 rounded">{{ $reference->notes }}</div>
                    @endif
                </div>
                <div class="flex gap-2">
                    @if(!$reference->verified)
                    <button wire:click="markAsVerified({{ $reference->id }})" 
                        class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs hover:bg-emerald-200">
                        Verificar
                    </button>
                    @endif
                    <button wire:click="openModal({{ $reference->id }})" class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs hover:bg-slate-200">Editar</button>
                    <button wire:click="delete({{ $reference->id }})" wire:confirm="¿Eliminar referencia?" class="px-2 py-1 bg-rose-100 text-rose-700 rounded text-xs hover:bg-rose-200">Eliminar</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8 text-slate-500">
        <p>No hay referencias registradas</p>
    </div>
    @endif

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click.self="showModal = false">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">{{ $referenceId ? 'Editar' : 'Nueva' }} Referencia</h3>
                <button wire:click="showModal = false" class="text-slate-500 hover:text-slate-700">✕</button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tipo *</label>
                        <select wire:model="type" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                            <option value="personal">Personal</option>
                            <option value="family">Familiar</option>
                            <option value="work">Laboral</option>
                        </select>
                        @error('type') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nombre Completo *</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                        @error('name') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono *</label>
                        <input type="tel" wire:model="phone" class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                        @error('phone') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono Alternativo</label>
                        <input type="tel" wire:model="second_phone" class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" wire:model="email" class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                        @error('email') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Relación *</label>
                        <input type="text" wire:model="relationship" placeholder="Ej: Hermano, Amigo, Jefe" class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                        @error('relationship') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ocupación</label>
                        <input type="text" wire:model="occupation" class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Dirección</label>
                        <textarea wire:model="address" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Notas</label>
                        <textarea wire:model="notes" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="showModal = false" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow hover:bg-sky-600">
                        {{ $referenceId ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
