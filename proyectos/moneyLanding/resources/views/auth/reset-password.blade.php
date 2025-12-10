@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ request('token') }}">
        <div>
            <label class="block text-sm text-slate-600">Email</label>
            <input type="email" name="email" value="{{ request('email') }}" required
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
        </div>
        <div>
            <label class="block text-sm text-slate-600">Nueva contraseña</label>
            <input type="password" name="password" required
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
        </div>
        <div>
            <label class="block text-sm text-slate-600">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
        </div>
        <button type="submit"
            class="w-full py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 shadow">Actualizar contraseña</button>
    </form>
@endsection
