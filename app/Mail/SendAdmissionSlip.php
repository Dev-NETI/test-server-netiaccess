<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAdmissionSlip extends Mailable
{
    use Queueable, SerializesModels;
    protected $pdf;
    /**
     * Create a new message instance.
     */
    public function __construct($pdf)
    {
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('OEX: APPROVED ENROLLMENT E-ADMISSION SLIP')
            ->view('mails.send-admission-slip')
            ->attachData($this->pdf->output(), 'admission-slip.pdf');
    }
}
