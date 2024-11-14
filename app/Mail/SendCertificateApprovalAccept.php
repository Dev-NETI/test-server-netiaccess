<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCertificateApprovalAccept extends Mailable
{
    public $enrolled;
    public $schedule;
    public $user;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->enrolled = $data['enrolled'];
        $this->schedule = $data['schedule'];
        $this->user = $data['user'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OEX: Certificate Approved for ' . $this->schedule->batchno . ' - ' . $this->schedule->course->coursename . ' ( ' . date('F d, Y', strtotime($this->schedule->startdateformat)) . ' - ' . date('F d, Y', strtotime($this->schedule->enddateformat)) . ' )',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-certificate-approval-accept',
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
