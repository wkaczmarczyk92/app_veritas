<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class ExportPayoutRequestsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public $att_path = null;

    public static $emails = [
        'wyplaty@grupa-veritas.pl',
        'wyplaty-regiony@grupa-veritas.pl',
    ];

    public static $cc_emails = [
        'm.walas@grupa-veritas.pl',
        'w.donajski@grupa-veritas.pl',
        'd.wisniewska@grupa-veritas.pl',
        'l.fil@grupa-veritas.pl',
        // 'r.reitzig@grupa-veritas.pl',
        'm.binko@grupa-veritas.pl',
        'bok@grupa-veritas.pl',
        // 'a.pniewski@grupa-veritas.pl',
        'a.wisniewska@grupa-veritas.pl',
        'k.soroka@grupa-veritas.pl'
    ];

    /**
     * Create a new message instance.
     */
    public function __construct($att_path)
    {
        $this->att_path = $att_path;
        // $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'VeritasApp - lista z wnioskami o wypłatę',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails/export-payout-requests',
            // with: [
            //     'data' => $this->data
            // ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('exports', $this->att_path)
        ];
    }
}
