<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendBillingStatementToClient extends Mailable
{
    use Queueable, SerializesModels;
    protected $pdf;
    protected $serialnumber;
    protected $course;
    protected $trainingdate;

    /**
     * Create a new message instance.
     */
    public function __construct($pdf, $serialnumber, $course, $trainingdate)
    {
        //
        $this->pdf = $pdf;
        $this->serialnumber = $serialnumber;
        $this->course = $course;
        $this->trainingdate = $trainingdate;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('OEX: Billing Statement')
            ->view('mails.send-billing-statement-toClient')
            ->with('serialnumber', $this->serialnumber)
            ->with('course', $this->course)
            ->with('trainingdate', $this->trainingdate)
            ->attachData($this->pdf->output(), 'billing-statement.pdf');
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
