@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Clientes</h1>
            <p class="text-sm text-slate-500">Gestión de cartera y datos básicos.</p>
        </div>
        <a href="{{ route('clients.create') }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow">Nuevo cliente</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-4">
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                class="px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100 text-sm" />
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
                                <a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a>
                            </td>
                            <td class="py-3 pr-4">{{ $client->email }}</td>
                            <td class="py-3 pr-4">{{ $client->phone }}</td>
                            <td class="py-3 pr-4">
                                <span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700">{{ $client->status }}</span>
                            </td>
                            <td class="py-3 pr-4">
                                <a href="{{ route('clients.edit', $client) }}" class="text-sky-600 hover:underline text-sm">Editar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $clients->links() }}
    </div>
@endsection
