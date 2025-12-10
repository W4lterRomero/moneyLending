<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Loan;
use App\Models\Payment;
use App\Policies\ClientPolicy;
use App\Policies\LoanPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Client::class => ClientPolicy::class,
        Loan::class => LoanPolicy::class,
        Payment::class => PaymentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin-only', fn ($user) => $user->role === 'admin');
    }
}
