<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\SchoolClassSection;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
        $this->middleware('verifyAdmin'); // Custom middleware to check admin confirmation and profile
    }
    public function showStudents($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $potentialStudentsCount = $school->potentialStudents()->count();
        $studentsByClass = [];
    
        foreach ($school->classes as $class) {
            $studentsByClass[$class->name] = $class->students();
        }
    
        return view('school.show_students', compact('school', 'studentsByClass', 'potentialStudentsCount'));
    }
    

    
    public function showClass($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $classCount = $school->classes()->count();
        // dd($classCount);

        return view('school.show_classes', compact('school', 'classCount'));
    }

    public function confirmAndMakeStudent(Request $request, User $user)
    {
        try {
            // return response()->json(['message' => "$user->profile->full_name is now a student"]);
            // Validate the request data
            $request->validate([
                'school_id' => 'required|exists:schools,id',
            ]);

            // Ensure that the authenticated user is the school owner
            $schoolId = $request->input('school_id');
            $authenticatedUserId = auth()->id();
            
            // Ensure that the provided user belongs to the specified school
            if ($user->school_id != $schoolId) {
                return response()->json(['error' => "This user does not belong to the specified school."], 400);
            }

            // Check if the number of admins has exceeded the maximum allowed by the school's package
            $school = School::find($schoolId);
            $maxStudents = $school->schoolPackage->max_students;

            if ($school->confirmedStudents()->count() >= $maxStudents) {
                return response()->json(['error' => 'The maximum number of admins for this school has been reached.'], 400);
            } 

            // Update the user's profile to make them an admin
            $user->profile->student_confirmed = true;
            $user->profile->save();

            // Fetch the updated user with the profile
            $updatedUser = User::with('profile')->find($user->id);
            $profilePicturePath = $user->profile->profile_picture;
            $fullProfilePictureUrl = $profilePicturePath
                ? asset('storage/' . $profilePicturePath)
                : asset('dist/img/avatar5.png');
            
            return response()->json(['message' => "$user->profile->full_name is now a student", 'newAdmin' => $user, 'profile_picture_url' => $fullProfilePictureUrl]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to make the user an admin. Please try again.'. $e->getMessage()], 500);
        }
    }


    public function createClass(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'code' => 'required|string',
            'class_level' => 'required|string',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_id'=>'required|numeric'
        ]);
    
        $class = SchoolClass::create($validatedData);

        /// Save the uploaded image to the storage folder
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');

            // Generate a unique filename for the file
            $filename = 'school_class_picture' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('school_class_picture', $filename, 'public');

            // Resize the image to 500x500
            $resizedImage = Image::make(storage_path('app/public/' . $imagePath))->fit(500, 500);
            $resizedImage->save(storage_path('app/public/' . $imagePath));

            $class->picture = $imagePath;
            $class->save();
        }


        // Return the created package as JSON response with picture_url
        return response()->json([
            'name' => $class->name,
            'created_at' => $class->created_at,
            'picture_url' => asset('storage/' . $class->picture), 
            'description' => $class->description,
        ], 201);
    }
    
    public function addClassSection(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'section_name' => 'required|string',
            'description' => 'nullable|string',
            'section_code' => 'required|string',
            'main_form_teacher' => 'nullable|numeric',
            'class_id' => 'required|numeric'
        ]);
    
        // Rename the keys to match the names in your model
        $validatedData['name'] = $validatedData['section_name'];
        $validatedData['code'] = $validatedData['section_code'];
        $validatedData['main_form_teacher_id'] = $validatedData['main_form_teacher'];
        unset($validatedData['section_name']);
        unset($validatedData['section_code']);
        unset($validatedData['main_form_teacher']);
    
        // Create the class section
        $classSection = SchoolClassSection::create($validatedData);
    
        // Attach the form teacher to the class section in the pivot table
        if ($request->has('main_form_teacher')) {
            $classSection->formTeachers()->attach($validatedData['main_form_teacher_id']);
        }
    
        // Return the created package as a JSON response with picture_url
        return response()->json([
            'name' => $classSection->name,
            'code' => asset('storage/' . $classSection->picture),
            'description' => $classSection->description,
        ], 201);
    }
    
    public function editClass(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'code',
                'class_level',
                'description',
                'picture',
               
                
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = \Validator::make($validatedData, [
                'name' => 'nullable|string',
                'code'=> 'nullable|string',
                'class_level'=> 'required|string',
                'description' => 'nullable|string',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
                
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the package by ID
            $class = SchoolClass::findOrFail($id);
    
            // Update the package with the validated data
            $class->fill($validatedData);
    
            // Save the changes to the database
            $class->save();
    
            // Handle picture update separately
            if ($request->hasFile('picture')) {
                // Delete the old picture from the server
                Storage::disk('public')->delete($class->picture);

                // Upload the new picture
                $file = $request->file('picture');
                $filename = 'user_class_picture' . time() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('user_class_picture', $filename, 'public');

                // Resize the image to 500x500
                $resizedImage = Image::make(storage_path('app/public/' . $imagePath))->fit(500, 500);
                $resizedImage->save(storage_path('app/public/' . $imagePath));

                $class->picture = $imagePath;
                $class->save();
            }

    
            // Return the updated package as JSON response
            return response()->json($class, 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the Class.' . $e->getMessage()], 500);
        }
    }
    

    public function deleteClass($id)
    {
        try {
            // Find the package by ID
            $class = SchoolClass::findOrFail($id);

            // Delete the package picture from storage
            Storage::disk('public')->delete($class->picture);

            // Delete the package from the database
            $class->delete();

            // Return a success response
            return response()->json(['message' => 'Class deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to delete the class.'], 500);
        }
    }

    public function editSection(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'code',
                'description',
                'main_form_teacher',
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = \Validator::make($validatedData, [
                'name' => 'required|string',
                'code' => 'required|string',
                'description' => 'nullable|string',
                'main_form_teacher' => 'nullable|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the class section by ID
            $classSection = SchoolClassSection::findOrFail($id);
    
            // Update the class section with the validated data
            $classSection->update($validatedData);
    
            // If main_form_teacher is provided in the request, update the main_form_teacher_id
            if ($request->has('main_form_teacher')) {
                $mainFormTeacherId = $request->input('main_form_teacher');
                $classSection->main_form_teacher_id = $mainFormTeacherId;
                $classSection->save();
            }
    
            // If main_form_teacher is not provided in the request, detach any existing form teacher
            // if (!$request->has('main_form_teacher')) {
            //     $classSection->formTeachers()->detach();
            // }
    
            // Return the updated class section as a JSON response
            return response()->json($classSection, 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the Class Section.' . $e->getMessage()], 500);
        }
    }
    
    public function deleteSection($id)
    {
        try {
            // Find the class section by ID
            $classSection = SchoolClassSection::findOrFail($id);

            // Detach form teachers from the pivot table
            $classSection->formTeachers()->detach();

            // Detach students from the pivot table
            $classSection->students()->detach();

            // Delete the class section
            $classSection->delete();

            return response()->json(['message' => 'Class Section deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(['error' => 'Failed to delete the Class Section.'], 500);
        }
    }

    public function viewSection($sectionId)
    {
        // Fetch the details of the section using the ID
        $section = SchoolClassSection::findOrFail($sectionId);
        // dd($section->schoolClass->school->name);

        // Pass the section data to the view
        return view('school.show_sections', ['section' => $section]);
    }
    public function confirmAndAddStudentToSection(Request $request, User $user)
    {
        try {
            // Validate the request data
            $request->validate([
                'section_id' => 'required|exists:school_class_sections,id',
            ]);
    
            // Fetch the authenticated user and the specified section
            $authenticatedUser = auth()->user();
            $sectionId = $request->input('section_id');
            $section = SchoolClassSection::findOrFail($sectionId);
            $schoolId = $section->schoolClass->school->id;
    
            // Check if the authenticated user is a school owner
            $isSchoolOwner = $authenticatedUser->ownedSchools()
                ->where('id', $schoolId)
                ->exists();
    
            // Check if the authenticated user has the necessary permissions or is the school owner
            $hasPermission = $authenticatedUser->profile &&
                $authenticatedUser->profile->permission_confirm_student &&
                $authenticatedUser->profile->permission_create_class;
    
            if (!$hasPermission && !$isSchoolOwner) {
                return response()->json(['error' => 'You do not have permission to confirm this student in the specified class section.'], 403);
            }
    
            // Ensure that the provided user belongs to the specified section
            if ($user->profile->class_id != $section->schoolClass->id) {
                return response()->json(['error' => "This user does not belong to the specified class section."], 400);
            }
    
            // Perform any additional checks or actions needed before confirming the student
    
            // Update the user's profile to confirm them as a student in the section
            $user->profile->class_confirmed = true;
            $user->profile->student_confirmed = true;
            $user->profile->save();


            $user->class_section_id = $sectionId;
            $user->save();
    
            // Attach the user to the class section in the pivot table
            $section->students()->attach($user->id);
    
            // Get compulsory courses for the class section
            $compulsoryCourses = $section->courses()->where('compulsory', true)->get();
    
            // Attach the student to compulsory courses
            foreach ($compulsoryCourses as $course) {
                $course->students()->attach($user->id);
            }
    
            return response()->json(['message' => 'User is now confirmed as a student in the class section']);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to confirm this student in the class section. Please try again.', 'message' => $e->getMessage()], 500);
        }
    }
    
   
    public function showTeachers($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $potentialTeachersCount = $school->potentialTeachers()->count();
        // dd($potentialTeachersCount);
    
        return view('school.show_teachers', compact('school', 'potentialTeachersCount'));
    }
    

    public function confirmAndMakeTeacher(Request $request, User $user)
    {
        try {
            // Validate the request data
            $request->validate([
                'school_id' => 'required|exists:schools,id',
            ]);

            // Ensure that the authenticated user is the school owner
            $schoolId = $request->input('school_id');
            $school = School::find($schoolId);
            $authenticatedUserId = auth()->id();
            $authenticatedUser = auth()->user();
            
            // Check if the authenticated user is a school owner
            $isSchoolOwner = $authenticatedUser->ownedSchools()
                ->where('id', $schoolId)
                ->exists();
    
            // Check if the authenticated user has the necessary permissions or is the school owner
            $hasPermission = $authenticatedUser->profile &&
                $authenticatedUser->profile->permission_confirm_student &&
                $authenticatedUser->profile->permission_create_class;
    
            if (!$hasPermission && !$isSchoolOwner) {
                return response()->json(['error' => 'You do not have permission to confirm this Teacher.'], 403);
            }
            $maxTeacher = $school->schoolPackage->max_teachers;
            $currentTeachersCount = $school->confirmedTeachers()->count();

            if ($currentTeachersCount >= $maxTeacher) {
                return response()->json(['error' => 'The maximum number of Teachers for this school package has been reached.'. $maxTeacher], 400);
            }
    
            // Ensure that the provided user belongs to the specified section
            if ($user->school_id != $school->id) {
                return response()->json(['error' => "This user does not belong to the specified class section."], 400);
            }
    
            // Perform any additional checks or actions needed before confirming the student
    
            // Update the user's profile to confirm them as a student in the section
            $user->profile->teacher_confirmed = true;
            // $user->profile->student_confirmed = true;
            $user->profile->save();
    

            
            return response()->json(['message' => 'User is now an admin', 'newAdmin' => $user]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to make the user an admin. Please try again.'.$e->getMessage()], 500);
        }
    }

    public function removeTeacher($teacherId)
    {
        try {
            $teacher = User::findOrFail($teacherId);
    
            // Check if the teacher is a form teacher for any class section
            $formTeacherSections = SchoolClassSection::whereHas('formTeachers', function ($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })->get();
    
            // Check if the teacher is the main form teacher for any class section
            $mainFormTeacherSections = SchoolClassSection::where('main_form_teacher_id', $teacherId)->get();
    
            // Remove admin permissions
            $teacher->profile->update([
                'permission_confirm_student' => false,
                'permission_confirm_admin' => false,
                'permission_confirm_teacher' => false,
                'permission_create_lesson' => false,
                'permission_create_course' => false,
                'permission_create_class' => false,
                'permission_create_event' => false,
                'permission_confirm_staff' => false,
            ]);
    
            // Set admin_confirmed to false
            // $teacher->profile->admin_confirmed = false;
            $teacher->profile->teacher_confirmed = false;
            $teacher->profile->save();
    
            // If the teacher is a form teacher, remove them from the class sections
            if ($formTeacherSections->count() > 0) {
                foreach ($formTeacherSections as $section) {
                    $section->formTeachers()->detach($teacherId);
                }
            }
    
            // If the teacher is the main form teacher, update main_form_teacher_id to null
            if ($mainFormTeacherSections->count() > 0) {
                foreach ($mainFormTeacherSections as $section) {
                    $section->update(['main_form_teacher_id' => null]);
                }
            }
    
            return response()->json(['message' => 'Teacher removed successfully']);
        } catch (\Exception $e) {
            \Log::error($e);
    
            return response()->json(['error' => 'Failed to remove Teacher. Please try again.'], 500);
        }
    }

    public function removeStudent($studentId)
    {
        try {
            $student = User::findOrFail($studentId);
    
            // Check if the authenticated user has permission_confirm_students and belongs to the same school
            $authenticatedUser = auth()->user();
            $isSchoolOwner = $authenticatedUser->ownedSchools()
                ->where('id', $student->school_id)
                ->exists();
            
            $hasPermission = $authenticatedUser->profile &&
                $authenticatedUser->profile->permission_confirm_student &&
                $authenticatedUser->profile->permission_create_class;
    
            if (!$hasPermission && !$isSchoolOwner) {
                return response()->json(['error' => 'You do not have permission to remove this student.'], 403);
            }
    
            // Use a transaction to ensure data consistency
            DB::beginTransaction();
    
            try {
                // Detach the student from all class sections
                $student->classSection()->detach();
    
                // Update profile to mark the student as not confirmed
                $student->profile->student_confirmed = false;
                $student->profile->class_confirmed = false;
                $student->profile->save();

                $student->class_section_id = null;
                $student->save();
    
                // Commit the transaction
                DB::commit();
    
                return response()->json(['message' => 'Student removed successfully']);
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollBack();
                \Log::error($e);
    
                return response()->json(['error' => 'Failed to remove Student. Please try again.' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            \Log::error($e);
    
            return response()->json(['error' => 'Failed to find the student. Please try again.'], 404);
        }
    }

    public function showCourses($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $uniqueSubjectNames = Course::getAllUniqueSubjects();
        $coursesCount = $school->courses()->count();
        // $studentsByClass = [];
    
        // foreach ($school->classes as $class) {
        //     $studentsByClass[$class->name] = $class->students();
        // }
    
        return view('school.show_courses', compact('school', 'coursesCount','uniqueSubjectNames'));
    }

    public function createCourse(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'code' => 'required|string',
            'general_name' => 'required|string',
            'school_id' => 'required|numeric',
            'compulsory' => 'nullable|boolean', // Added validation rule for compulsory field
        ]);
    
        // Retrieve the authenticated user
        $user = auth()->user();
    
        $hasPermission = $user->profile && $user->profile->permission_create_course;
    
        $isSchoolOwner = $user->ownedSchools()->where('id', $validatedData['school_id'])->exists();
    
        // Check if the user is the school owner or if the user's school_id matches the one in the request
        if (!$isSchoolOwner && ($user->school_id != $validatedData['school_id'] && !$hasPermission)) {
            return response()->json(['error' => "You don't have permission for this action"], 403);
        }
    
        // Add the compulsory field to the data
        $validatedData['compulsory'] = $request->has('compulsory') ? $request->input('compulsory') : false;
    
        // Create the course
        $course = Course::create($validatedData);
    
        // Return the created package as a JSON response with picture_url
        return response()->json([
            'name' => $course->name,
            'created_at' => $course->created_at,
            'description' => $course->description,
            'compulsory' => $course->compulsory, // Include compulsory in the response
        ], 201);
    }
    public function editCourse(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'code',
                'description',
                'general_name',
                'compulsory',
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Set compulsory to false if it's not provided or if it's null
            $validatedData['compulsory'] = $request->has('compulsory') && $request->compulsory ? true : false;
    
            // Validate the request data
            $validator = \Validator::make($validatedData, [
                'name' => 'required|string',
                'code' => 'required|string',
                'description' => 'nullable|string',
                'general_name' => 'required|string',
                'compulsory' => 'boolean', // Update validation rule for compulsory to allow boolean values
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the class section by ID
            $course = Course::findOrFail($id);
    
            // Update the class section with the validated data
            $course->update($validatedData);
    
            // Return the updated class section as a JSON response
            return response()->json($course, 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the Class Section.' . $e->getMessage()], 500);
        }
    }
    
    
    public function deleteCourse($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);

            // Check if the authenticated user has permission_confirm_students and belongs to the same school
            $authenticatedUser = auth()->user();
            $isSchoolOwner = $authenticatedUser->ownedSchools()
                ->where('id', $course->school_id)
                ->exists();
            
            $hasPermission = $authenticatedUser->profile && $authenticatedUser->school_id == $course->school_id &&
                $authenticatedUser->profile->permission_create_course ;

            if (!$hasPermission && !$isSchoolOwner) {
                return response()->json(['error' => 'You do not have permission to remove this course.'], 403);
            }

            // Use a transaction to ensure data consistency
            DB::beginTransaction();

            try {
                // Detach teachers and class sections
                $course->teachers()->detach();
                $course->students()->detach();
                $course->class_sections()->detach();

                // Delete the course
                $course->delete();

                // Commit the transaction
                DB::commit();

                return response()->json(['message' => 'Course deleted successfully']);
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollBack();
                \Log::error($e);

                return response()->json(['error' => 'Failed to delete course. Please try again.' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(['error' => 'Failed to find the course. Please try again.'], 404);
        }
    }

    public function fetchClassSections($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $classSections = $course->getAllSections();

        return response()->json($classSections);
    }
    public function updateSectionTeacherCourse(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'selected_classes.*' => 'sometimes|nullable|exists:school_class_sections,id',
            'teachers.*' => 'sometimes|nullable|exists:users,id',
            'class_id.*' => 'sometimes|nullable|exists:school_class_sections,id',
        ]);

        // Extract the data from the request
        $courseId = $request->input('course_id');
        $selectedClasses = $request->input('selected_classes', []);
        $teachers = $request->input('teachers', []);
        $classIds = $request->input('class_id', []);

        // Find the course
        $course = Course::findOrFail($courseId);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Prepare data for synchronization
            $teachersData = [];

            // Remove teachers, class sections, and students if the corresponding checkbox is deselected
            foreach ($course->class_sections as $classSection) {
                if (!in_array($classSection->id, $selectedClasses)) {
                    // If checkbox is deselected, remove from pivot tables
                    $course->class_sections()->detach($classSection->id);
                    $course->teachers()->detach($classSection->pivot->teacher_id);

                    // Remove students of the class
                    $students = $classSection->students()->pluck('user_id')->toArray();
                    $course->students()->detach($students);
                }
            }


            // Iterate over selected classes
            foreach ($selectedClasses as $index => $classSectionId) {
                // Check if the class section exists
                $classSection = SchoolClassSection::findOrFail($classSectionId);

                // Check if the teacher is selected
                if (isset($teachers[$index]) && !empty($teachers[$index])) {
                    $teachersData[$teachers[$index]] = [
                        'class_id' => $classIds[$index],
                        'user_id' => $teachers[$index] // Include user_id
                    ];
                }
                // Sync teachers for the specified course in the course_teacher pivot table
                $course->teachers()->sync($teachersData);

                // Sync teachers with the class sections in the course_class pivot table
                foreach ($teachersData as $teacherId => $teacherData) {
                    $course->class_sections()->syncWithoutDetaching([$classSectionId => ['teacher_id' => $teacherId]]);
                }
            }

            
            if ($course->compulsory) {
                foreach ($selectedClasses as $classSectionId) {
                    $section = SchoolClassSection::findOrFail($classSectionId);
                    $students = $section->students()->pluck('user_id')->toArray();
                    $course->students()->syncWithoutDetaching($students);
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Teachers and class sections have been updated successfully.']);
        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
            \Log::error('Error:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return response()->json(['error' => 'Failed to update teachers and class sections. ' . $e->getMessage()], 500);
        }
    } catch (\Exception $e) {
        \Log::error('Validation Error:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
        return response()->json(['error' => 'Validation failed. ' . $e->getMessage()], 400);
    }
}

    
}
