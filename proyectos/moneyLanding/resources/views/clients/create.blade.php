@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Nuevo cliente</h1>
        <a href="{{ route('clients.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Volver</a>
    </div>

    <div class="card p-5">
        <form method="POST" action="{{ route('clients.store') }}" class="space-y-4" enctype="multipart/form-data">
            @include('clients._form', ['client' => new \App\Models\Client()])
            <button class="btn-primary-apple">Guardar</button>
        </form>
    </div>
@endsection
