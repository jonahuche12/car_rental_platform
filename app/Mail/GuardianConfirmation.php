<?php

namespace App\Mail;

use App\Models\User; // Update the import statement to match the namespace of your User model

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuardianConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    /**
     * The guardian who added the student as a ward.
     *
     * @var User
     */
    public $guardian;

    /**
     * Create a new message instance.
     *
     * @param User $guardian
     * @param User $student
     * @return void
     */
    public function __construct(User $guardian, User $student)
    {
        $this->student = $student;
        $this->guardian = $guardian;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Guardian Confirmation")->view('emails.guardian_confirmation');
    }
}
