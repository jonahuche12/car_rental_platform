<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class GuardianAddedYouAsWard extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The student who has been added as a ward.
     *
     * @var User
     */
    public $student;

    /**
     * The guardian who added the student as a ward.
     *
     * @var User
     */
    public $guardian;

    /**
     * The confirmation token.
     *
     * @var string
     */
    public $confirmationToken;

    /**
     * Create a new message instance.
     *
     * @param User $student
     * @param User $guardian
     * @param string $confirmationToken
     * @return void
     */
    public function __construct(User $guardian, User $student, $confirmationToken)
    {
        $this->student = $student;
        $this->guardian = $guardian;
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.guardian_added_you_as_ward')
                    ->with(['confirmationToken' => $this->confirmationToken]);
    }
}
