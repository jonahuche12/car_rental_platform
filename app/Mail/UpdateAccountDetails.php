<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateAccountDetails extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\WithdrawalRequest $withdrawal
     * @return void
     */
    public function __construct($withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = $this->withdrawal->token;
        $updateLink = route('withdrawal.updateUserAccount', ['token' => $token]);

        return $this->markdown('emails.update_account_details')
                    ->with([
                        'accountName' => $this->withdrawal->account_name,
                        'bankName' => $this->withdrawal->bank_name,
                        'accountNumber' => $this->withdrawal->account_number,
                        'amount' => $this->withdrawal->amount,
                        'updateLink' => $updateLink,
                    ]);
    }
}
