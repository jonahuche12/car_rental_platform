<?php

namespace App\Jobs;

use App\Models\User; 
use App\Mail\PromotionMail;
use App\Mail\GuardianPromotionMail; // New Mailable for guardian notification
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPromotionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $student;
    protected $isPromoted;

    /**
     * Create a new job instance.
     *
     * @param User $student
     * @param bool $isPromoted
     * @return void
     */
    public function __construct(User $student, bool $isPromoted)
    {
        $this->student = $student;
        $this->isPromoted = $isPromoted;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send email to student
        if (!empty($this->student) && !empty($this->student->email)) {
            Mail::to($this->student->email)->send(new PromotionMail($this->student, $this->isPromoted));
        }
    
        // Send email to guardians
            foreach ($this->student->guardians as $guardian) {
                if (!empty($guardian) && !empty($guardian->email)) {
                    Mail::to($guardian->email)->send(new GuardianPromotionMail($this->student, $this->isPromoted));
                }
            }
       
    }
     
    
}
