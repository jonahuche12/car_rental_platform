<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\AcademicSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\AcademicSessionCreatedMail as AcademicSessionCreatedMail;

class SendAcademicSessionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $owner;
    protected $academicSession;

    public function __construct(User $owner, AcademicSession $academicSession)
    {
        $this->owner = $owner;
        $this->academicSession = $academicSession;
    }

    public function handle()
    {
        try {
            if ($this->owner->email && $this->owner->profile) {
                // dd($this->owner->email);
                Mail::to($this->owner->email)->send(new AcademicSessionCreatedMail($this->owner, $this->academicSession));
            } else {
                // dd("Error");
                \Log::error('Owner email or profile missing: ' . $this->owner->id);
            }
        } catch (\Exception $e) {
            \Log::error('Error sending academic session notification email: ' . $e->getMessage());
        }
    }
}

