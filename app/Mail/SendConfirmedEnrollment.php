<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendConfirmedEnrollment extends Mailable
{
    use Queueable, SerializesModels;


    public $enrol;
    public $trainee;
    protected $pdf;

    /**
     * Create a new message instance.
     */
    public function __construct($enrol, $trainee, $pdf)
    {
        $this->enrol = $enrol;
        $this->trainee = $trainee;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OEX: Send Confirmed Enrollment',

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-confirmed-enrollment',
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
    public function build()
    {
        return $this->view('mails.send-confirmed-enrollment')
            ->attachData($this->pdf->output(), 'admission-slip.pdf');
    }
}
