@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <livewire:dashboard-widgets />
        <livewire:loans.loan-calendar />
        <livewire:loans.loan-table />
        <livewire:loans.loan-calculator />
    </div>
@endsection
