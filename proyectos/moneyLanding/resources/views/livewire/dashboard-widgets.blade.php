<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="text-xs uppercase text-slate-500 dark:text-slate-400">Resumen General</div>
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Dashboard</h1>
        </div>
        <a href="{{ route('dashboard', ['refresh' => 1]) }}" class="flex items-center gap-2 px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-400 hover:border-sky-200 dark:hover:border-sky-600 transition-colors shadow-sm">
            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            <span>Actualizar</span>
        </a>
    </div>

    {{-- KPIs Principales --}}
    <div class="grid md:grid-cols-4 gap-3">
        <div class="card border border-slate-200/80 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3m6 0c0-1.657-1.343-3-3-3m0 0V4m0 4a3 3 0 110 6m-3 0a3 3 0 106 0m-6 0H7m11 0h-4m-4 0H7m10 0a5 5 0 11-10 0"/></svg>
                    <span>Total Prestado</span>
                </div>
                <span class="text-[10px] px-2 py-1 rounded-full bg-sky-50 dark:bg-sky-900/50 text-sky-700 dark:text-sky-300">USD</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 dark:text-white mt-2">${{ number_format($metrics['total_lent'] ?? 0, 2) }}</div>
        </div>

        <div class="card border border-slate-200/80 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h5l2-6 4 12 2-6h5"/></svg>
                <span>Total Cobrado</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 dark:text-white mt-2">${{ number_format($metrics['total_collected'] ?? 0, 2) }}</div>
        </div>

        <div class="card border border-slate-200/80 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>Total Intereses Ganados</span>
                </div>
                <span class="text-[10px] px-2 py-1 rounded-full bg-purple-50 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300">USD</span>
            </div>
            <div class="text-2xl font-semibold text-purple-600 dark:text-purple-400 mt-2">${{ number_format($metrics['total_interest'] ?? 0, 2) }}</div>
        </div>

        <div class="card border border-slate-200/80 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h6"/></svg>
                <span>Préstamos Activos</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 dark:text-white mt-2">{{ $metrics['active_loans'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Barra de Progreso Simple --}}
    <div class="card border border-slate-200/80 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
        <div class="text-sm font-semibold text-slate-800 dark:text-white mb-3">Progreso de Cobranza</div>
        @php
            $lent = $metrics['total_lent'] ?? 1;
            $collected = $metrics['total_collected'] ?? 0;
            $percentage = $lent > 0 ? ($collected / $lent) * 100 : 0;
        @endphp
        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-6 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold"
                 style="width: {{ $percentage }}%">
                {{ number_format($percentage, 1) }}%
            </div>
        </div>
        <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
            Has cobrado <span class="font-semibold text-emerald-600 dark:text-emerald-400">${{ number_format($collected, 2) }}</span> de <span class="font-semibold text-slate-700 dark:text-slate-300">${{ number_format($lent, 2) }}</span>
        </div>
    </div>

    {{-- Top Profesiones y Empresas --}}
    <div class="grid md:grid-cols-2 gap-4">
        <div class="card border border-slate-200/80 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
            <div class="text-sm font-semibold text-slate-800 dark:text-white mb-3">Top Profesiones</div>
            @if(!empty($metrics['top_occupations']) && count($metrics['top_occupations']) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-4 py-2">Profesión</th>
                                <th class="px-4 py-2 text-right">Préstamos</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($metrics['top_occupations'] as $occupation)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="px-4 py-2 font-medium text-slate-900 dark:text-white">{{ $occupation->name }}</td>
                                    <td class="px-4 py-2 text-right text-slate-600 dark:text-slate-400">{{ $occupation->count }}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-sky-600 dark:text-sky-400">${{ number_format($occupation->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-slate-400 dark:text-slate-500">
                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <p class="text-sm">No hay datos de profesiones en este periodo.</p>
                </div>
            @endif
        </div>

        <div class="card border border-slate-200/80 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
            <div class="text-sm font-semibold text-slate-800 dark:text-white mb-3">Top Empresas</div>
            @if(!empty($metrics['top_companies']) && count($metrics['top_companies']) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-4 py-2">Empresa</th>
                                <th class="px-4 py-2 text-right">Préstamos</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($metrics['top_companies'] as $company)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="px-4 py-2 font-medium text-slate-900 dark:text-white">{{ $company->name }}</td>
                                    <td class="px-4 py-2 text-right text-slate-600 dark:text-slate-400">{{ $company->count }}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-sky-600 dark:text-sky-400">${{ number_format($company->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-slate-400 dark:text-slate-500">
                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="text-sm">No hay datos de empresas en este periodo.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Clientes Widget --}}
    <div class="card border border-slate-200/80 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
            <div class="text-sm font-semibold text-slate-800 dark:text-white">
                Top {{ $clientLimit }} Clientes
            </div>
            <div class="flex items-center gap-2">
                {{-- Order selector --}}
                <select wire:model.live="clientOrder" 
                    class="text-xs rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 py-1 px-2 focus:ring-sky-500">
                    <option value="recent">Más recientes</option>
                    <option value="name">Nombre A-Z</option>
                    <option value="loans">Más préstamos</option>
                </select>
                {{-- Limit selector --}}
                <select wire:model.live="clientLimit" 
                    class="text-xs rounded-lg border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 py-1 px-2 focus:ring-sky-500">
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                </select>
                <a href="{{ route('clients.index') }}" class="text-xs text-sky-600 hover:text-sky-700 dark:text-sky-400">
                    Ver todos →
                </a>
            </div>
        </div>
        @if(!empty($recentClients) && count($recentClients) > 0)
            <div class="space-y-2">
                @foreach($recentClients as $client)
                    <a href="{{ route('clients.show', $client['id']) }}" 
                       class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                            {{ strtoupper(substr($client['name'], 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-slate-800 dark:text-white truncate group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                                {{ $client['name'] }}
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                {{ $client['email'] ?? $client['phone'] ?? 'Sin contacto' }}
                            </div>
                        </div>
                        <div class="text-xs text-slate-400 dark:text-slate-500 shrink-0">
                            {{ \Carbon\Carbon::parse($client['created_at'])->diffForHumans() }}
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-6 text-slate-400 dark:text-slate-500">
                <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                <p class="text-sm">No hay clientes registrados aún</p>
                <a href="{{ route('clients.create') }}" class="mt-2 text-xs text-sky-600 hover:text-sky-700">+ Agregar cliente</a>
            </div>
        @endif
    </div>

    <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
        <span>Última actualización:</span>
        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $refreshedAt }}</span>
    </div>
</div>
