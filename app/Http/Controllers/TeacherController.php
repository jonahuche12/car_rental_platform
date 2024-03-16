<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\Assessment;
use App\Models\SchoolClassSection;
use App\Models\Exam;
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

    public function assignmentPage($courseId, $classSectionId, $teacherId)
    {
        // dd($teacherId);
        $class_section = SchoolClassSection::find($classSectionId);
        if ($class_section) {
            $school = $class_section->schoolClass->school;
            // dd($school);
            if ($school) {
                $assignments = Assignment::where('course_id',$courseId)->where('class_section_id',$classSectionId)->get();

                // dd($assignments);
                return view('teacher.assignments', compact('assignments', 'school', 'class_section'));
            }else {
                dd($class);
                return redirect()->back()->with('error', "School Not Found");
            }
            
        }else {
            return redirect()->back()->with('error', "No Class Section Found");
        }
        
    }

    public function assessmentPage($courseId, $classSectionId, $teacherId)
    {
        dd($teacherId);
    }

    public function examPage($courseId, $classSectionId, $teacherId)
    {
        dd($teacherId);
    }

    public function createAssignment(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Add more validation rules as needed
        ]);

        // Create a new assignment instance
        $assignment = new Assignment();
        $assignment->name = $request->name;
        $assignment->academic_session = '2023/2024';
        $assignment->description = $request->description;
        // Assign other form fields here

        // Save the assignment
        $assignment->save();

        // Optionally, you can return a response to indicate success
        return response()->json(['message' => 'Assignment created successfully'], 201);
    }

    

}
