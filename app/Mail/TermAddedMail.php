<?php
// app/Mail/TermAddedMail.php

namespace App\Mail;

use App\Models\User;
use App\Models\Term;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TermAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $term;
    public $owner;

    /**
     * Create a new message instance.
     *
     * @param Term $term
     */
    public function __construct(User $owner, Term $term)
    {
        $this->owner = $owner;
        $this->term = $term;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Term Added')
                    ->view('emails.term_added');
    }
}
