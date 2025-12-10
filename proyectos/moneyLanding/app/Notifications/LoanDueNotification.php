<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Loan $loan)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Préstamo {$this->loan->code} próximo a vencer")
            ->line("El préstamo {$this->loan->code} para {$this->loan->client?->name} vence el {$this->loan->next_due_date?->format('d/m/Y')}.")
            ->action('Ver préstamo', route('loans.show', $this->loan))
            ->line('Asegúrate de coordinar el cobro o reagendar el pago.');
    }
}
