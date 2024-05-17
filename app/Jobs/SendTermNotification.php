<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Term;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\TermAddedMail as TermAddedMail;

class SendTermNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $owner;
    protected $term;

    /**
     * Create a new job instance.
     *
     * @param  User  $owner
     * @param  Term  $term
     * @return void
     */
    public function __construct(User $owner, Term $term)
    {
        $this->owner = $owner;
        $this->term = $term;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($this->owner->email && $this->owner->profile) {
                // Accessing properties safely
                Mail::to($this->owner->email)->send(new TermAddedMail($this->owner, $this->term));
            } else {
                // Log the error if email or profile is missing
                \Log::error('Owner email or profile missing: ' . $this->owner->id);
            }
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error sending term notification email: ' . $e->getMessage());
        }
    }
}
