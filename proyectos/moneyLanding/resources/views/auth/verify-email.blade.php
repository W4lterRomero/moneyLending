@extends('layouts.guest')

@section('content')
    <div class="space-y-4 text-center">
        <div class="text-sm text-slate-600">
            Antes de continuar, revisa tu correo y confirma tu email.
        </div>
        <form method="POST" action="{{ route('verification.send') }}" class="space-y-3">
            @csrf
            <button type="submit"
                class="w-full py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 shadow">Reenviar correo</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-500 hover:text-slate-700">Cerrar sesión</button>
        </form>
    </div>
@endsection
