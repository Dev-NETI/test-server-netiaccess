<?php

namespace App\Mail;

use App\Models\tblinstructor;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInstructorAssignedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $selected_instructor;
    public $schedule;
    public $ins_type;
    public $total_enroled;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->schedule = $data['schedule'];
        $this->selected_instructor = User::where('user_id', $data['selected_instructor'])->first();
        $this->ins_type = $data['ins_type'];
        $this->total_enroled = $data['total_enroled'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OEX: Training assigned to ' . $this->selected_instructor->formal_name() . ' - ' . $this->schedule->course->coursename . ' ( ' . date('F d, Y', strtotime($this->schedule->startdateformat)) . ' - ' . date('F d, Y', strtotime($this->schedule->enddateformat)) . ' )',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.send-instructor-assigned-mail',
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
