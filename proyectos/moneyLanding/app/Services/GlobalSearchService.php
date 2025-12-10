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

        $clients = Client::query()
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like);
            })
            ->limit(5)
            ->get()
            ->map(fn ($c) => [
                'type' => 'Cliente',
                'title' => $c->name,
                'subtitle' => $c->email,
                'url' => route('clients.show', $c),
            ]);

        $loans = Loan::query()
            ->with('client')
            ->where(function ($q) use ($like) {
                $q->where('code', 'like', $like)
                    ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', $like));
            })
            ->limit(5)
            ->get()
            ->map(fn ($l) => [
                'type' => 'Préstamo',
                'title' => $l->client?->name ?? $l->code,
                'subtitle' => $l->code,
                'url' => route('loans.show', $l),
            ]);

        $payments = Payment::query()
            ->with('loan')
            ->where(function ($q) use ($like) {
                $q->where('reference', 'like', $like)
                    ->orWhereHas('loan', fn ($lq) => $lq->where('code', 'like', $like));
            })
            ->limit(5)
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
