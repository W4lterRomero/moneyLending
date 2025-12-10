@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Nuevo préstamo</h1>
        <a href="{{ route('loans.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Volver</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <form method="POST" action="{{ route('loans.store') }}" class="space-y-4">
            @include('loans._form', ['loan' => null])
            <button class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow">Guardar y generar cronograma</button>
        </form>
    </div>
@endsection
