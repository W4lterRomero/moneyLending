@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm text-slate-600">Nombre</label>
            <input type="text" name="name" required autofocus
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
        </div>
        <div>
            <label class="block text-sm text-slate-600">Email</label>
            <input type="email" name="email" required
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-slate-600">Teléfono</label>
                <input type="text" name="phone"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
            </div>
            <div>
                <label class="block text-sm text-slate-600">Zona horaria</label>
                <input type="text" name="timezone" value="UTC"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-slate-600">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
            </div>
            <div>
                <label class="block text-sm text-slate-600">Confirmar</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
            </div>
        </div>
        <button type="submit"
            class="w-full py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 shadow">Crear cuenta</button>
        <div class="text-center text-sm text-slate-600">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-sky-600 hover:underline">Ingresar</a>
        </div>
    </form>
@endsection
