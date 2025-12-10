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
        if (strlen(trim($this->term)) < $this->minLength) {
            $this->results = [];
            return;
        }

        $this->isSearching = true;
        $this->results = app(GlobalSearchService::class)->search($this->term)->toArray();
        $this->isSearching = false;
    }
}
