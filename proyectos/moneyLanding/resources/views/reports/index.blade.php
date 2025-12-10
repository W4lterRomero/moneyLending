@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Reportes y exportes</h1>
        <p class="text-sm text-slate-500">Exporta cartera, clientes y flujo de caja a Excel o PDF.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-3">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-3">
            <div class="font-semibold text-slate-800">Clientes</div>
            <p class="text-sm text-slate-600">Listado completo de clientes.</p>
            <a href="{{ route('reports.clients') }}" class="px-3 py-2 bg-slate-900 text-white rounded-lg text-sm inline-block">Exportar Excel</a>
        </div>
        <div class="bg-white border-slate-200 border rounded-xl shadow-sm p-4 space-y-3">
            <div class="font-semibold text-slate-800">Préstamos</div>
            <p class="text-sm text-slate-600">Estados, montos y tasas.</p>
            <a href="{{ route('reports.loans') }}" class="px-3 py-2 bg-slate-900 text-white rounded-lg text-sm inline-block">Exportar Excel</a>
        </div>
        <div class="bg-white border-slate-200 border rounded-xl shadow-sm p-4 space-y-3">
            <div class="font-semibold text-slate-800">Pagos</div>
            <p class="text-sm text-slate-600">Flujo de caja cobrado.</p>
            <a href="{{ route('reports.payments') }}" class="px-3 py-2 bg-slate-900 text-white rounded-lg text-sm inline-block">Exportar Excel</a>
        </div>
        <div class="bg-white border-slate-200 border rounded-xl shadow-sm p-4 space-y-3">
            <div class="font-semibold text-slate-800">Cartera PDF</div>
            <p class="text-sm text-slate-600">Resumen ejecutivo en PDF.</p>
            <a href="{{ route('reports.pdf.portfolio') }}" class="px-3 py-2 bg-sky-500 text-white rounded-lg text-sm inline-block">Descargar PDF</a>
        </div>
    </div>
@endsection
