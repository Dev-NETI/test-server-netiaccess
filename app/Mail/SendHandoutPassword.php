<?php

namespace App\Mail;

use App\Models\tblcourses;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendHandoutPassword extends Mailable
{
    use Queueable, SerializesModels;
    protected $courses;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
        $courses = tblcourses::where('handoutpath', '!=', '')
            ->orderBy('coursecode', 'ASC')
            ->get();

        $this->courses = $courses;
        // dd($this->courses);
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('OEX: Handout Password')
            ->with('courses', $this->courses)
            ->view('mails.send-handout-password');
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
