<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangedPriceNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $oldPrice;
    public $currentPrice;

    /**
     * Create a new message instance.
     */
    public function __construct($subscription, $oldPrice, $currentPrice)
    {
        $this->subscription = $subscription;
        $this->oldPrice = $oldPrice;
        $this->currentPrice = $currentPrice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Зміна ціни: ' . $this->subscription->advertisement->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.changed-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
