<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalFailed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $withdrawal;

    public function __construct($withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function build()
    {
        return $this->subject('Withdrawal Failed')
                    ->markdown('emails.withdrawal_failed')
                    ->with([
                        'accountName' => $this->withdrawal->account_name,
                        'amount' => $this->withdrawal->amount,
                    ]);
    }
}
