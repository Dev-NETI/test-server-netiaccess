<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPdeAssessment extends Mailable
{
    use Queueable, SerializesModels;

    public $departmentheadname;
    public $assessorname;
    public $pdecrewname;
    public $retrievepderequirements;
    public $pderequirementsarrayremarks;
    //   public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct($departmentheadname, $assessorname, $pdecrewname, $retrievepderequirements, $pderequirementsarrayremarks)
    {
        $this->departmentheadname = $departmentheadname;
        $this->assessorname = $assessorname;
        $this->pdecrewname = $pdecrewname;
        $this->retrievepderequirements = $retrievepderequirements;
        $this->pderequirementsarrayremarks = $pderequirementsarrayremarks;
        // $this->pdfPath = $pdfPath; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OEX: Pde Assessment',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-pde-assessment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
    
        ];
    }
}
