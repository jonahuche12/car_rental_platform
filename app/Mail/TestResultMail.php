<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $test;
    public $totalScore;
    public $passed;

    public function __construct($user, $test, $totalScore, $passed)
    {
        $this->user = $user;
        $this->test = $test;
        $this->totalScore = $totalScore;
        $this->passed = $passed;
    }

    public function build()
    {
        return $this->view('emails.test-result')
                    ->subject('Your Test Results')
                    ->with([
                        'user' => $this->user,
                        'test' => $this->test,
                        'score' => $this->totalScore,
                        'passed' => $this->passed,
                    ]);
    }
}
