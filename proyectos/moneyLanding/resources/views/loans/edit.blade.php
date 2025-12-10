@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Editar préstamo {{ $loan->code }}</h1>
        <a href="{{ route('loans.show', $loan) }}" class="text-sm text-slate-500 hover:text-slate-700">Ver ficha</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <form method="POST" action="{{ route('loans.update', $loan) }}" class="space-y-4">
            @method('PUT')
            @include('loans._form')
            <button class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow">Actualizar</button>
        </form>
    </div>
@endsection
