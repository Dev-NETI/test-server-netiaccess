<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendJissEmailNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $content;
    public $filePath;

    public function __construct($content, $email, $filePath = null)
    {
        $this->content = $content;
        $this->email = $email;
        $this->filePath = $filePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Jiss Email Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-jiss-notifications',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->filePath == NULL) {
            return [];
        } else {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($this->filePath)
                    ->as('billing_statement.pdf') // Optional: specify the name of the file
                    ->withMime('application/pdf'),
            ];
        }
    }
}
