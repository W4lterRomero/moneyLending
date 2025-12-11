@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Pagos</h1>
            <p class="text-sm text-slate-500">Control de cobros y conciliación.</p>
        </div>
        <a href="{{ route('payments.create') }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow w-full sm:w-auto text-center">Registrar pago</a>
    </div>

    <livewire:payments.payment-table />
@endsection
