<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    /**
     * Create a new message instance.
     *
     * @param string $token The reset token.
     */
    public function __construct($token)
    {
        $this->token=$token;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope The message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Email',
            tags:['Reset']
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content The message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reset_password',
            with: [
                'url' => $this->token,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment> The attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
