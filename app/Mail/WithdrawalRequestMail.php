<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawalRequest;

    public function __construct($withdrawalRequest)
    {
        $this->withdrawalRequest = $withdrawalRequest;
    }

    public function build()
    {
        return $this->view('emails.withdrawal_request')
                    ->with([
                        'token' => $this->withdrawalRequest->token,
                        'amount' => $this->withdrawalRequest->amount,
                        'school' => $this->withdrawalRequest->school,
                    ]);
    }
}