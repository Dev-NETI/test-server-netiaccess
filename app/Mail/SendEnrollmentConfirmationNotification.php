<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEnrollmentConfirmationNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $course;
    public $trainingdate;
    public $enroledid;
    public $coursetypeid;
    public $bus_type;
    public $dorm_type;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $course, $trainingdate, $enroledid, $coursetypeid, $bus_type, $dorm_type)
    {
        $this->name = $name;
        $this->course = $course;
        $this->trainingdate = $trainingdate;
        $this->enroledid = $enroledid;
        $this->coursetypeid = $coursetypeid;
        $this->bus_type = $bus_type;
        $this->dorm_type = $dorm_type;
    }

    public function build()
    {
        return $this->subject('OEX: Enrollment Confirmation')
            ->with('name', $this->name)
            ->with('course', $this->course)
            ->with('trainingdate', $this->trainingdate)
            ->with('enroledid', $this->enroledid)
            ->with('coursetypeid', $this->coursetypeid)
            ->with('bus_type', $this->bus_type)
            ->with('dorm_type', $this->dorm_type)
            ->view('mails.send-enrollment-confirmation-notification');
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
