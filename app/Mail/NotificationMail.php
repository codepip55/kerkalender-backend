<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $service;
    public $team;
    public $position;
    public $confirmationLink;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $service, $team, $position, $confirmationLink)
    {
        $this->user = $user;
        $this->service = $service;
        $this->team = $team;
        $this->position = $position;
        $this->confirmationLink = $confirmationLink;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Je bent toegevoegd aan een serviceteam')
            ->view('emails.new_member_notification');
    }
}
