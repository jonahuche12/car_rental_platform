<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class PopulateDailyAttendance extends Command
{
    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    protected $signature = 'attendance:populate';
    protected $description = 'Populate daily attendance for all students';

    public function handle()
    {
        $date = Carbon::today()->toDateString();
        $students = User::whereHas('profile', function ($query) {
            $query->where('role', 'student');
        })->get();

        foreach ($students as $student) {
            Attendance::updateOrCreate(
                ['student_id' => $student->id, 'date' => $date],
                ['attendance' => false]
            );
        }

        $this->info('Daily attendance populated successfully.');
    }
}
