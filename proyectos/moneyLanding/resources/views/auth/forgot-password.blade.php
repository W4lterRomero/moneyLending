@extends('layouts.guest')

@section('content')
    <div class="space-y-4">
        <div class="text-sm text-slate-600">Recupera tu acceso enviando el enlace a tu correo.</div>
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-slate-600">Email</label>
                <input type="email" name="email" required
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
            </div>
            <button type="submit"
                class="w-full py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 shadow">Enviar enlace</button>
            <div class="text-center text-sm text-slate-600">
                <a href="{{ route('login') }}" class="text-sky-600 hover:underline">Volver a iniciar sesión</a>
            </div>
        </form>
    </div>
@endsection
