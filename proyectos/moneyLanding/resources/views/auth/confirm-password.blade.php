@extends('layouts.guest')

@section('content')
    <div class="space-y-4">
        <div class="text-sm text-slate-600">Confirma tu contraseña para continuar.</div>
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-slate-600">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring focus:ring-sky-100" />
            </div>
            <button type="submit"
                class="w-full py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 shadow">Confirmar</button>
        </form>
    </div>
@endsection
