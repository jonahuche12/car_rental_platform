<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScholarshipEnrollmentMail extends Mailable
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
        return $this->subject('Scholarship Enrollment Confirmation')
                    ->view('emails.scholarship_enrollment')
                    ->with([
                        'student' => $this->student,
                        'category' => $this->category,
                    ]);
    }
}
