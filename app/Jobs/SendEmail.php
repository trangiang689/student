<?php

namespace App\Jobs;

use App\Mail\MailExpulsion;
use App\Mail\WelcomeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $fromUser;

    public function __construct($fromUser, $user)
    {
        $this->user = $user;
        $this->fromUser = $fromUser;
    }

    public function handle()
    {
        $email = new MailExpulsion($this->user, $this->fromUser);
        Mail::to($this->user)->send($email);
    }
}
