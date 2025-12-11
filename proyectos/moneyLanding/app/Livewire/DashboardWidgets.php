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
        cache()->forget('dashboard.metrics');
        $this->aggregator->metrics('month', null, true);
        $this->refreshData();
    }

    protected function refreshData(): void
    {
        // Caché agresivo de 1 hora para Raspberry Pi
        $this->metrics = cache()->remember('dashboard.metrics', 3600, function () {
            return $this->aggregator->metrics('month');
        });

        $this->refreshedAt = now()->format('d/m/Y H:i');
    }
}
