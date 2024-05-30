<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\ScholarshipCategory;

class ScholarshipCategoryUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $category;
    public $student;
    public $link;

    public function __construct(ScholarshipCategory $category, User $student, $link)
    {
        $this->category = $category;
        $this->student = $student;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Scholarship Category Updated')
                    ->view('emails.scholarship_category_updated');
    }
}
