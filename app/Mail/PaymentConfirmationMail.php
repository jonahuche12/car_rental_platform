<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;


    public $transfer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transfer)
    {
        //
        $this->transfer = $transfer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.confirm_transfer')
        ->subject("Confirm Transfer");
    }
}
