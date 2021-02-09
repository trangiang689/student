<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class MailExpulsion extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $fromUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $fromUser)
    {
        $this->user = $user;
        $this->fromUser = $fromUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->fromUser )->view('admin.mails.expulsion');
    }
}
