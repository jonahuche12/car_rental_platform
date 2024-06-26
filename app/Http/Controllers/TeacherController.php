<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\School;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\StudentResult;
use App\Models\SchoolClass;
use App\Models\SchoolClassSection;
use App\Models\PromotionCriteria;
use App\Models\Exam;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Jobs\SendPromotionEmail;

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
            // dd($school->term);
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

            // Retrieve academic session and term for the school
            $school = School::find($schoolId);
            $academicSessionId = $school->academic_session_id;
            $termId = $school->term_id;

            // Begin a database transaction
            DB::beginTransaction();

            // Find or create the attendance record for the student, today's date, academic session, and term
            $attendance = Attendance::updateOrCreate(
                ['user_id' => $studentId, 'date' => today(), 'academic_session_id' => $academicSessionId, 'term_id' => $termId],
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
        $course = Course::find($courseId);
        if ($class_section && $course) {
            $school = $class_section->schoolClass->school;
            // dd($school);
            if ($school) {
                $assignments = Assignment::where('course_id',$courseId)->where('class_section_id',$classSectionId)->latest()->get();
                $academicSessions = AcademicSession::with(['terms.assignments' => function ($query) use ($courseId, $classSectionId) {
                    $query->where('course_id', $courseId)->where('class_section_id', $classSectionId)->latest();
                }])->get();

                // dd($assignments);
                return view('teacher.assignments', compact('academicSessions','assignments','course','classSectionId', 'school', 'class_section'));
            }else {
                // dd($class);
                return redirect()->back()->with('error', "School Not Found");
            }
            
        }else {
            return redirect()->back()->with('error', "No Class Section Found");
        }
        
    }

    public function assessmentPage($courseId, $classSectionId, $teacherId)
    {
        // dd($teacherId);
        $class_section = SchoolClassSection::find($classSectionId);
        $course = Course::find($courseId);
        if ($class_section && $course) {
            $school = $class_section->schoolClass->school;
            // dd($school);
            if ($school) {
                $assessments = Assessment::where('course_id',$courseId)->where('class_section_id',$classSectionId)->latest()->get();
                $academicSessions = AcademicSession::with(['terms.assessments' => function ($query) use ($courseId, $classSectionId) {
                    $query->where('course_id', $courseId)->where('class_section_id', $classSectionId)->latest();
                }])->get();

                // dd($assignments);
                return view('teacher.assessments', compact('academicSessions','assessments','course','classSectionId', 'school', 'class_section'));
            }else {
                // dd($class);
                return redirect()->back()->with('error', "School Not Found");
            }
            
        }else {
            return redirect()->back()->with('error', "Class Section Not Found");
        }
    }

    public function examPage($courseId, $classSectionId, $teacherId)
    {
        // dd($teacherId);
        $class_section = SchoolClassSection::find($classSectionId);
        $course = Course::find($courseId);
        if ($class_section && $course) {
            $school = $class_section->schoolClass->school;
            // dd($school);
            if ($school) {
                $exams = Exam::where('course_id',$courseId)->where('class_section_id',$classSectionId)->latest()->get();
                $academicSessions = AcademicSession::with(['terms.exams' => function ($query) use ($courseId, $classSectionId) {
                    $query->where('course_id', $courseId)->where('class_section_id', $classSectionId)->latest();
                }])->get();
                // dd($assignments);
                return view('teacher.exams', compact('academicSessions','exams','course','classSectionId', 'school', 'class_section'));
            }else {
                // dd($class);
                return redirect()->back()->with('error', "School Not Found");
            }
            
        }else {
            return redirect()->back()->with('error', "Class Section Not Found");
        }
    }

    public function createAssignment(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|integer',
            'due_date' => 'required|date',
            'complete_score'=> 'required|numeric',
            'class_section_id' => 'required|integer', // Assuming class_section_id is required
            // Add more validation rules as needed
        ]);
    
        // Retrieve authenticated user and associated school
        $user = auth()->user();
        $school = $user->school;
    
        // Check if the school and its academic session are available
        if ($school && $school->academicSession) {
            // Check if the term belongs to the academic session
            if ($school->term->academic_session_id !== $school->academicSession->id) {
                // Return a message to inform the teacher to contact their owner to update their school term
                return response()->json(['error' => 'Please contact your school owner to update the school term'], 400);
            }
    
            // Create a new assignment instance
            $assignment = new Assignment();
            $assignment->course_id = $request->course_id;
            $assignment->teacher_id = $user->id; // Assuming authenticated user is the teacher
            $assignment->due_date = $request->due_date;
            $assignment->school_id = $school->id;
            $assignment->complete_score = $request->complete_score;
            $assignment->class_section_id = $request->class_section_id;
            $assignment->name = $request->name;
            $assignment->description = $request->description;
    
            // Associate the assignment with the academic session and term
            $assignment->academic_session_id = $school->academicSession->id;
            $assignment->term_id = $school->term->id;
    
            // Save the assignment
            $assignment->save();
    
            // Return a response to indicate success
            return response()->json(['message' => 'Assignment created successfully'], 201);
        }
    
        // If the school or its academic session is not available, return an error response
        return response()->json(['error' => 'School or academic session not found'], 404);
    }
    

    public function editAssignment(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'description',
                'course_id',
                'due_date',
                'complete_score',
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = \Validator::make($validatedData, [
                'due_date' => 'required|date',
                'name' => 'required|string|max:255',
                'course_id' => 'required|integer',
                'description' => 'nullable|string',
                'complete_score' => 'required|numeric'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the assignment by ID
            $assignment = Assignment::findOrFail($id);
    
            // Update the assignment with the validated data
            $assignment->update($validatedData);
    
            // Return the updated assignment as JSON response
            return response()->json(['message'=>"Assignment Updated Successfully",'assignment'=>$assignment], 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the assignment.' . $e->getMessage()], 500);
        }
    }

    public function deleteAssignment($assignmentId)
    {
        try {
            $assignment = Assignment::findOrFail($assignmentId);

            // Check if there are any grades associated with this assignment
            $existingGrades = Grade::where('assignment_id', $assignmentId)->exists();

            // If there are existing grades, return an error response
            if ($existingGrades) {
                return response()->json(['error' => 'This assignment cannot be deleted because it contains grades.'], 403);
            }

            // If there are no existing grades, proceed with deletion
            // Check if the authenticated user has permission to delete the assignment
            $authenticatedUser = auth()->user();
            $isSchoolOwner = $authenticatedUser->ownedSchools()->where('id', $assignment->school_id)->exists();

                
            $hasPermission = $authenticatedUser->profile && $authenticatedUser->school_id == $assignment->school_id &&
            $authenticatedUser->profile->teacher_confirmed ;

            if (!$isSchoolOwner && !$hasPermission) {
                return response()->json(['error' => 'You do not have permission to remove this assignment.'], 403);
            }

            // Use a transaction to ensure data consistency
            DB::beginTransaction();

            try {
                // Delete the assignment
                $assignment->delete();

                // Commit the transaction
                DB::commit();

                return response()->json(['message' => 'Assignment deleted successfully']);
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollBack();
                \Log::error($e);

                return response()->json(['error' => 'Failed to delete assignment. Please try again.' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(['error' => 'Failed to find the assignment. Please try again.'], 404);
        }
    }
    
    public function saveGrade(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'user_id' => 'required|integer',
                'assignment_id' => 'required|integer',
                'score' => 'required|integer|min:0|max:999',
                'course_id' => 'required|integer',
            ]);

            // Extract data from the request
            $userId = $request->input('user_id');
            $assignmentId = $request->input('assignment_id');
            $score = $request->input('score');
            
            $courseId = $request->input('course_id');

            // Retrieve the assignment
            $assignment = Assignment::findOrFail($assignmentId);
            

            // Ensure the score does not exceed the complete_score
            if ($score > $assignment->complete_score) {
                return response()->json(['error' => 'Score cannot be greater than the complete score'], 422);
            }

            // Update assignment's academic session and term if null
            if (is_null($assignment->academic_session_id) || is_null($assignment->term_id)) {
                $assignment->academic_session_id = auth()->user()->school->academicSession->id;
                $assignment->term_id = auth()->user()->school->term->id;
                $assignment->save();
            }

            // Check if the grade already exists for this user and assignment
            $existingGrade = Grade::where('user_id', $userId)
                ->where('assignment_id', $assignmentId)
                ->first();

            if ($existingGrade) {
                // Update the existing grade
                $existingGrade->score = $score;
                $existingGrade->save();
            } else {
                // Create a new grade
                Grade::create([
                    'user_id' => $userId,
                    'assignment_id' => $assignmentId,
                    'course_id' => $courseId,
                    'score' => $score,
                    'academic_session_id' => $assignment->academic_session_id,
                    'complete_score' => $assignment->complete_score,
                    'term_id' => $assignment->term_id,
                ]);
            }

            // Return a success response
            return response()->json(['message' => 'Score saved successfully', 'score' => $score], 200);
        } catch (\Exception $e) {
            // Return an error response if any exception occurs
            return response()->json(['error' => 'Failed to save score: ' . $e->getMessage()], 500);
        }
    }

    public function saveGradeAssessment(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'user_id' => 'required|integer',
                'assessment_id' => 'required|integer',
                'score' => 'required|integer|min:0|max:999',
                'course_id' => 'required|integer',
            ]);

            // Extract data from the request
            $userId = $request->input('user_id');
            $assessmentId = $request->input('assessment_id');
            $score = $request->input('score');
            $courseId = $request->input('course_id');

            // Retrieve the assessment
            $assessment = Assessment::findOrFail($assessmentId);

            // Ensure the score does not exceed the complete_score
            if ($score > $assessment->complete_score) {
                return response()->json(['error' => 'Score cannot be greater than the complete score'], 422);
            }

            // Update assessment's academic session and term if null
            if (is_null($assessment->academic_session_id) || is_null($assessment->term_id)) {
                $assessment->academic_session_id = auth()->user()->school->academicSession->id;
                $assessment->term_id = auth()->user()->school->term->id;
                $assessment->save();
            }

            // Check if the grade already exists for this user and assessment
            $existingGrade = Grade::where('user_id', $userId)
                ->where('assessment_id', $assessmentId)
                ->first();

            if ($existingGrade) {
                // Update the existing grade
                $existingGrade->score = $score;
                $existingGrade->save();
            } else {
                // Create a new grade
                Grade::create([
                    'user_id' => $userId,
                    'assessment_id' => $assessmentId,
                    'course_id' => $courseId,
                    'score' => $score,
                    'academic_session_id' => $assessment->academic_session_id,
                    'complete_score' => $assessment->complete_score,
                    'term_id' => $assessment->term_id,
                ]);
            }

            // Return a success response
            return response()->json(['message' => 'Score saved successfully', 'score' => $score], 200);
        } catch (\Exception $e) {
            // Return an error response if any exception occurs
            return response()->json(['error' => 'Failed to save score: ' . $e->getMessage()], 500);
        }
    }

    public function saveGradeExam(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'user_id' => 'required|integer',
                'exam_id' => 'required|integer',
                'score' => 'required|integer|min:0|max:999',
                'course_id' => 'required|integer',
            ]);

            // Extract data from the request
            $userId = $request->input('user_id');
            $examId = $request->input('exam_id');
            $score = $request->input('score');
            $courseId = $request->input('course_id');

            // Retrieve the exam
            $exam = Exam::findOrFail($examId);

            // Ensure the score does not exceed the complete_score
            if ($score > $exam->complete_score) {
                return response()->json(['error' => 'Score cannot be greater than the complete score'], 422);
            }

            // Update the exam's academic session and term if null
            if (is_null($exam->academic_session_id) || is_null($exam->term_id)) {
                $exam->academic_session_id = auth()->user()->school->academicSession->id;
                $exam->term_id = auth()->user()->school->term->id;
                $exam->save();
            }

            // Check if the grade already exists for this user and exam
            $existingGrade = Grade::where('user_id', $userId)
                ->where('exam_id', $examId)
                ->first();

            if ($existingGrade) {
                // Update the existing grade
                $existingGrade->score = $score;
                $existingGrade->save();
            } else {
                // Create a new grade
                Grade::create([
                    'user_id' => $userId,
                    'exam_id' => $examId,
                    'course_id' => $courseId,
                    'score' => $score,
                    'academic_session_id' => $exam->academic_session_id,
                    'complete_score' => $exam->complete_score,
                    'term_id' => $exam->term_id,
                ]);
            }

            // Return a success response
            return response()->json(['message' => 'Score saved successfully', 'score' => $score], 200);
            } catch (\Exception $e) {
                // Return an error response if any exception occurs
                return response()->json(['error' => 'Failed to save score: ' . $e->getMessage()], 500);
            }
        }
    

    
    public function editAssessment(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'description',
                'course_id',
                'due_date',
                'complete_score',
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = \Validator::make($validatedData, [
                'due_date' => 'required|date',
                'name' => 'required|string|max:255',
                'course_id' => 'required|integer',
                'description' => 'nullable|string',
                'complete_score' => 'required|numeric'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the assignment by ID
            $assessment = Assessment::findOrFail($id);
    
            // Update the assignment with the validated data
            $assessment->update($validatedData);
    
            // Return the updated assignment as JSON response
            return response()->json(['message'=>"Assessment Updated Successfully",'assignment'=>$assessment], 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the assessment.' . $e->getMessage()], 500);
        }
    }

    public function deleteAssessment($assessmentId)
    {
        try {
            $assessment = Assessment::findOrFail($assessmentId);

            // Check if there are any grades associated with this assignment
            $existingGrades = Grade::where('assessment_id', $assessmentId)->exists();

            // If there are existing grades, return an error response
            if ($existingGrades) {
                return response()->json(['error' => 'This assessment cannot be deleted because it contains grades.'], 403);
            }

            // If there are no existing grades, proceed with deletion
            // Check if the authenticated user has permission to delete the assignment
            $authenticatedUser = auth()->user();
            $isSchoolOwner = $authenticatedUser->ownedSchools()->where('id', $assessment->school_id)->exists();

                
            $hasPermission = $authenticatedUser->profile && $authenticatedUser->school_id == $assessment->school_id &&
            $authenticatedUser->profile->teacher_confirmed ;

            if (!$isSchoolOwner && !$hasPermission) {
                return response()->json(['error' => 'You do not have permission to remove this assignment.'], 403);
            }

            // Use a transaction to ensure data consistency
            DB::beginTransaction();

            try {
                // Delete the assignment
                $assessment->delete();

                // Commit the transaction
                DB::commit();

                return response()->json(['message' => 'Assignment deleted successfully']);
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollBack();
                \Log::error($e);

                return response()->json(['error' => 'Failed to delete assignment. Please try again.' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(['error' => 'Failed to find the assignment. Please try again.'], 404);
        }
    }
    public function createAssessment(Request $request)
    {
        try {
            // Validate the form data
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'course_id' => 'required|integer',
                'due_date' => 'required|date',
                'complete_score'=> 'required|numeric',
                'class_section_id' => 'required|integer', // Assuming class_section_id is required
            ]);
    
            // Retrieve the authenticated user and associated school
            $user = auth()->user();
            $school = $user->school;
    
            // Check if the school and its academic session are available
            if ($school && $school->academicSession) {
                // Check if the term belongs to the academic session
                if ($school->term->academic_session_id !== $school->academicSession->id) {
                    // Return a message to inform the teacher to contact their school owner to update the school term
                    return response()->json(['error' => 'Please contact your school owner to update the school term'], 400);
                }
    
                // Create a new assessment instance
                $assessment = new Assessment();
                $assessment->name = $request->name;
                $assessment->description = $request->description;
                $assessment->course_id = $request->course_id;
                $assessment->due_date = $request->due_date;
                $assessment->complete_score = $request->complete_score;
                $assessment->teacher_id = $user->id;
                $assessment->school_id = $school->id;
                $assessment->class_section_id = $request->class_section_id;
    
                // Associate the assessment with the academic session and term of the school
                $assessment->academic_session_id = $school->academicSession->id;
                $assessment->term_id = $school->term->id;
    
                // Save the assessment
                $assessment->save();
    
                // Return success response
                return response()->json(['message' => 'Assessment created successfully'], 201);
            } else {
                // Return an error response if school or its academic session is not available
                return response()->json(['error' => 'School or academic session not found'], 404);
            }
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json(['error' => 'Failed to create assessment: ' . $e->getMessage()], 500);
        }
    }
    
    public function createExam(Request $request)
    {
        try {
            // Validate the form data
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'course_id' => 'required|integer',
                'due_date' => 'required|date',
                'complete_score'=> 'required|numeric',
                'class_section_id' => 'required|integer', // Assuming class_section_id is required
            ]);
    
            // Retrieve the authenticated user and associated school
            $user = auth()->user();
            $school = $user->school;
    
            // Check if the school and its academic session are available
            if ($school && $school->academicSession) {
                // Check if the term belongs to the academic session
                if ($school->term->academic_session_id !== $school->academicSession->id) {
                    // Return a message to inform the teacher to contact their school owner to update the school term
                    return response()->json(['error' => 'Please contact your school owner to update the school term'], 400);
                }
    
                // Create a new exam instance
                $exam = new Exam();
                $exam->name = $request->name;
                $exam->description = $request->description;
                $exam->course_id = $request->course_id;
                $exam->due_date = $request->due_date;
                $exam->complete_score = $request->complete_score;
                $exam->teacher_id = $user->id;
                $exam->school_id = $school->id;
                $exam->class_section_id = $request->class_section_id;
    
                // Associate the exam with the academic session and term of the school
                $exam->academic_session_id = $school->academicSession->id;
                $exam->term_id = $school->term->id;
    
                // Save the exam
                $exam->save();
    
                // Return success response
                return response()->json(['message' => 'Exam created successfully'], 201);
            } else {
                // Return an error response if school or its academic session is not available
                return response()->json(['error' => 'School or academic session not found'], 404);
            }
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json(['error' => 'Failed to create exam: ' . $e->getMessage()], 500);
        }
    }
    
    
    public function editExam(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'description',
                'course_id',
                'due_date',
                'complete_score'
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = \Validator::make($validatedData, [
                'due_date' => 'required|date',
                'name' => 'required|string|max:255',
                'course_id' => 'required|integer',
                'description' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the exam by ID
            $exam = Exam::findOrFail($id);
    
            // Check if the exam's academic session is null
            if (is_null($exam->academicSession)) {
                // Fetch the school's academic session
                $schoolAcademicSession = $exam->course->school->academicSession;
    
                // Assign the school's academic session to the exam
                $validatedData['academic_session_id'] = $schoolAcademicSession->id;
            }
    
            // Check if the exam's term is null
            if (is_null($exam->term)) {
                // Fetch the school's current term
                $schoolCurrentTerm = $exam->course->school->term;
    
                // Assign the school's current term to the exam
                $validatedData['term_id'] = $schoolCurrentTerm->id;
            }
    
            // Update the exam with the validated data
            $exam->update($validatedData);
    
            // Return the updated exam as a JSON response
            return response()->json(['message' => "Exam Updated Successfully", 'exam' => $exam], 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the exam.' . $e->getMessage()], 500);
        }
    }
    

    public function deleteExam($examId)
    {
        try {
            $exam = Exam::findOrFail($examId);

            // Check if there are any grades associated with this assignment
            $existingGrades = Grade::where('exam_id', $examId)->exists();

            // If there are existing grades, return an error response
            if ($existingGrades) {
                return response()->json(['error' => 'This exam cannot be deleted because it contains grades.'], 403);
            }

            // If there are no existing grades, proceed with deletion
            // Check if the authenticated user has permission to delete the assignment
            $authenticatedUser = auth()->user();
            $isSchoolOwner = $authenticatedUser->ownedSchools()->where('id', $exam->school_id)->exists();

                
            $hasPermission = $authenticatedUser->profile && $authenticatedUser->school_id == $exam->school_id &&
            $authenticatedUser->profile->teacher_confirmed ;

            if (!$isSchoolOwner && !$hasPermission) {
                return response()->json(['error' => 'You do not have permission to remove this assignment.'], 403);
            }

            // Use a transaction to ensure data consistency
            DB::beginTransaction();

            try {
                // Delete the assignment
                $exam->delete();

                // Commit the transaction
                DB::commit();

                return response()->json(['message' => 'Exam deleted successfully']);
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollBack();
                \Log::error($e);

                return response()->json(['error' => 'Failed to delete Exam. Please try again.' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(['error' => 'Failed to find the Exam. Please try again.'], 404);
        }
    }
 
    
    public function archive($modelName, $id)
    {
        try {
            // Retrieve the instance of the model based on the provided model name
            switch ($modelName) {
                case 'Assignment':
                    $instance = Assignment::findOrFail($id);
                    break;
                case 'Assessment':
                    $instance = Assessment::findOrFail($id);
                    break;
                case 'Exam':
                    $instance = Exam::findOrFail($id);
                    break;
                default:
                    return response()->json(['error' => 'Invalid model name'], 400);
            }
    
            // Get the associated class section, course, and teacher
            $classSection = $instance->classSection;
            $course = $instance->course;
            $teacher = $course->getTeacherForClassSection($classSection->id);
    
            // Check if the authenticated user is the teacher of the course for the class
            if ($teacher->id != auth()->id()) {
                return response()->json(['error' => "You cannot archive this $modelName"], 403);
            }
            
            // Archive the instance by setting the archived flag to true
            $instance->archived = true;
            $instance->save();
    
            return response()->json(['message' => "$modelName archived successfully"], 200);
        } catch (ModelNotFoundException $exception) {
            // Handle model not found exception
            return response()->json(['error' => 'The requested resource was not found'], 404);
        } catch (\Exception $exception) {
            // Handle other exceptions
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function toggleAssignmentStatus($modelName, $id)
    {
        try {
            // Retrieve the instance of the model based on the provided model name
            switch ($modelName) {
                case 'Assignment':
                    $instance = Assignment::findOrFail($id);
                    break;
                case 'Assessment':
                    $instance = Assessment::findOrFail($id);
                    break;
                case 'Exam':
                    $instance = Exam::findOrFail($id);
                    break;
                default:
                    return response()->json(['error' => 'Invalid model name'], 400);
            }

            // Get the associated class section, course, and teacher
            $classSection = $instance->classSection;
            $course = $instance->course;
            $teacher = $course->getTeacherForClassSection($classSection->id);

            // Check if the authenticated user is the teacher of the course for the class
            if ($teacher->id != auth()->id()) {
                return response()->json(['error' => "You are not authorized to perform this action"], 403);
            }

            // Toggle the use_in_final_result attribute
            $instance->use_in_final_result = !$instance->use_in_final_result;
            $instance->save();

            return response()->json(['message' => "$modelName status changed successfully"], 200);
        } catch (ModelNotFoundException $e) {
            // Handle model not found exception
            return response()->json(['error' => 'The requested resource was not found'], 404);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }

    public static function calculatePercentage(float $score, float $completeScore): float
    {
        if ($completeScore <= 0) {
            return 0; // Avoid division by zero
        }

        return ($score / $completeScore) * 100;
    }
    public static function calculateGrade(float $percentage): string
    {
        if ($percentage >= 80) {
            return 'A+';
        } elseif ($percentage >= 70) {
            return 'A';
        } elseif ($percentage >= 60) {
            return 'B';
        } elseif ($percentage >= 50) {
            return 'C';
        } elseif ($percentage >= 40) {
            return 'D';
        } elseif ($percentage >= 30) {
            return 'E';
        } else {
            return 'F';
        }
    }
    public function compileResults(Request $request, $studentId)
    {
        // Retrieve the academic session ID and term ID from the request
        $academicSessionId = $request->input('academic_session_id');
        $termId = $request->input('term_id');

        // Retrieve the student
        $student = User::findOrFail($studentId);
        $academic_session = AcademicSession::findOrFail($academicSessionId);
        $term = Term::findOrFail($termId);
        $netScore = 0;

        // Check if the student exists
        if (!$student instanceof User) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Clear the existing compiled results from the session
        session()->forget('compiledResults');

        // Retrieve the student's courses for the academic session and term with eager loading
        $studentCourses = $student->student_courses()
            ->with(['assignments', 'assessments', 'exams'])
            ->get();

        // Initialize an array to store compiled results
        $compiledResults = [];
        
        // Extract student information
        $studentInfo = [
            'student_id' => $student->id,
            'student_name' => $student->profile->full_name,
            'class_section' => $student->userClassSection->name,
            'class_id' => $student->schoolClass()->id,
            'class_name' => $student->schoolClass()->code,
            'class_section_name' => $student->userClassSection->code,
            'class_section_id' => $student->userClassSection->id,
            'academic_session' => $academic_session->name,
            'academic_session_id' => $academic_session->id,
            'term_id'=>$term->id,
            'term' => $term->name,
        ];

        // Add student information to the compiled results array
        $compiledResults['student_info'] = $studentInfo;

        // Iterate through each course
        foreach ($studentCourses as $course) {
            // Initialize variables to store scores and counts for the course
            $totalAssignmentScore = 0;
            $totalAssignmentCompleteScore = 0;
            $totalAssessmentScore = 0;
            $totalAssessmentCompleteScore = 0;
            $totalExamScore = 0;
            $assignmentGradeCounts = 0;
            $assessmentGradeCounts = 0;
            $examGradeCounts = 0;
            $examCount = $course->exams->where('user_id', $studentId)->count();
            $totalExamCompleteScore = 0;

            // Process assignments
            foreach ($course->assignments as $assignment) {
                $assignmentGrade = $assignment->grades()
                    ->where('user_id', $studentId)
                    ->where('academic_session_id', $academicSessionId)
                    ->where('term_id', $termId)
                    ->first();

                if ($assignmentGrade) {
                    $totalAssignmentScore += $assignmentGrade->score;
                    $totalAssignmentCompleteScore += $assignment->complete_score;
                    $assignmentGradeCounts++;
                }
            }
            $averageAssignmentCompleteScore = ($totalAssignmentCompleteScore > 0) ? ($totalAssignmentCompleteScore / $course->assignments->count()) : 0;
            $netAssignmentScore = ($totalAssignmentCompleteScore > 0) ? ($totalAssignmentScore / $assignmentGradeCounts) : 0;

            // Process assessments
            foreach ($course->assessments as $assessment) {
                $assessmentGrade = $assessment->grades()
                    ->where('user_id', $studentId)
                    ->where('academic_session_id', $academicSessionId)
                    ->where('term_id', $termId)
                    ->first();

                if ($assessmentGrade) {
                    $totalAssessmentScore += $assessmentGrade->score;
                    $totalAssessmentCompleteScore += $assessment->complete_score;
                    $assessmentGradeCounts++;
                }
            }
            $averageAssessmentCompleteScore = ($totalAssessmentCompleteScore > 0) ? ($totalAssessmentCompleteScore / $course->assessments->count()) : 0;
            $netAssessmentScore = ($totalAssessmentCompleteScore > 0) ? ($totalAssessmentScore / $assessmentGradeCounts) : 0;

            // Process exams
            foreach ($course->exams as $exam) {
                $examGrade = $exam->grades()
                    ->where('user_id', $studentId)
                    ->where('academic_session_id', $academicSessionId)
                    ->where('term_id', $termId)
                    ->first();

                if ($examGrade) {
                    $totalExamScore += $examGrade->score;
                    $totalExamCompleteScore += $exam->complete_score;
                    $examGradeCounts++;
                }
            }
            $averageExamCompleteScore = ($totalExamCompleteScore > 0) ? ($totalExamCompleteScore / $course->exams->count()) : 0;
            $netExamScore = ($totalExamCompleteScore > 0) ? ($totalExamScore / $examGradeCounts) : 0;

            $totalScore = $netAssignmentScore + $netAssessmentScore + $netExamScore;
            $grade = $this->calculateGrade($totalScore); // Adjusting for the grade calculation function
            $netScore += $totalScore;

            // Store compiled results for the course
            $compiledResults[] = [
                'course_name' => $course->name,
                'total_score' => $totalScore,
                'grade' => $grade,
                'exam_count' => $examCount,
                'net_assignment_score' => $netAssignmentScore,
                'net_assessment_score' => $netAssessmentScore,
                'net_exam_score' => $netExamScore,
            ];
        }
        // dd($compiledResults);

        // Store the compiled results in the session
        session()->put('compiledResults', $compiledResults);

        // Redirect to the result page
        return response()->json(['success'=>"Result Compiled Successfully", 'link'=>'/result_page']);
    }

    public function publishResult(Request $request)
    {
        // Retrieve the compiled results from the session
        $compiledResults = session()->get('compiledResults');
        // dd($compiledResults);
        $total_scores_sum = 0;
        $count = 0;

        // Iterate through each element in $compiledResults
        foreach ($compiledResults as $key => $value) {
            // Check if $value is an array and contains the necessary keys
            if (is_array($value) && isset($value['course_name'], $value['net_assignment_score'], $value['net_assessment_score'], $value['net_exam_score'], $value['total_score'], $value['grade'])) {
                // Check if net_exam_score is greater than zero
                if ($value['net_exam_score'] > 0) {
                    // Add total score to the sum and increment the count
                    $total_scores_sum += $value['total_score'];
                    $count++;
                }

                // Find existing student result or create a new one
                $result = StudentResult::updateOrCreate([
                    'student_id' => $compiledResults['student_info']['student_id'],
                    'academic_session_id' => $compiledResults['student_info']['academic_session_id'],
                    'term_id' => $compiledResults['student_info']['term_id'],
                    'school_id' => auth()->user()->school->id,
                    'class_section_id' => $compiledResults['student_info']['class_section_id'],
                    'class_id' => $compiledResults['student_info']['class_id'],
                    'course_name' => $value['course_name'],
                ], [
                    'form_teacher_id' => auth()->id(),
                    'assignment_score' => $value['net_assignment_score'],
                    'assessment_score' => $value['net_assessment_score'],
                    'exam_score' => $value['net_exam_score'],
                    'total_score' => $value['total_score'],
                    'grade' => $value['grade'],
                ]);
            }
        }

        // Calculate total average only if there are courses with net_exam_score > 0
        $total_average = $count > 0 ? $total_scores_sum / $count : 0;
        // dd($total_average);

        // Update or create the comment outside the loop
        foreach ($compiledResults as $key => $value) {
            if (is_array($value)) {
                $result->comments()->updateOrCreate(
                    [
                        'form_teacher_id' => auth()->id(),
                        'student_id' => $compiledResults['student_info']['student_id'],
                        'academic_session_id' => $compiledResults['student_info']['academic_session_id'],
                        'term_id' => $compiledResults['student_info']['term_id'],
                        'student_result_id' => $result->id, // Assuming the relationship between StudentResult and StudentResultComment is defined
                    ],
                    [
                        'class_id' => $compiledResults['student_info']['class_id'],
                        'class_section_id' => $compiledResults['student_info']['class_section_id'],
                        'total_average_score' => $total_average,
                        'academic_session_id' => $compiledResults['student_info']['academic_session_id'],
                        'term_id' => $compiledResults['student_info']['term_id'],
                        'comment' => $request->input('comment'),
                    ]
                );
                break; // Exit loop after updating comments for the first student result
            }
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Result published successfully.');
    }

    
    public function showResults(Request $request)
    {
        $school = auth()->user()->school;
        // Retrieve the compiled results from the session
        $compiledResults = session()->get('compiledResults');
        // dd($compiledResults);
    
        // Check if compiledResults is null or not an array
        if ($compiledResults === null || !is_array($compiledResults)) {
            return response()->json(['error' => 'Invalid compiled results'], 400);
        }
    
        // Pass the compiled results to the view
        return view('teacher.results_page', ['compiledResults' => $compiledResults, 'school'=>$school]);
    }
    
   /**
     * Store a newly created promotion criteria in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePromotionCriteria(Request $request)
    {
        // Validate form data
        $validatedData = $request->validate([
            'requiredAvgScore' => 'required|numeric',
            'totalAttendancePercentage' => 'required|numeric',
            'compulsoryCoursesAvgScore' => 'required|numeric',
            'academicSessionId' => 'required|exists:academic_sessions,id',
            'class_section_id' => 'required|exists:school_class_sections,id',
        ]);
    
        // Create a new PromotionCriteria instance
        $promotionCriteria = new PromotionCriteria([
            'required_avg_score' => $validatedData['requiredAvgScore'],
            'total_attendance_percentage' => $validatedData['totalAttendancePercentage'],
            'compulsory_courses_avg_score' => $validatedData['compulsoryCoursesAvgScore'],
            'academic_session_id' => $validatedData['academicSessionId'],
            'school_class_section_id' => $validatedData['class_section_id'],
        ]);
    
        // Save the promotion criteria to the database
        $promotionCriteria->save();
    
        // Return a success response
        return response()->json(['message' => 'Promotion criteria saved successfully']);
    }
    
    public function fetchCriteriaValues(Request $request)
    {
        $academicSessionId = $request->input('academic_session_id');
        $classSectionId = $request->input('class_section_id');

        // Fetch the promotion criteria based on academic session ID and class section ID
        $promotionCriteria = PromotionCriteria::where('academic_session_id', $academicSessionId)
            ->where('school_class_section_id', $classSectionId)
            ->first();

        // Prepare the response data
        $responseData = [
            'requiredAvgScore' => $promotionCriteria->required_avg_score,
            'totalAttendancePercentage' => $promotionCriteria->total_attendance_percentage,
            'compulsoryCoursesAvgScore' => $promotionCriteria->compulsory_courses_avg_score,
        ];

        return response()->json($responseData);
    }

    public function updatePromotionCriteria(Request $request)
    {
        // Validate the request data as needed
        $validatedData = $request->validate([
            'academicSessionId' => 'required|integer', // Assuming academicSessionId is required and should be an integer
            'class_section_id' => 'required|integer', // Assuming class_section_id is required and should be an integer
            'requiredAvgScore' => 'nullable|numeric', // Assuming requiredAvgScore is optional and should be numeric
            'totalAttendancePercentage' => 'nullable|numeric', // Assuming totalAttendancePercentage is optional and should be numeric
            'compulsoryCoursesAvgScore' => 'nullable|numeric', // Assuming compulsoryCoursesAvgScore is optional and should be numeric
        ]);

        // Retrieve the promotion criteria record based on academic session ID and class section ID
        $promotionCriteria = PromotionCriteria::where('academic_session_id', $validatedData['academicSessionId'])
            ->where('school_class_section_id', $validatedData['class_section_id'])
            ->first();

        if (!$promotionCriteria) {
            // If the promotion criteria record doesn't exist, you can choose to create it here or return an error
            return response()->json(['error' => 'Promotion criteria not found'], 404);
        }

        // Update the promotion criteria fields if they are provided in the request
        if (isset($validatedData['requiredAvgScore'])) {
            $promotionCriteria->required_avg_score = $validatedData['requiredAvgScore'];
        }
        if (isset($validatedData['totalAttendancePercentage'])) {
            $promotionCriteria->total_attendance_percentage = $validatedData['totalAttendancePercentage'];
        }
        if (isset($validatedData['compulsoryCoursesAvgScore'])) {
            $promotionCriteria->compulsory_courses_avg_score = $validatedData['compulsoryCoursesAvgScore'];
        }

        // Save the updated promotion criteria
        $promotionCriteria->save();

        // Return a success response
        return response()->json(['message' => 'Promotion criteria updated successfully'], 200);
    }
    public function promoteStudents(Request $request)
    {
        try {
            $academic_session_id = $request->academic_session_id;
            $class_section_id = $request->class_section_id;
            $nextClassId = $request->next_class_id;

            // Retrieve class section and next class
            $class_section = SchoolClassSection::findOrFail($class_section_id);
            $nextClass = SchoolClass::findOrFail($nextClassId);

            // Retrieve promotion criteria
            $promotionCriteria = $class_section->promotionCriteria()
                ->where('academic_session_id', $academic_session_id)
                ->first();

            if (!$promotionCriteria) {
                throw new \Exception('Promotion criteria not found for the specified academic session and class section.', 404);
            }

            // Get all students in the class section
            $students = $class_section->students;
            $compulsory_courses = $class_section->courses->where('compulsory', true)->pluck('name')->toArray();

            $promotedCount = 0;
            $notPromotedCount = 0;

            foreach ($students as $student) {
                // Calculate average total score
                $averageTotalScore = $this->calculateAverageTotalScore($student, $academic_session_id);

                // Calculate attendance percentage
                $attendancePercentage = $this->calculateAttendancePercentage($student, $academic_session_id);

                // Check if the student meets the promotion criteria for total average score and attendance
                if ($averageTotalScore >= $promotionCriteria->required_avg_score &&
                    $attendancePercentage >= $promotionCriteria->total_attendance_percentage) {

                    // Check compulsory courses
                    $passesCompulsoryCourses = true;
                    foreach ($compulsory_courses as $course_name) {
                        $courseScore = $this->getScoreForCourse($student, $academic_session_id, $course_name);
                        if ($courseScore < $promotionCriteria->compulsory_courses_avg_score) {
                            $passesCompulsoryCourses = false;
                            break;
                        }
                    }

                    if ($passesCompulsoryCourses) {
                        // Promote the student

                        $student->userClassSection()->dissociate();
                        $student->classSection()->detach($class_section_id);
                        $student->class_section_id = null;
                        $student->save();
                        $student->profile->update(['class_id' => $nextClassId]);
                        $promotedCount++;

                        // Queue email to student and guardians
                        SendPromotionEmail::dispatch($student, true);

                    } else {
                        $notPromotedCount++;

                        // Queue email to student and guardians
                        SendPromotionEmail::dispatch($student, false);
                    }
                } else {
                    $notPromotedCount++;

                    // Queue email to student and guardians
                    SendPromotionEmail::dispatch($student, false);
                }
            }

            return response()->json([
                'message' => "$promotedCount Students are Promoted and $notPromotedCount Students are not Promoted",
                'promoted_count' => $promotedCount,
                'not_promoted_count' => $notPromotedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    private function calculateAverageTotalScore($student, $academic_session_id)
    {
        // Get all results of the student for the academic session
        $results = $student->studentResults()->where('academic_session_id', $academic_session_id)->get();
        
        // Calculate total score
        $totalScore = $results->sum('score');
        
        // Calculate average total score
        $averageTotalScore = $totalScore / $results->count();
        
        return $averageTotalScore;
    }
    
    private function getScoreForCourse($student, $academic_session_id, $course_name)
    {
        // Retrieve the student's results for the specified academic session and course
        $result = $student->studentResults()
                          ->where('academic_session_id', $academic_session_id)
                          ->where('course_name', $course_name)
                          ->first();
    
        // Check if the result exists
        if ($result) {
            // Return the score for the course
            return $result->total_score;
        } else {
            // If no result found, return 0 or handle accordingly based on your application logic
            return 0;
        }
    }
    
    private function calculateAttendancePercentage($student, $academic_session_id)
    {
        // Retrieve all attendance records for the student within the specified academic session
        $attendanceRecords = Attendance::where('user_id', $student->id)
            ->where('academic_session_id', $academic_session_id)
            ->get();

        // Count the total number of attendance records for the student
        $totalRecords = $attendanceRecords->count();

        // Count the number of days the student was present
        $presentRecords = $attendanceRecords->where('attendance', true)->count();

        // Calculate the attendance percentage
        if ($totalRecords > 0) {
            return ($presentRecords / $totalRecords) * 100;
        }

        return null; // Return null if there are no attendance records
    }
    public function getNextClass(Request $request)
    {
        $classSectionId = $request->class_section_id;
        
        // Get the current class section
        $currentClassSection = SchoolClassSection::find($classSectionId);
        
        // Assuming you have a method in your model to retrieve the next class
        $classNextClassPair = [
            'primary_one' => 'primary_two',
            'primary_two' => 'primary_three',
            'primary_three' => 'primary_four',
            'primary_four' => 'primary_five',
            'primary_five' => 'primary_six',
            'primary_six' => 'jss_one',
            'jss_one' => 'jss_two',
            'jss_two' => 'jss_three',
            'jss_three' => 'sss_one',
            'sss_one' => 'sss_two',
            'sss_two' => 'sss_three',
        ];
        
        // Get the next class level based on the current class level
        $nextClassLevel = $classNextClassPair[$currentClassSection->schoolClass->class_level] ?? null;
        
        // Find the next class based on the next class level
        if ($nextClassLevel) {
            $nextClass = SchoolClass::where('class_level', $nextClassLevel)->first();
            
            if ($nextClass) {
                return response()->json([
                    'next_class' => $nextClass->name . ' ('. $nextClass->code . ')',
                    'next_class_id' => $nextClass->id
                ]);
            } else {
                return response()->json([
                    'error' => 'Next class not found'
                ], 404);
            }
        } else {
            return response()->json([
                'error' => 'Next class level not defined'
            ], 404);
        }
    }
    


}
