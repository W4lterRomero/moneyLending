@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Clientes</h1>
            <p class="text-sm text-slate-500">Gestión de cartera y datos completos.</p>
        </div>
        <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg shadow w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nuevo cliente
        </a>
    </div>

    <livewire:clients.client-table />
@endsection
