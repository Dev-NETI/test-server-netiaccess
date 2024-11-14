<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendPaymentSlip extends Mailable
{
    use Queueable, SerializesModels;
    protected $serialnumber;
    protected $company;
    protected $trainingdate;
    protected $filepath;
    protected $filenameWithExtension;
    protected $course;

    /**
     * Create a new message instance.
     */
    public function __construct($serialnumber, $company, $trainingdate, $filepath, $filenameWithExtension, $course)
    {
        //
        $this->serialnumber = $serialnumber;
        $this->company = $company;
        $this->trainingdate = $trainingdate;
        $this->filepath = $filepath;
        $this->filenameWithExtension = $filenameWithExtension;
        $this->course = $course;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        $fileContents = Storage::disk('public')->get($this->filepath);

        return $this->subject('OEX: Payment Slip')
            ->view('mails.send-paymentslip')
            ->with('serialnumber', $this->serialnumber)
            ->with('company', $this->company)
            ->with('trainingdate', $this->trainingdate)
            ->with('course', $this->course)
            ->attachData($fileContents, $this->filenameWithExtension);
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
