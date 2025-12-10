<?php

namespace App\Jobs;

use App\Models\Installment;
use App\Notifications\LoanDueNotification;
use App\Notifications\PaymentOverdueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CheckDueLoansJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = Carbon::today();

        $upcoming = Installment::with('loan.client')
            ->whereDate('due_date', '<=', $today->copy()->addDays(3))
            ->whereNull('paid_at')
            ->get();

        foreach ($upcoming as $installment) {
            optional($installment->loan->owner)->notify(new LoanDueNotification($installment->loan));
        }

        $overdue = Installment::with('loan.owner')
            ->where('status', 'overdue')
            ->get();

        foreach ($overdue as $installment) {
            optional($installment->loan->owner)->notify(new PaymentOverdueNotification($installment));
        }
    }
}
