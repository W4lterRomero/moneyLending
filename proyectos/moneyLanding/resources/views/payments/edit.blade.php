@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Editar pago</h1>
        <a href="{{ route('payments.show', $payment) }}" class="text-sm text-slate-500 hover:text-slate-700">Ver</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <form method="POST" action="{{ route('payments.update', $payment) }}" class="space-y-4" enctype="multipart/form-data">
            @method('PUT')
            @include('payments._form')
            <button class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow">Actualizar</button>
        </form>
    </div>
@endsection
