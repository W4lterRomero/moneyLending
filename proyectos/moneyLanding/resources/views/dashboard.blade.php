@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <livewire:dashboard-widgets />

        <div class="grid lg:grid-cols-2 gap-4">
            <livewire:loans.loan-calendar />
            <livewire:loans.loan-kanban />
        </div>

        <livewire:loans.loan-table />
        <livewire:loans.loan-calculator />
    </div>
@endsection
