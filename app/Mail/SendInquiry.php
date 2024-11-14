<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInquiry extends Mailable
{
    use Queueable, SerializesModels;

    // public $emailMessage;
    public $name;
    public $email;
    public $mobile;
    public $datenow;
    public $company;
    public $inquiry_text;
    public $inquirytypecontent;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $mobile,$datenow, $company, $inquiry_text,$inquirytypecontent)
    {
        // $this->emailMessage = $emailMessage;
        $this->name = $name;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->datenow = $datenow;
        $this->company = $company;
        $this->inquiry_text = $inquiry_text;
        $this->inquirytypecontent = $inquirytypecontent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $inquirytypecontentSubject = $this->inquirytypecontent; // Assuming $inquiry_text is a property in your class
        return new Envelope(
            subject: $inquirytypecontentSubject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-inquiry',
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
