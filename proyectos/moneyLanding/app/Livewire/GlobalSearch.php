<?php

namespace App\Livewire;

use App\Services\GlobalSearchService;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $term = '';
    public array $results = [];
    public bool $isSearching = false;
    public int $minLength = 2;

    public function render()
    {
        return view('livewire.global-search');
    }

    public function updatedTerm(): void
    {
        $term = trim($this->term);
        
        // Detect quick action commands with > prefix
        if (str_starts_with($term, '>')) {
            $this->results = $this->getQuickActions($term);
            return;
        }
        
        if (strlen($term) < $this->minLength) {
            $this->results = [];
            return;
        }

        try {
            $this->isSearching = true;
            $this->results = app(GlobalSearchService::class)->search($term)->toArray();
        } catch (\Throwable $e) {
            $this->results = [];
            session()->flash('error', 'Error en la búsqueda');
        } finally {
            $this->isSearching = false;
        }
    }

    protected function getQuickActions(string $term): array
    {
        $command = strtolower(trim(substr($term, 1)));
        
        $actions = [
            ['command' => 'dashboard', 'title' => 'Ir al Dashboard', 'icon' => '📊'],
            ['command' => 'clientes', 'title' => 'Ir a Clientes', 'icon' => '👥'],
            ['command' => 'finanzas', 'title' => 'Ir a Finanzas', 'icon' => '💰'],
            ['command' => 'prestamos', 'title' => 'Ir a Préstamos', 'icon' => '💳'],
            ['command' => 'financiamientos', 'title' => 'Ir a Financiamientos', 'icon' => '📦'],
            ['command' => 'pagos', 'title' => 'Ir a Pagos', 'icon' => '💵'],
            ['command' => 'config', 'title' => 'Configuración', 'icon' => '⚙️'],
            ['command' => 'nuevo', 'title' => 'Nuevo Préstamo', 'icon' => '➕'],
            ['command' => 'nuevo cliente', 'title' => 'Nuevo Cliente', 'icon' => '➕'],
        ];

        return collect($actions)
            ->filter(fn($a) => empty($command) || str_contains($a['command'], $command))
            ->map(fn($a) => [
                'type' => 'Acción',
                'title' => $a['icon'] . ' ' . $a['title'],
                'subtitle' => '>' . $a['command'],
                'url' => $this->getActionUrl($a['command']),
            ])
            ->values()
            ->toArray();
    }

    protected function getActionUrl(string $command): string
    {
        return match($command) {
            'dashboard' => route('dashboard'),
            'clientes' => route('clients.index'),
            'finanzas' => route('finance.index'),
            'prestamos' => route('loans.index'),
            'financiamientos' => route('financings.index'),
            'pagos' => route('payments.index'),
            'config' => route('settings.business'),
            'nuevo' => route('loans.create'),
            'nuevo cliente' => route('clients.create'),
            default => route('dashboard'),
        };
    }
}
