@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
        <div>
            <div class="text-xs uppercase text-slate-500">Préstamo</div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ $loan->client?->name }}</h1>
            <p class="text-sm text-slate-500">Monto: ${{ number_format($loan->principal, 2) }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('payments.create', ['loan_id' => $loan->id]) }}" class="px-3 py-2 bg-sky-600 text-white rounded-lg text-sm hover:bg-sky-700">Registrar pago</a>
            <a href="{{ route('loans.edit', $loan) }}" class="px-3 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800">Editar</a>
            <form method="POST" action="{{ route('loans.destroy', $loan) }}">
                @csrf
                @method('DELETE')
                <button class="px-3 py-2 border border-red-200 text-red-600 rounded-lg text-sm hover:bg-red-50">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-3">
            <div class="font-semibold text-slate-800 text-lg">Información del Préstamo</div>
            <div class="space-y-2">
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Monto del préstamo</span>
                    <span class="text-sm font-semibold text-slate-900">${{ number_format($loan->principal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Tasa de interés</span>
                    <span class="text-sm font-semibold text-slate-900">{{ number_format($loan->interest_rate, 2) }}%</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Frecuencia de pago</span>
                    <span class="text-sm font-semibold text-slate-900">
                        @php
                            $freqLabels = ['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'];
                        @endphp
                        {{ $freqLabels[$loan->frequency] ?? $loan->frequency }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Fecha de inicio</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $loan->start_date->format('d/m/Y') }}</span>
                </div>
                @if($loan->notes)
                <div class="py-2">
                    <span class="text-sm text-slate-500">Notas</span>
                    <p class="text-sm text-slate-700 mt-1">{{ $loan->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-3">
            <div class="font-semibold text-slate-800 text-lg">Historial de Pagos</div>
            @if($loan->payments->count() > 0)
                <div class="grid gap-3 sm:hidden">
                    @foreach ($loan->payments as $payment)
                        @php
                            $method = $payment->method instanceof \BackedEnum ? $payment->method->value : $payment->method;
                        @endphp
                        <div class="card border border-slate-200 rounded-xl p-3 shadow-sm">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">${{ number_format($payment->amount, 2) }}</div>
                                    <div class="text-xs text-slate-500">{{ $payment->paid_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs text-slate-500 capitalize">Método: {{ $method }}</div>
                                </div>
                                <span class="text-xs text-slate-500">Por {{ $payment->recordedBy?->name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="overflow-auto hidden sm:block">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b border-slate-200">
                                <th class="py-2 pr-4">Fecha</th>
                                <th class="py-2 pr-4">Monto</th>
                                <th class="py-2 pr-4">Método</th>
                                <th class="py-2 pr-4">Registrado por</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($loan->payments as $payment)
                                @php
                                    $method = $payment->method instanceof \BackedEnum ? $payment->method->value : $payment->method;
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 pr-4">{{ $payment->paid_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3 pr-4 font-semibold text-slate-900">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="py-3 pr-4">{{ ucfirst($method) }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $payment->recordedBy?->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-slate-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm">No hay pagos registrados aún</p>
                    <a href="{{ route('payments.create', ['loan_id' => $loan->id]) }}" class="text-sky-600 hover:underline text-sm mt-2 inline-block">Registrar primer pago</a>
                </div>
            @endif
        </div>
    </div>
@endsection
