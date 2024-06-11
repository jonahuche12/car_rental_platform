<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RewardMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $student;
    public $category;

    public function __construct($student, $category)
    {
        $this->student = $student;
        $this->category = $category;
    }

    public function build()
    {
        $updateLink = route('account.update', ['category_id' => $this->category->id]); // Using the named route to generate the URL

        return $this->subject('Congratulations! You Have Earned a Reward')
                    ->view('emails.reward')
                    ->with([
                        'studentName' => $this->student->profile->full_name,
                        'category_name' => $this->category->name,
                        'updateLink' => $updateLink,
                    ]);
    }
}
