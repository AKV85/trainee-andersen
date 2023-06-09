<?php

namespace App\Notifications;

use Dompdf\Dompdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeletionNotification extends Notification
{
    use Queueable;

    protected $pdf;

    public function __construct(DomPDF $pdf)
    {
        $this->pdf = $pdf;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $pdfContents = $this->pdf->output();

        return (new MailMessage)
            ->subject('Account Deletion Notification')
            ->line('Your account has been deleted.')
            ->attachData($pdfContents, 'account_deletion.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
