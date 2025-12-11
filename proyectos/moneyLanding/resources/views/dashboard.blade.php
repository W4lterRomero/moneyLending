@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <livewire:dashboard-widgets />

        {{-- Tabla simple de préstamos --}}
        <livewire:loans.loan-table :limit="5" />
    </div>
@endsection
