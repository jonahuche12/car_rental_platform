<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class PromotionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $isPromoted;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $student, bool $isPromoted)
    {
        $this->student = $student;
        $this->isPromoted = $isPromoted;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $status = $this->isPromoted ? 'promoted' : 'not promoted';
        $subject = $this->isPromoted ? 'Promotion Notification' : 'Promotion Notification';

        return $this->subject($subject)
                    ->view('emails.promotion-notification')
                    ->with([
                        'student' => $this->student,
                        'status' => $status,
                    ]);
    }
}
