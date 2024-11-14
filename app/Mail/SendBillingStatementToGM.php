<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendBillingStatementToGM extends Mailable
{
    use Queueable, SerializesModels;
    protected $pdf;
    protected $serialnumber;
    protected $company;
    protected $trainingdate;
    protected $course;
    public $subject;
    public $billingstatus;

    /**
     * Create a new message instance.
     */
    public function __construct($pdf = null, $serialnumber, $company, $trainingdate, $course, $subject, $billingstatus)
    {
        //
        $this->pdf = $pdf;
        $this->serialnumber = $serialnumber;
        $this->company = $company;
        $this->trainingdate = $trainingdate;
        $this->course = $course;
        $this->subject = $subject;
        $this->billingstatus = $billingstatus;
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Send Billing Statement To G M',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'mails.send-billing-statement-toGM',
    //     );
    // }

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
