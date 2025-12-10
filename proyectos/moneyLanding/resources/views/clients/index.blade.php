@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Clientes</h1>
            <p class="text-sm text-slate-500">Gestión de cartera y datos completos.</p>
        </div>
        <a href="{{ route('clients.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nuevo cliente
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-4">
        <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2">
            <div class="flex items-center gap-2 flex-1">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, email..."
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100 text-sm" />
            </div>
            <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm">Filtrar</button>
        </form>

        <div class="overflow-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500">
                        <th class="py-2 pr-4">Nombre</th>
                        <th class="py-2 pr-4">Email</th>
                        <th class="py-2 pr-4">Teléfono</th>
                        <th class="py-2 pr-4">Estado</th>
                        <th class="py-2 pr-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($clients as $client)
                        <tr>
                            <td class="py-3 pr-4 font-semibold text-slate-800">
                                <a href="{{ route('clients.show', $client) }}" class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-sky-50 text-sky-700 text-sm font-bold">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </span>
                                    <span>{{ $client->name }}</span>
                                </a>
                            </td>
                            <td class="py-3 pr-4">{{ $client->email }}</td>
                            <td class="py-3 pr-4">{{ $client->phone }}</td>
                            <td class="py-3 pr-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @class([
                                        'bg-sky-50 text-sky-700' => $client->status === 'active',
                                        'bg-amber-50 text-amber-700' => $client->status === 'lead',
                                        'bg-slate-100 text-slate-700' => $client->status === 'suspended',
                                    ])">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>
                            <td class="py-3 pr-4">
                                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center gap-1 text-sky-600 hover:underline text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.414 1.414-9.9 9.9-2.475.353.353-2.475zM19.5 7.125L17.375 5 19.5 7.125z"/></svg>
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $clients->links() }}
    </div>
@endsection
