<?php

namespace App\Livewire;

use App\Services\KpiAggregator;
use Livewire\Component;

class DashboardWidgets extends Component
{
    public array $metrics = [];
    public string $refreshedAt = '';

    protected KpiAggregator $aggregator;

    public function boot(KpiAggregator $aggregator): void
    {
        $this->aggregator = $aggregator;
    }

    public function mount(): void
    {
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.dashboard-widgets');
    }

    public function refresh(): void
    {
        // Forzar actualización en el servicio (limpia la caché interna del servicio)
        $this->metrics = $this->aggregator->metrics('all', null, true);
        $this->refreshedAt = now()->format('d/m/Y H:i');
    }

    protected function refreshData(): void
    {
        // Obtener métricas (usa la caché del servicio si existe)
        $this->metrics = $this->aggregator->metrics('all');
        $this->refreshedAt = now()->format('d/m/Y H:i');
    }
}
