<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeneralRewardMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function build()
    {
        return $this->subject('Congratulations! You Have Earned Additional Study Connects')
                    ->view('emails.general_reward')
                    ->with([
                        'studentName' => $this->student->profile->full_name,
                    ]);
    }
}
