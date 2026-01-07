<?php

namespace App\Livewire;

use App\Models\Client;
use App\Services\KpiAggregator;
use Livewire\Component;

class DashboardWidgets extends Component
{
    public array $metrics = [];
    public string $refreshedAt = '';
    public $recentClients = [];
    
    // Client widget customization
    public int $clientLimit = 5;
    public string $clientOrder = 'recent'; // recent, name, loans

    protected KpiAggregator $aggregator;

    public function boot(KpiAggregator $aggregator): void
    {
        $this->aggregator = $aggregator;
    }

    public function mount(): void
    {
        if (request()->has('refresh')) {
            $this->metrics = $this->aggregator->metrics('all', null, true);
            $this->refreshedAt = now()->format('d/m/Y H:i:s');
        } else {
            $this->refreshData();
        }
        $this->loadRecentClients();
    }

    public function render()
    {
        return view('livewire.dashboard-widgets');
    }

    public function refresh(): void
    {
        sleep(1);
        $this->metrics = $this->aggregator->metrics('all', null, true);
        $this->refreshedAt = now()->format('d/m/Y H:i:s');
        $this->loadRecentClients();
        $this->dispatch('dashboard-refreshed');
    }

    public function updatedClientLimit(): void
    {
        $this->loadRecentClients();
    }

    public function updatedClientOrder(): void
    {
        $this->loadRecentClients();
    }

    protected function refreshData(): void
    {
        $this->metrics = $this->aggregator->metrics('all');
        $this->refreshedAt = now()->format('d/m/Y H:i:s');
    }

    protected function loadRecentClients(): void
    {
        $query = Client::query();
        
        // Apply ordering
        switch ($this->clientOrder) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'loans':
                $query->withCount('loans')->orderByDesc('loans_count');
                break;
            default: // recent
                $query->latest('created_at');
        }
        
        $this->recentClients = $query
            ->take($this->clientLimit)
            ->get(['id', 'name', 'email', 'phone', 'created_at'])
            ->toArray();
    }
}
