<?php

namespace App\Mail;

use App\Traits\BillingModuleTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientConfirmationBoardNotification extends Mailable
{
    use Queueable, SerializesModels;
    use BillingModuleTrait;
    public $serialNumber,$company,$trainingDate,$course,$billingStatus,$subject;

    /**
     * Create a new message instance.
     */
    public function __construct($serialNumber,$company,$trainingDate,$course,$billingStatus,$subject)
    {
        $this->serialNumber = $serialNumber;
        $this->company = $company;
        $this->trainingDate = $trainingDate;
        $this->course = $course;
        $this->billingStatus = $billingStatus;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('mails.send-client-confirmation-board-reminder')
            ->with('serialnumber', $this->serialNumber)
            ->with('company', $this->company)
            ->with('trainingdate', $this->trainingDate)
            ->with('course', $this->course)
            ->with('billingstatus', $this->billingStatus);
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
