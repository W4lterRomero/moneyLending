<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'officer', 'viewer']);
    }

    public function view(User $user, Payment $payment): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'officer']);
    }

    public function update(User $user, Payment $payment): bool
    {
        return in_array($user->role, ['admin', 'officer']);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->role === 'admin';
    }
}
