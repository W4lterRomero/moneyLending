@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Préstamos</h1>
            <p class="text-sm text-slate-500">Controla el ciclo de vida, calendario y kanban.</p>
        </div>
        <a href="{{ route('loans.create') }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow w-full sm:w-auto text-center">Nuevo préstamo</a>
    </div>

    <livewire:loans.loan-table />
@endsection
