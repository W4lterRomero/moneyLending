<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\ClientReference;
use Livewire\Component;

class ClientReferences extends Component
{
    public Client $client;
    public $references = [];
    public bool $showModal = false;

    public $referenceId = null;
    public $type = 'personal';
    public $name = '';
    public $phone = '';
    public $second_phone = '';
    public $email = '';
    public $relationship = '';
    public $address = '';
    public $occupation = '';
    public $notes = '';

    protected $rules = [
        'type' => 'required|in:personal,family,work',
        'name' => 'required|string|max:255',
        'phone' => 'required|string',
        'second_phone' => 'nullable|string',
        'email' => 'nullable|email',
        'relationship' => 'required|string',
        'address' => 'nullable|string',
        'occupation' => 'nullable|string',
        'notes' => 'nullable|string',
    ];

    public function mount(): void
    {
        $this->loadReferences();
    }

    public function loadReferences(): void
    {
        $this->references = $this->client->references()->latest()->get();
    }

    public function openModal($referenceId = null): void
    {
        $this->resetForm();

        if ($referenceId) {
            $reference = ClientReference::findOrFail($referenceId);
            $this->referenceId = $reference->id;
            $this->type = $reference->type;
            $this->name = $reference->name;
            $this->phone = $reference->phone;
            $this->second_phone = $reference->second_phone;
            $this->email = $reference->email;
            $this->relationship = $reference->relationship;
            $this->address = $reference->address;
            $this->occupation = $reference->occupation;
            $this->notes = $reference->notes;
        }

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->referenceId) {
            ClientReference::findOrFail($this->referenceId)->update([
                'type' => $this->type,
                'name' => $this->name,
                'phone' => $this->phone,
                'second_phone' => $this->second_phone,
                'email' => $this->email,
                'relationship' => $this->relationship,
                'address' => $this->address,
                'occupation' => $this->occupation,
                'notes' => $this->notes,
            ]);
        } else {
            ClientReference::create([
                'client_id' => $this->client->id,
                'type' => $this->type,
                'name' => $this->name,
                'phone' => $this->phone,
                'second_phone' => $this->second_phone,
                'email' => $this->email,
                'relationship' => $this->relationship,
                'address' => $this->address,
                'occupation' => $this->occupation,
                'notes' => $this->notes,
            ]);
        }

        $this->loadReferences();
        $this->showModal = false;
        session()->flash('success', 'Referencia guardada exitosamente');
    }

    public function delete($id): void
    {
        ClientReference::findOrFail($id)->delete();
        $this->loadReferences();
        session()->flash('success', 'Referencia eliminada');
    }

    public function markAsVerified($id): void
    {
        ClientReference::findOrFail($id)->update([
            'verified' => true,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);
        $this->loadReferences();
    }

    private function resetForm(): void
    {
        $this->referenceId = null;
        $this->type = 'personal';
        $this->name = '';
        $this->phone = '';
        $this->second_phone = '';
        $this->email = '';
        $this->relationship = '';
        $this->address = '';
        $this->occupation = '';
        $this->notes = '';
    }

    public function render()
    {
        return view('livewire.client-references');
    }
}
