@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <div class="text-xs uppercase text-slate-500">Pago</div>
            <h1 class="text-2xl font-semibold text-slate-900">${{ number_format($payment->amount, 2) }}</h1>
            <p class="text-sm text-slate-500">
                Cliente: {{ $payment->loan?->client?->name ?? 'No asignado' }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('payments.edit', $payment) }}" class="px-3 py-2 bg-slate-900 text-white rounded-lg text-sm">Editar</a>
            <form method="POST" action="{{ route('payments.destroy', $payment) }}">
                @csrf
                @method('DELETE')
                <button class="px-3 py-2 border border-red-200 text-red-600 rounded-lg text-sm">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-2">
            <div class="font-semibold text-slate-800">Detalles</div>
            <div class="text-sm text-slate-600">Fecha: {{ $payment->paid_at->format('d/m/Y') }}</div>
            @php
                $method = $payment->method instanceof \BackedEnum ? $payment->method->value : $payment->method;
            @endphp
            <div class="text-sm text-slate-600">Método: {{ ucfirst($method) }}</div>
            <div class="text-sm text-slate-600">Referencia: {{ $payment->reference }}</div>
            <div class="text-sm text-slate-600">Capital: ${{ number_format($payment->principal_amount, 2) }}</div>
            <div class="text-sm text-slate-600">Interés: ${{ number_format($payment->interest_amount, 2) }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-3">
            <div class="font-semibold text-slate-800">Notas</div>
            <p class="text-sm text-slate-600">{{ $payment->notes ?: 'Sin notas adicionales.' }}</p>
            <div class="grid sm:grid-cols-2 gap-3 pt-2">
                <div>
                    <div class="text-xs uppercase text-slate-500 mb-1">Foto</div>
                    @if ($payment->photo_path)
                        <a href="{{ Storage::url($payment->photo_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sky-600 hover:underline text-sm">
                            <x-icon name="photo" class="w-4 h-4" />
                            Ver foto
                        </a>
                    @else
                        <div class="text-sm text-slate-400">No se adjuntó foto</div>
                    @endif
                </div>
                <div>
                    <div class="text-xs uppercase text-slate-500 mb-1">Comprobante</div>
                    @if ($payment->receipt_path)
                        <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" class="inline-flex items-center gap-2 text-emerald-600 hover:underline text-sm">
                            <x-icon name="document" class="w-4 h-4" />
                            Descargar comprobante
                        </a>
                    @else
                        <div class="text-sm text-slate-400">No hay comprobante adjunto</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
