<?php

namespace App\Notifications;

use App\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Installment $installment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Cuota {$this->installment->number} vencida")
            ->line("La cuota {$this->installment->number} del préstamo {$this->installment->loan?->code} está vencida.")
            ->action('Revisar préstamo', route('loans.show', $this->installment->loan))
            ->line('Coordina el seguimiento o reprogramación.');
    }
}
