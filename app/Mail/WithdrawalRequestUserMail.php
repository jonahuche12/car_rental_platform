<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequestUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawalRequest;

    public function __construct($withdrawalRequest)
    {
        $this->withdrawalRequest = $withdrawalRequest;
    }

    public function build()
    {
        return $this->view('emails.withdrawal_request_user')
                    ->with([
                        'token' => $this->withdrawalRequest->token,
                        'amount' => $this->withdrawalRequest->amount,
                        'user' => $this->withdrawalRequest->user,
                    ]);
    }
}