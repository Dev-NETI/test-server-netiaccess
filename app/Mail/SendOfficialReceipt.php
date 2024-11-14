<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendOfficialReceipt extends Mailable
{
    use Queueable, SerializesModels;
    protected $serialnumber;
    protected $trainingdate;
    protected $filepath;
    protected $filenameWithExtension;
    protected $course;

    /**
     * Create a new message instance.
     */
    public function __construct($serialnumber, $trainingdate, $filepath, $filenameWithExtension, $course)
    {
        //
        $this->serialnumber = $serialnumber;
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

        return $this->subject('OEX: Official Receipt')
            ->view('mails.send-official-receipt')
            ->with('serialnumber', $this->serialnumber)
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
