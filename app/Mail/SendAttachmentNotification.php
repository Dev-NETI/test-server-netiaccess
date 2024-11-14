<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAttachmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $attachment;
    public $date;
    public $attachmenttype;
    public $emailuser;
    public $months;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $attachment, $date, $attachmenttype, $emailuser, $months)
    {

        $this->name = $name;
        $this->email = $email;
        $this->attachment = $attachment;
        $this->date = $date;
        $this->attachmenttype = $attachmenttype;
        $this->emailuser = $emailuser;
        $this->months = $months;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OEX: Attachment Expiration Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-attachment-notification',
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
