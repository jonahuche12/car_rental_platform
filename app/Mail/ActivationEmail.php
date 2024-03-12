<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $merchant;

    public function __construct($user)
    {
        $this->user = $user;
        // $this->merchant = $merchant;
    }

    public function build()
    {
        return $this->view('emails.activation')
            ->subject('Account Activation')
            ->with(['user' => $this->user]);
    }
}
