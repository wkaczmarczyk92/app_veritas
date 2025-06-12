<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Address;

class OfferEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public static $email = 'callcenter@grupa-veritas.pl';

    /**
     * Create a new message instance.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('app@grupa-veritas.pl', 'AppVeritas - zgłoszenie na oferty pracy'),
            subject: 'Nowe zgłoszenie opiekunki w aplikacji na oferty pracy',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails/offer-notification',
            with: [
                'data' => $this->data
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
