@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ showUploadModal: false }">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-4">
            @if($client->photo_path)
                <img src="{{ Storage::url($client->photo_path) }}" alt="{{ $client->name }}" 
                    class="w-20 h-20 rounded-full object-cover border-2 border-sky-500">
            @else
                <div class="w-20 h-20 rounded-full bg-sky-100 flex items-center justify-center text-sky-600 text-2xl font-bold">
                    {{ substr($client->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $client->name }}</h1>
                <p class="text-sm text-slate-500">Cliente desde {{ $client->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('clients.edit', $client) }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-center">Editar</a>
            <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('¿Eliminar cliente?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-rose-500 text-white rounded-lg">Eliminar</button>
            </form>
        </div>
    </div>

    {{-- Información Personal --}}
    <div class="card">
        <h3 class="text-lg font-semibold mb-4">Información Personal</h3>
        <dl class="grid md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-slate-500">DUI</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->document_number ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Email</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->email ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Teléfono Principal</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->phone }}</dd>
            </div>
            @if($client->second_phone)
            <div>
                <dt class="text-sm text-slate-500">Teléfono Alternativo</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->second_phone }}</dd>
            </div>
            @endif
            @if($client->birth_date)
            <div>
                <dt class="text-sm text-slate-500">Fecha de Nacimiento</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->birth_date->format('d/m/Y') }} ({{ $client->birth_date->age }} años)</dd>
            </div>
            @endif
            @if($client->gender)
            <div>
                <dt class="text-sm text-slate-500">Género</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ ucfirst($client->gender) }}</dd>
            </div>
            @endif
            @if($client->marital_status)
            <div>
                <dt class="text-sm text-slate-500">Estado Civil</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ ucfirst($client->marital_status) }}</dd>
            </div>
            @endif
            @if($client->dependents > 0)
            <div>
                <dt class="text-sm text-slate-500">Dependientes</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->dependents }}</dd>
            </div>
            @endif
            @if($client->address)
            <div class="md:col-span-2">
                <dt class="text-sm text-slate-500">Dirección</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->address }}, {{ $client->city }}, {{ $client->country }}</dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- Información Laboral --}}
    @if($client->company_name || $client->job_title || $client->monthly_income)
    <div class="card">
        <h3 class="text-lg font-semibold mb-4">Información Laboral</h3>
        <dl class="grid md:grid-cols-2 gap-4">
            @if($client->company_name)
            <div>
                <dt class="text-sm text-slate-500">Empresa</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->company_name }}</dd>
            </div>
            @endif
            @if($client->job_title)
            <div>
                <dt class="text-sm text-slate-500">Cargo</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->job_title }}</dd>
            </div>
            @endif
            @if($client->monthly_income)
            <div>
                <dt class="text-sm text-slate-500">Salario Mensual</dt>
                <dd class="text-sm font-semibold text-emerald-600">${{ number_format($client->monthly_income, 2) }}</dd>
            </div>
            @endif
            @if($client->employment_start_date)
            <div>
                <dt class="text-sm text-slate-500">Antigüedad</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->employment_start_date->diffForHumans() }}</dd>
            </div>
            @endif
            @if($client->work_phone)
            <div>
                <dt class="text-sm text-slate-500">Teléfono de Trabajo</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->work_phone }}</dd>
            </div>
            @endif
            @if($client->supervisor_name)
            <div>
                <dt class="text-sm text-slate-500">Supervisor</dt>
                <dd class="text-sm font-semibold text-slate-900">{{ $client->supervisor_name }} @if($client->supervisor_phone)({{ $client->supervisor_phone }})@endif</dd>
            </div>
            @endif
        </dl>
    </div>
    @endif

    {{-- Documentos --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Documentos ({{ $client->documents->count() }})</h3>
            <button @click="showUploadModal = true" class="px-3 py-2 bg-sky-500 text-white rounded-lg text-sm">+ Subir Documento</button>
        </div>
        
        @if($client->documents->count() > 0)
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($client->documents as $doc)
            <div class="border border-slate-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">{{ $doc->icon }}</span>
                        <div>
                            <div class="text-sm font-semibold text-slate-800">{{ $doc->type_name }}</div>
                            <div class="text-xs text-slate-500">{{ $doc->file_size_formatted }}</div>
                        </div>
                    </div>
                    @if($doc->verified)
                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full">✓ Verificado</span>
                    @endif
                </div>
                
                @if(str_starts_with($doc->mime_type, 'image/'))
                <a href="{{ $doc->url }}" target="_blank" class="block mb-2">
                    <img src="{{ $doc->url }}" alt="{{ $doc->type_name }}" class="w-full h-32 object-cover rounded-lg">
                </a>
                @endif
                
                <div class="flex gap-2">
                    <a href="{{ $doc->url }}" target="_blank" class="flex-1 text-center px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs hover:bg-slate-200">Ver</a>
                    <a href="{{ $doc->url }}" download class="flex-1 text-center px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs hover:bg-slate-200">Descargar</a>
                    <form action="{{ route('clients.documents.destroy', [$client, $doc]) }}" method="POST" onsubmit="return confirm('¿Eliminar documento?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-2 py-1 bg-rose-100 text-rose-700 rounded text-xs hover:bg-rose-200">✕</button>
                    </form>
                </div>
                
                <div class="mt-2 text-xs text-slate-500">
                    Subido {{ $doc->created_at->diffForHumans() }}
                    @if($doc->uploader)
                        por {{ $doc->uploader->name }}
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-slate-500">
            <p>No hay documentos cargados</p>
        </div>
        @endif
    </div>

    {{-- Préstamos del Cliente --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Préstamos ({{ $client->loans->count() }})</h3>
            <a href="{{ route('loans.create', ['client_id' => $client->id]) }}" class="px-3 py-2 bg-sky-500 text-white rounded-lg text-sm">+ Nuevo Préstamo</a>
        </div>
        
        @if($client->loans->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="py-2">Código</th>
                        <th class="py-2">Monto</th>
                        <th class="py-2">Estado</th>
                        <th class="py-2">Fecha</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($client->loans as $loan)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 font-semibold">{{ $loan->code }}</td>
                        <td class="py-3">${{ number_format($loan->principal, 2) }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $loan->status->value === 'active' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $loan->status->value === 'delinquent' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $loan->status->value === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($loan->status->value) }}
                            </span>
                        </td>
                        <td class="py-3">{{ $loan->start_date->format('d/m/Y') }}</td>
                        <td class="py-3">
                            <a href="{{ route('loans.show', $loan) }}" class="text-sky-600 hover:underline">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-slate-500">
            <p>No hay préstamos registrados</p>
        </div>
        @endif
    </div>

    {{-- Referencias --}}
    <livewire:client-references :client="$client" />

    {{-- Modal de subida rápida --}}
    <div x-show="showUploadModal" x-cloak class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" @click.self="showUploadModal = false">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Subir documento</h3>
                <button @click="showUploadModal = false" class="text-slate-500 hover:text-slate-700">✕</button>
            </div>
            <p class="text-sm text-slate-600 mb-4">Usa el formulario de edición para adjuntar nuevos documentos al cliente.</p>
            <a href="{{ route('clients.edit', $client) }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow hover:bg-sky-600">Ir a editar</a>
        </div>
    </div>
</div>
@endsection
