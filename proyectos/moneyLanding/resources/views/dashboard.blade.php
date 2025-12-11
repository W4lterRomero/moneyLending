@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <livewire:dashboard-widgets />

        {{-- Calculadora --}}
        <div class="flex justify-start sm:justify-end">
            <livewire:loans.loan-calculator />
        </div>

        {{-- Tabla simple de préstamos --}}
        <livewire:loans.loan-table />
    </div>
@endsection
