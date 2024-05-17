<?php

// app/Mail/AcademicSessionCreatedMail.php

namespace App\Mail;

use App\Models\User;
use App\Models\AcademicSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AcademicSessionCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $academicSession;
    public $owner;

    public function __construct(User $owner, AcademicSession $academicSession)
    {
        $this->owner = $owner;
        $this->academicSession = $academicSession;
    }

    public function build()
    {
        return $this->subject('New Academic Session Created')
                    ->view('emails.academic_session_created');
    }
}
