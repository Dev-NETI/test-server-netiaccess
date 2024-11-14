<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPdeCertificateApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $crewname;
    public $crewattachment;
    public $pdecertificate;

    /**
     * Create a new message instance.
     */
    public function __construct($crewname, $crewattachment, $pdecertificate)
    {
        $this->crewname = $crewname;
        $this->crewattachment = $crewattachment;
        $this->pdecertificate = $pdecertificate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OEX: Send Pde Certificate Approval',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-pde-certificate-approval',
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
            public_path($this->crewattachment),
            public_path($this->pdecertificate),
        ];
    }
}
