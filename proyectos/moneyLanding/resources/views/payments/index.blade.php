@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Pagos</h1>
            <p class="text-sm text-slate-500">Control de cobros y conciliación.</p>
        </div>
        <a href="{{ route('payments.create') }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow">Registrar pago</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-4">
        <div class="overflow-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500">
                        <th class="py-2 pr-4">Préstamo</th>
                        <th class="py-2 pr-4">Fecha</th>
                        <th class="py-2 pr-4">Monto</th>
                        <th class="py-2 pr-4">Método</th>
                        <th class="py-2 pr-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($payments as $payment)
                        @php
                            $method = $payment->method instanceof \BackedEnum ? $payment->method->value : $payment->method;
                        @endphp
                        <tr>
                            <td class="py-3 pr-4">
                                <a href="{{ route('loans.show', $payment->loan) }}" class="inline-flex items-center gap-2 text-sky-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 10h18M5 14h14M7 18h10"/></svg>
                                    {{ $payment->loan?->code }}
                                </a>
                            </td>
                            <td class="py-3 pr-4">{{ $payment->paid_at->format('d/m/Y') }}</td>
                            <td class="py-3 pr-4 font-semibold text-slate-800">${{ number_format($payment->amount, 2) }}</td>
                            <td class="py-3 pr-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @class([
                                        'bg-emerald-50 text-emerald-700' => $method === 'cash' || $method === 'transfer',
                                        'bg-indigo-50 text-indigo-700' => $method === 'card',
                                        'bg-slate-100 text-slate-700' => !in_array($method, ['cash','transfer','card']),
                                    ])">
                                    {{ ucfirst($method) }}
                                </span>
                            </td>
                            <td class="py-3 pr-4">
                                <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center gap-1 text-sm text-sky-600 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
@endsection
