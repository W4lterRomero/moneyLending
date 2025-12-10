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
                                <a href="{{ route('loans.show', $payment->loan) }}" class="text-sky-600">{{ $payment->loan?->code }}</a>
                            </td>
                            <td class="py-3 pr-4">{{ $payment->paid_at->format('d/m/Y') }}</td>
                            <td class="py-3 pr-4">${{ number_format($payment->amount, 2) }}</td>
                            <td class="py-3 pr-4">{{ ucfirst($method) }}</td>
                            <td class="py-3 pr-4">
                                <a href="{{ route('payments.show', $payment) }}" class="text-sm text-sky-600">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
@endsection
