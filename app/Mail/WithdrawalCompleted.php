<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalCompleted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $withdrawal;

    /**
     * Create a new message instance.
     *
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
        return $this->subject('Withdrawal Completed')
                    ->markdown('emails.withdrawal_completed')
                    ->with([
                        'accountName' => $this->withdrawal->account_name,
                        'bankName' => $this->withdrawal->bank_name,
                        'accountNo' => $this->withdrawal->account_number,
                        'amount' => $this->withdrawal->amount,
                        'processedAt' => $this->withdrawal->processed_at,
                    ]);
    }
}
