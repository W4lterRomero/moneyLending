<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Financing;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class FinancingTable extends Component
{
    use WithFileUploads, WithPagination;

    #[Url]
    public string $search = '';
    
    #[Url]
    public string $statusFilter = 'active';
    
    #[Url]
    public string $viewMode = 'list'; // 'list' or 'gallery'

    // Modal state
    public bool $showModal = false;
    public ?int $editingId = null;

    // Form fields
    public $clientId = '';
    public string $productName = '';
    public $productImage = null;
    public $existingImage = null;
    public string $productPrice = '';
    public string $balance = '';
    public string $notes = '';
    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'clientId' => 'required|exists:clients,id',
            'productName' => 'required|string|max:255',
            'productImage' => 'nullable|image|max:2048',
            'productPrice' => 'required|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,paid,cancelled',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedProductImage(): void
    {
        $this->validateOnly('productImage', [
            'productImage' => 'nullable|image|max:2048',
        ]);
    }

    public function openModal(?int $id = null): void
    {
        $this->resetForm();
        
        if ($id) {
            $financing = Financing::find($id);
            if ($financing) {
                $this->editingId = $id;
                $this->clientId = $financing->client_id;
                $this->productName = $financing->product_name;
                $this->existingImage = $financing->product_image;
                $this->productPrice = (string) $financing->product_price;
                $this->balance = (string) $financing->balance;
                $this->notes = $financing->notes ?? '';
                $this->status = $financing->status;
            }
        }
        
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'client_id' => $this->clientId,
            'product_name' => $this->productName,
            'product_price' => (float) $this->productPrice,
            'balance' => (float) $this->balance,
            'notes' => $this->notes ?: null,
            'status' => $this->status,
        ];

        // Handle image upload
        if ($this->productImage) {
            $path = $this->productImage->store('financings', 'public');
            $data['product_image'] = $path;
        }

        // Auto-set status to paid if balance is 0
        if ($data['balance'] <= 0) {
            $data['status'] = 'paid';
            $data['balance'] = 0;
        }

        if ($this->editingId) {
            Financing::find($this->editingId)->update($data);
        } else {
            Financing::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function updateBalance(int $id, $newBalance): void
    {
        $financing = Financing::find($id);
        if ($financing) {
            $balance = max(0, (float) $newBalance);
            $financing->update([
                'balance' => $balance,
                'status' => $balance <= 0 ? 'paid' : 'active',
            ]);
        }
    }

    public function delete(int $id): void
    {
        Financing::destroy($id);
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->clientId = '';
        $this->productName = '';
        $this->productImage = null;
        $this->existingImage = null;
        $this->productPrice = '';
        $this->balance = '';
        $this->notes = '';
        $this->status = 'active';
    }

    public function render()
    {
        $query = Financing::with('client')
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function($q) {
                $q->where(function($q2) {
                    $q2->where('product_name', 'like', "%{$this->search}%")
                       ->orWhereHas('client', fn($c) => $c->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->orderByDesc('updated_at');

        $financings = $query->paginate(12);
        $clients = Client::orderBy('name')->get();

        // Summary stats
        $totalActive = Financing::active()->count();
        $totalOwed = Financing::active()->sum('balance');
        $totalPaid = Financing::paid()->count();
        $totalCollected = Financing::sum('product_price') - Financing::sum('balance');
        $totalValue = Financing::sum('product_price');
        $avgProgress = Financing::active()->count() > 0 
            ? (Financing::active()->sum('product_price') - Financing::active()->sum('balance')) / Financing::active()->sum('product_price') * 100 
            : 0;

        return view('livewire.financing-table', [
            'financings' => $financings,
            'clients' => $clients,
            'totalActive' => $totalActive,
            'totalOwed' => $totalOwed,
            'totalPaid' => $totalPaid,
            'totalCollected' => $totalCollected,
            'totalValue' => $totalValue,
            'avgProgress' => $avgProgress,
        ]);
    }
}
