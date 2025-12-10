@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Editar cliente</h1>
        <a href="{{ route('clients.show', $client) }}" class="text-sm text-slate-500 hover:text-slate-700">Ver ficha</a>
    </div>

    <div class="card p-5">
        <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('clients._form')
            <button class="btn-primary-apple">Actualizar</button>
        </form>
    </div>
@endsection
