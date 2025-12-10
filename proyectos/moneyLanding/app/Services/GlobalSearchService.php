<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Support\Collection;

class GlobalSearchService
{
    public function search(string $term): Collection
    {
        $like = "%{$term}%";

        $clients = Client::search($term)
            ->take(5)
            ->get()
            ->map(fn ($c) => [
                'type' => 'Cliente',
                'title' => $c->name,
                'subtitle' => $c->email,
                'url' => route('clients.show', $c),
            ]);

        $loans = Loan::search($term)
            ->take(5)
            ->get()
            ->map(fn ($l) => [
                'type' => 'Préstamo',
                'title' => $l->code,
                'subtitle' => $l->client?->name,
                'url' => route('loans.show', $l),
            ]);

        $payments = Payment::search($term)
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'type' => 'Pago',
                'title' => $p->reference ?? $p->id,
                'subtitle' => $p->loan?->code,
                'url' => route('payments.show', $p),
            ]);

        return collect()
            ->merge($clients)
            ->merge($loans)
            ->merge($payments);
    }
}
