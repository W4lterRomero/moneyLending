@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Nuevo cliente</h1>
        <a href="{{ route('clients.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Volver</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <form method="POST" action="{{ route('clients.store') }}" class="space-y-4" enctype="multipart/form-data">
            @include('clients._form', ['client' => new \App\Models\Client()])
            <button class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow">Guardar</button>
        </form>
    </div>
@endsection
