<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInstructorApprovedAttendanceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $selected_instructor;
    public $date;
    public $type;
    public $time;

    /**
     * Create a new message instance.
     */
    public function __construct($email_data)
    {
        $this->selected_instructor = $email_data['user'];
        $this->date = $email_data['date'];
        if ($email_data['type'] == 1) {
            $this->type = 'Time in';
        } else {
            $this->type = 'Time out';
        }
        $this->time = date('H:i:s', strtotime($email_data['time']));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OEX: Approved failure to Time In/Out',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-instructor-approved-attendance-mail',
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
