<?php

namespace App\Listeners;

use App\Events\WithdrawalFailedQueued;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteWithdrawalRequest implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param \App\Events\WithdrawalFailedQueued $event
     * @return void
     */
    public function handle(WithdrawalFailedQueued $event)
    {
        $event->withdrawal->delete();
    }
}
