@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm text-slate-600">Email</label>
            <input type="email" name="email" required autofocus
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
        </div>
        <div>
            <label class="block text-sm text-slate-600">Contraseña</label>
            <input type="password" name="password" required
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2 text-slate-600">
                <input type="checkbox" name="remember"> Recordarme
            </label>
            <a href="{{ route('password.request') }}" class="text-sky-600 hover:underline">¿Olvidaste la contraseña?</a>
        </div>
        <button type="submit"
            class="w-full py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 shadow">Ingresar</button>
        <div class="text-center text-sm text-slate-600">
            ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-sky-600 hover:underline">Crear cuenta</a>
        </div>
    </form>
@endsection
