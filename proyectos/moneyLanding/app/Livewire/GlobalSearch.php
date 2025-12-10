<?php

namespace App\Livewire;

use App\Services\GlobalSearchService;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $term = '';
    public array $results = [];

    public function render()
    {
        return view('livewire.global-search');
    }

    public function updatedTerm(): void
    {
        $this->results = app(GlobalSearchService::class)->search($this->term)->toArray();
    }
}
