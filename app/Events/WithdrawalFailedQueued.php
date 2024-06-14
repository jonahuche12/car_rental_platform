<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\WithdrawalRequest; // Import the WithdrawalRequest model

class WithdrawalFailedQueued
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $withdrawal;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\WithdrawalRequest $withdrawal
     * @return void
     */
    public function __construct(WithdrawalRequest $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
