@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <div class="text-xs uppercase text-slate-500">Préstamo</div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ $loan->code }}</h1>
            <p class="text-sm text-slate-500">{{ $loan->client?->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('loans.edit', $loan) }}" class="px-3 py-2 bg-slate-900 text-white rounded-lg text-sm">Editar</a>
            <form method="POST" action="{{ route('loans.destroy', $loan) }}">
                @csrf
                @method('DELETE')
                <button class="px-3 py-2 border border-red-200 text-red-600 rounded-lg text-sm">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-2">
            <div class="font-semibold text-slate-800">Resumen</div>
            <div class="text-sm text-slate-600">Monto: ${{ number_format($loan->principal, 2) }}</div>
            <div class="text-sm text-slate-600">Interés: {{ $loan->interest_rate }}%</div>
            <div class="text-sm text-slate-600">Cuota: ${{ number_format($loan->installment_amount, 2) }}</div>
            @php
                $status = $loan->status instanceof \BackedEnum ? $loan->status->value : $loan->status;
            @endphp
            <div class="text-sm text-slate-600">Estado: {{ ucfirst($status) }}</div>
            <div class="text-sm text-slate-600">Próx. vencimiento: {{ optional($loan->next_due_date)->format('d/m/Y') }}</div>
        </div>

        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="font-semibold text-slate-800">Cronograma</div>
                <a href="{{ route('payments.create', ['loan_id' => $loan->id]) }}" class="text-sm text-sky-600 hover:underline">Registrar pago</a>
            </div>
            <div class="overflow-auto max-h-80">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500">
                            <th class="py-2 pr-4">#</th>
                            <th class="py-2 pr-4">Fecha</th>
                            <th class="py-2 pr-4">Cuota</th>
                            <th class="py-2 pr-4">Capital</th>
                            <th class="py-2 pr-4">Interés</th>
                            <th class="py-2 pr-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($loan->installments as $installment)
                            @php
                                $installmentStatus = $installment->status instanceof \BackedEnum ? $installment->status->value : $installment->status;
                            @endphp
                            <tr>
                                <td class="py-2 pr-4">{{ $installment->number }}</td>
                                <td class="py-2 pr-4">{{ $installment->due_date->format('d/m/Y') }}</td>
                                <td class="py-2 pr-4">${{ number_format($installment->amount, 2) }}</td>
                                <td class="py-2 pr-4">${{ number_format($installment->principal_amount, 2) }}</td>
                                <td class="py-2 pr-4">${{ number_format($installment->interest_amount, 2) }}</td>
                                <td class="py-2 pr-4">{{ ucfirst($installmentStatus) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 mt-4">
        <div class="font-semibold text-slate-800 mb-2">Pagos</div>
        <div class="overflow-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500">
                        <th class="py-2 pr-4">Fecha</th>
                        <th class="py-2 pr-4">Monto</th>
                        <th class="py-2 pr-4">Capital</th>
                        <th class="py-2 pr-4">Interés</th>
                        <th class="py-2 pr-4">Método</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($loan->payments as $payment)
                        @php
                            $method = $payment->method instanceof \BackedEnum ? $payment->method->value : $payment->method;
                        @endphp
                        <tr>
                            <td class="py-2 pr-4">{{ $payment->paid_at->format('d/m/Y') }}</td>
                            <td class="py-2 pr-4">${{ number_format($payment->amount, 2) }}</td>
                            <td class="py-2 pr-4">${{ number_format($payment->principal_amount, 2) }}</td>
                            <td class="py-2 pr-4">${{ number_format($payment->interest_amount, 2) }}</td>
                            <td class="py-2 pr-4">{{ ucfirst($method) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
