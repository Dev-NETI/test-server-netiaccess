<?php

namespace App\Mail;

use App\Models\tblcourseschedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendZoomCredentials extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $course;
    public $trainingdate;
    public $enroledid;
    public $coursetypeid;
    public $zoom_link;
    public $dateonline;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $course, $trainingdate, $enroledid, $coursetypeid, $zoom_link, $dateonline)
    {
        $this->name = $name;
        $this->course = $course;
        $this->trainingdate = $trainingdate;
        $this->enroledid = $enroledid;
        $this->coursetypeid = $coursetypeid;
        $this->zoom_link = $zoom_link;
        $this->dateonline = $dateonline;
    }

    public function build()
    {
        return $this->subject('OEX: Zoom Credentials')
            ->with('name', $this->name)
            ->with('course', $this->course)
            ->with('trainingdate', $this->trainingdate)
            ->with('enroledid', $this->enroledid)
            ->with('coursetypeid', $this->coursetypeid)
            ->with('zoom_link', $this->zoom_link)
            ->view('mails.send-zoom-credentials');
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
