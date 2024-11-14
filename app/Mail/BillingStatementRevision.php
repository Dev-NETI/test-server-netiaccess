<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BillingStatementRevision extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('mails.send-billing-statement-toGM')
            ->with('serialnumber', $this->serialnumber)
            ->with('company', $this->company)
            ->with('trainingdate', $this->trainingdate)
            ->with('course', $this->course)
            ->with('billingstatus', $this->billingstatus);
        // ->attachData($this->pdf->output(), 'billing-statement.pdf')
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
