<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    //
    public function showFormClasses($teacherId)
    {
        // dd($teacherId);$
        $teacher = User::find($teacherId);
        if($teacher){
            $school = $teacher->school;
            $form_classes = $teacher->formClasses;
            // dd($school);
            return view('teacher.form_classes', compact('form_classes', 'school'));
        }
        else {
            return  redirect('home')->with('error', "You are not a Teacher");
        }
    }


    public function toggleAttendance(Request $request)
{
    try {
        // Retrieve input data
        $studentId = $request->input('student_id');
        $schoolId = $request->input('school_id');
        $teacherId = $request->input('teacher_id');

        // Begin a database transaction
        DB::beginTransaction();

        // Find or create the attendance record for the student and today's date
        $attendance = Attendance::updateOrCreate(
            ['user_id' => $studentId, 'date' => today()],
            ['school_id' => $schoolId, 'teacher_id' => $teacherId]
        );

        // Get the original attendance status
        $previousAttendance = $attendance->attendance;

        // Toggle the attendance status
        $attendance->attendance = !$attendance->attendance;

        // Save the attendance
        $attendance->save();

        // Commit the transaction
        DB::commit();

        // Determine the message based on the previous and current attendance status
        $message = $previousAttendance ? 'Attendance removed' : 'Attendance marked';

        // Return a success response
        return response()->json(['attendance' => $attendance->attendance, 'message' => $message]);
    } catch (\Exception $e) {
        // Rollback the transaction if an exception occurs
        DB::rollBack();

        // Log the error for debugging
        \Log::error($e);

        // Return an error response
        return response()->json(['error' => 'Failed to toggle attendance. Please try again later.' . $e->getMessage()], 500);
    }
}

    

}
