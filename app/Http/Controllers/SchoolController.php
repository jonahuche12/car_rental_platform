<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolPackage;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\SchoolClassSection;
use App\Models\StudentResult;
use App\Models\Course;
use App\Models\User;
use App\Models\Grade; // Import the Grade model at the top of your controller
use App\Models\Profile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    //
    public function manageSchools()
    {
        $user = auth()->user();
        
        // Retrieve the latest academic session
        $latest_academic_session = AcademicSession::latest()->first();
        
        // Retrieve the latest term
        $latest_term = Term::latest()->first();
        
        $schools = $user->ownedSchools;

        return view('school_owner.manage_schools', compact('schools', 'latest_academic_session', 'latest_term'));
    }
    public function updateAcademicSession(Request $request)
    {
        try {
            // Validate the request data if needed
            $request->validate([
                'school_id' => 'required|exists:schools,id',
            ]);
    
            // Retrieve the school based on the provided school ID
            $school = School::findOrFail($request->school_id);
    
            // Check if the school has a term
            if ($school->term()->exists()) {
                // Get the current term
                $currentTerm = $school->term;
    
                // Check if all students have results for the current term
                $studentsWithoutResults = $school->students()->whereDoesntHave('studentResults', function ($query) use ($currentTerm) {
                    $query->where('term_id', $currentTerm->id);
                })->count();
    
                if ($studentsWithoutResults > 0) {
                    return response()->json(['error' => 'Some students do not have results for the current term. Please ensure all students have results before updating the academic session.'], 400);
                }
    
                // Archive all assignments, assessments, and exams associated with the school
                $school->assignments()->update(['archived' => true]);
                $school->assessments()->update(['archived' => true]);
                $school->exams()->update(['archived' => true]);
            }
    
            // Perform the update operation for academic session
            // Update the school's academic session to the latest one
            $latestAcademicSession = AcademicSession::latest()->first();
            $school->academicSession()->associate($latestAcademicSession);
    
            // Save the changes
            $school->save();
    
            // Return a success response
            return response()->json(['message' => 'Academic session updated successfully']);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateTerm(Request $request)
    {
        try {
            // Validate the request data if needed
            $request->validate([
                'school_id' => 'required|exists:schools,id',
            ]);

            // Retrieve the school based on the provided school ID
            $school = School::findOrFail($request->school_id);

            // Get the current term
            $currentTerm = $school->term;

            // Check if the school has a term
            if ($currentTerm) {
                // Check if all students have results for the current term
                $studentsWithoutResults = $school->students()->whereDoesntHave('studentResults', function ($query) use ($currentTerm) {
                    $query->where('term_id', $currentTerm->id);
                })->count();

                if ($studentsWithoutResults > 0) {
                    return response()->json(['error' => 'Some students do not have results for the current term. Please ensure all students have results before updating the term. --'. $studentsWithoutResults], 400);
                }

                // Archive all assignments, assessments, and exams associated with the school
                $school->assignments()->update(['archived' => true]);
                $school->assessments()->update(['archived' => true]);
                $school->exams()->update(['archived' => true]);
            }

            // Perform the update operation for term
            // Update the school's term to the latest one
            $latestTerm = Term::latest()->first();
            $school->term()->associate($latestTerm);

            // Save the changes
            $school->save();

            // Return a success response
            return response()->json(['message' => 'Term updated successfully']);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createSchool()
    {
        $school_packages = Schoolpackage::all();
        return view('school_owner.create_school', compact('school_packages'));
    }

    public function showCreateSchoolForm($packageId)
    {
        // Retrieve the selected package based on the provided ID
        $package = SchoolPackage::find($packageId);

        // Retrieve the last created school by the user
        $lastSchool = session()->get('validated_data', []);

        // Convert $lastSchool to an array if it's an object
        if (is_object($lastSchool)) {
            $lastSchool = $lastSchool->toArray();
        }

        // Add any additional logic as needed

        return view('school_owner.create_school_form', compact(['package', 'lastSchool']));
    }

    public function storeSchool(Request $request)
    {
        // Check if the authenticated user already owns three or more schools
        $userSchoolCount = School::where('school_owner_id', auth()->user()->id)->count();
    
        if ($userSchoolCount >= 3) {
            return response()->json(['error' => 'You Cannot Create Another School.'], 422);
        }
    
        $data = $request->all();
    
        try {
            switch ($data['current_fieldset']) {
                case 'school_information':
                    return $this->validateAndSaveSchoolInformation($request, $data);
    
                case 'location':
                    return $this->validateAndSaveLocation($request, $data);
    
                case 'contact_information':
                    return $this->validateAndSaveContactInformation($request, $data);
    
                case 'social_media':
                    return $this->validateAndSaveSocialMedia($request, $data);
    
                default:
                    return response()->json(['error' => 'Invalid fieldset']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation error', 'errors' => $e->errors()]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
    
    
    private function validateAndSaveSchoolInformation(Request $request, array $data)
    {
        $rules = [
            'school_name' => 'required|string|max:255',
            'email' => 'required|email',
            'school_description' => 'required|string',
            'school_motto' => 'required|string',
            'school_mission' => 'required|string',
            'school_vision' => 'required|string',
            // 'school_logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Check if the field has changed and is not null, then add to validation rules
        foreach ($rules as $field => $rule) {
            if ($request->has($field) && $request->input($field) !== null) {
                $rules[$field] .= '|different:' . optional($request->input('last_school'))->{$field};
            }
        }

        // Add unique validation rule for email with ignoring the current record
        $rules['email'] .= ',' . auth()->user()->id;

        $validatedData = $request->validate($rules);

        // if ($request->hasFile('school_logo')) {
        //     $logo = $request->file('school_logo');
        //     $logoPath = $logo->store('logos', 'public');
        //     $validatedData['logo'] = $logoPath;
        // }

        $schoolData = [
            'name' => $validatedData['school_name'],
            'email' => $validatedData['email'],
            'motto' => $validatedData['school_motto'],
            'mission' => $validatedData['school_mission'],
            'vision' => $validatedData['school_vision'],
            'description' => $validatedData['school_description'],
            // 'logo' => $validatedData['logo'],
            'school_package_id' => $data['school_package_id'],
        ];

        $validatedData = $request->session()->get('validated_data', []);
        $validatedData['school_information'] = $schoolData;
    
        // Merge the existing session data with the new data
        $request->session()->put('validated_data', array_merge($validatedData, $request->session()->get('validated_data', [])));
        
        // Save the current fieldset and nextFieldset to the session
        $request->session()->put('current_fieldset', $data['current_fieldset']);
        $request->session()->put('nextFieldset', $data['nextFieldset']);
    
        return response()->json(['success' => true, 'message' => 'School information saved successfully']);
    }
    // Implement similar functions for other fieldsets: Location, Contact Information, Social Media
    private function validateAndSaveLocation(Request $request, array $data)
    {
        
        $validatedData = $request->validate([
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
        ]);

        $locationData = [
            'country' => $validatedData['country'],
            'state' => $validatedData['state'],
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
        ];
        // Save the validated data to the session
        $validatedData = $request->session()->get('validated_data', []);
        $validatedData['location'] = $locationData;
    
        // Merge the existing session data with the new data
        $request->session()->put('validated_data', array_merge($validatedData, $request->session()->get('validated_data', [])));
    
        // Save the current fieldset and nextFieldset to the session
        $request->session()->put('current_fieldset', $data['current_fieldset']);
        $request->session()->put('nextFieldset', $data['nextFieldset']);
    
        return response()->json(['success' => true, 'message' => 'Location information saved successfully']);
    }
    

    private function validateAndSaveContactInformation(Request $request, array $data)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|string',
        ]);

        $contactData = [
            'phone_number' => $validatedData['phone_number'],
            'school_package_id' => $data['school_package_id'],
        ];

        $validatedData = $request->session()->get('validated_data', []);
        $validatedData['contact_information'] = $contactData;

        // Merge the existing session data with the new data
        $request->session()->put('validated_data', array_merge($validatedData, $request->session()->get('validated_data', [])));

        // Save the current fieldset and nextFieldset to the session
        $request->session()->put('current_fieldset', $data['current_fieldset']);
        $request->session()->put('nextFieldset', $data['nextFieldset']);

        return response()->json(['success' => true, 'message' => 'Contact information saved successfully']);
    }
    private function validateAndSaveSocialMedia(Request $request, array $data)
    {
        $validatedData = $request->validate([
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
        ]);

        $socialMediaData = [
            'facebook' => $validatedData['facebook'],
            'instagram' => $validatedData['instagram'],
            'twitter' => $validatedData['twitter'],
            'linkedin' => $validatedData['linkedin'],
            'school_package_id' => $data['school_package_id'],
        ];

        $existingValidatedData = $request->session()->get('validated_data', []);
        $mergedData = array_merge($existingValidatedData, ['social_media' => $socialMediaData]);

        // Update the session with the merged data
        $request->session()->put('validated_data', $mergedData);

        // Pass $request to the saveDataToDatabase method
        $this->saveDataToDatabase($request);

        // Clear the session data if needed
        // $request->session()->forget('current_fieldset');
        // $request->session()->forget('nextFieldset');

        return response()->json(['success' => true, 'message' => 'School saved successfully', 'data' => $mergedData]);
    }

    private function saveDataToDatabase(Request $request)
    {
        // return response()->json(['success' => true, 'message' => 'School saved successfully', 'data'=> $request]);
        // Retrieve validated data from the session
        $validatedData = $request->session()->get('validated_data', []);

        // Instantiate a new School model with school_information
        $school = new School($validatedData['school_information']);

        // Assign location data directly to the school instance
        $school->country = $validatedData['location']['country'];
        $school->state = $validatedData['location']['state'];
        $school->city = $validatedData['location']['city'];
        $school->address = $validatedData['location']['address'];

        // Assign contact information directly to the school instance
        $school->phone_number = $validatedData['contact_information']['phone_number'];
        $school->school_package_id = $validatedData['contact_information']['school_package_id'];

        // Assign social media data directly to the school instance
        $school->facebook = $validatedData['social_media']['facebook'];
        $school->instagram = $validatedData['social_media']['instagram'];
        $school->twitter = $validatedData['social_media']['twitter'];
        $school->linkedin = $validatedData['social_media']['linkedin'];
        $school->school_package_id = $validatedData['social_media']['school_package_id'];
        $school->school_owner_id = auth()->user()->id;

        // Save the school instance
        $school->save();

        // Clear the session data
        $request->session()->forget('validated_data');
        $request->session()->forget('current_fieldset');
        $request->session()->forget('nextFieldset');

        return redirect('home')->with('message', "School Created Successfully");
    }


    public function editSchool(Request $request, $schoolId)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'mission' => 'nullable|string',
                'vision' => 'nullable|string',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone_number' => 'nullable|string|max:20',
                'website' => 'nullable|string|max:255',
                'facebook' => 'nullable|string|max:255',
                'instagram' => 'nullable|string|max:255',
                'twitter' => 'nullable|string|max:255',
                'linkedin' => 'nullable|string|max:255',
                'total_students' => 'nullable|integer',
                'total_teachers' => 'nullable|integer',
                'total_staff' => 'nullable|integer',
                'is_active' => 'nullable|boolean',
                'school_package_id' => 'nullable|exists:school_packages,id',
            ]);
    
            // Find the school by ID
            $school = School::findOrFail($schoolId);
    
            // Update the school with the validated data
            $school->update($validatedData);
    
            if ($request->hasFile('logo')) {
                // Delete the old picture from the server
                Storage::disk('public')->delete($school->logo);
    
                // Resize and upload the new picture
                $file = $request->file('logo');
                $filename = 'logo_picture' . time() . '.' . $file->getClientOriginalExtension();
    
                // Resize the image
                $resizedImage = Image::make($file)->resize(500, 500)->encode();
    
                // Store the resized image
                Storage::disk('public')->put('logos_picture/' . $filename, $resizedImage);
    
                $school->logo = 'logos_picture/' . $filename;
                $school->save();
            }
    
            return response()->json(['message' => 'School updated successfully', 'school' => $school]);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteSchool($id)
    {
        try {
            // Find the school by ID
            $school = School::findOrFail($id);

            // Get the authenticated user
            $user = Auth::user();

            // Check if the authenticated user is authorized to delete the school
            if ($user->id === $school->school_owner_id || $user->profile->role === 'super_admin') {
                // Check if the school is active
                if ($school->is_active) {
                    // Return an error response if the school is active
                    return response()->json(['error' => 'Cannot delete an active school.'], 422);
                }

                // Check if the school has associated students or teachers
                if ($school->students()->exists() || $school->teachers()->exists()) {
                    // Return an error response if the school has associated students or teachers
                    return response()->json(['error' => 'Cannot delete school with associated students or teachers.'], 422);
                }

                // Delete the school's logo from storage if it exists
                if ($school->logo) {
                    Storage::disk('public')->delete($school->logo);
                }

                // Delete the school from the database
                $school->delete();

                // Return a success response
                return response()->json(['message' => 'School deleted successfully'], 200);
            } else {
                // Return an error response if the user is not authorized
                return response()->json(['error' => 'Unauthorized to delete the School.'], 403);
            }
        } catch (\Exception $e) {
            \Log::error($e);

            // Return an error response if an exception occurs
            return response()->json(['error' => 'Failed to delete the School.'], 500);
        }
    }

    public function activateSchool(Request $request, $schoolId)
    {
        // Retrieve the school and its package
        $school = School::findOrFail($schoolId);
        $package = $school->schoolPackage;

        // Check if activation is allowed based on package
        if ($package->id == 1) {
            // Update is_active status
            $school->update(['is_active' => true]);
            return response()->json(['status' => 'success']);
        } else {
            // Redirect to payment page with the package price
            $price = $package->price; // You need to define the price attribute in your SchoolPackage model
            $package_id = $package->id;
            return response()->json(['status' => 'payment_required', 'price' => $price, 'package_id' => $package_id, 'school_id' => $schoolId]);
        }
    }
    public function ownerShow($id)
    {
        // Find the school by ID
        $school = School::find($id);
        $academicSessions = AcademicSession::all();
        

        // Check if the school is not found
        if (!$school) {
            return redirect()->route('home')->with('error', 'School Not Found.');
        }

        // Check if the authenticated user is the owner of the school or an admin for the school
        $user = auth()->user();

        if ($user->id === $school->school_owner_id || ($user->school->id == $school->id && $user->profile->admin_confirmed)) {
            // Fetch additional data or perform any necessary operations here
            $totalStudents = $school->students()->count();
            $totalTeachers = $school->teachers()->count();
            $eventsCount = $school->events()->count();

            // Pass the necessary data to the view
            return view('school_owner.show_owner', compact('school', 'totalStudents', 'totalTeachers', 'eventsCount', 'academicSessions'));
        } else {
            // dd($user->school()->id);
            // If the authenticated user is not the owner or an admin, handle it as needed
            return redirect()->route('home')->with('error', 'You do not have the neccessary permission');
        }
    }

    
    public function showSchool($schoolId, $view)
    {
        $school = School::findOrFail($schoolId);
        $user = auth()->user();
        // dd($school->getAdmins());
        $studentsByClass = [];
    
        foreach ($school->classes as $class) {
            $studentsByClass[$class->name] = $class->students();
        }
    
        if ($user->id === $school->school_owner_id){
            return view("school.show_$view", compact('school', 'studentsByClass'));
           
        }
    
        // Pass the $view parameter to the view
        return redirect()->route('home')->with('error', 'You do not have the neccessary permission');
    }

    // app/Http/Controllers/AdminController.php

    public function confirmAndMakeAdmin(Request $request, User $user)
    {
        try {
            // Validate the request data
            $request->validate([
                'school_id' => 'required|exists:schools,id',
            ]);

            // Ensure that the authenticated user is the school owner
            $schoolId = $request->input('school_id');
            $authenticatedUserId = auth()->id();
            $isSchoolOwner = User::where('id', $authenticatedUserId)
                ->whereHas('ownedSchools', function ($query) use ($schoolId) {
                    $query->where('id', $schoolId);
                })
                ->exists();

            if (!$isSchoolOwner) {
                return response()->json(['error' => 'You do not have permission to make this user an admin for the specified school.'], 403);
            }

            // Ensure that the provided user belongs to the specified school
            if ($user->school_id != $schoolId) {
                return response()->json(['error' => "This user does not belong to the specified school."], 400);
            }

            // Check if the number of admins has exceeded the maximum allowed by the school's package
            $school = School::find($schoolId);
            $maxAdmins = $school->schoolPackage->max_admins;

            if ($school->getConfirmedAdmins()->count() >= $maxAdmins) {
                return response()->json(['error' => 'The maximum number of admins for this school has been reached.'], 400);
            } 

            // Update the user's profile to make them an admin
            $user->profile->admin_confirmed = true;
            $user->profile->save();

            // Fetch the updated user with the profile
            $updatedUser = User::with('profile')->find($user->id);
            $profilePicturePath = $user->profile->profile_picture;
            $fullProfilePictureUrl = $profilePicturePath
                ? asset('storage/' . $profilePicturePath)
                : asset('dist/img/avatar5.png');
            
            return response()->json(['message' => 'User is now an admin', 'newAdmin' => $user, 'profile_picture_url' => $fullProfilePictureUrl]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to make the user an admin. Please try again.'], 500);
        }
    }

    public function grantPermission(Request $request, $adminId)
    {
        try {
            // Validate the request if needed
            // ...
    
            $permissionName = $request->input('permission');
    
            // Retrieve the admin profile
            $admin = Profile::find($adminId);
    
            if (!$admin) {
                return response()->json(['error' => 'Admin not found'], 404);
            }
    
            // Toggle the permission value
            $admin->$permissionName = !$admin->$permissionName;
            $admin->save();
    
            $action = $admin->$permissionName ? 'granted' : 'revoked';
            return response()->json(['message' => "$permissionName $action successfully"]);
        } catch (\Exception $e) {
            // Log the exception for further investigation
            \Log::error($e);
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeAdmin($adminId)
    {
        try {
            $admin = User::findOrFail($adminId);

            // Remove admin permissions
            $admin->profile->update([
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
            $admin->profile->admin_confirmed = false;
            $admin->profile->save();

            return response()->json(['message' => 'Admin removed successfully']);
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(['error' => 'Failed to remove admin. Please try again.'], 500);
        }
    }

    public function getTermsByAcademicSession(Request $request, $academicSessionId)
    {
        try {
            // Retrieve terms for the specified academic session
            $terms = Term::where('academic_session_id', $academicSessionId)->get();

            // Return the list of terms as JSON response
            return response()->json($terms);
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., academic session not found)
            return response()->json(['error' => 'Failed to retrieve terms.'], 500);
        }
    }
    public function getGradeDistribution(Request $request, $courseCode){

        $classId = $request->get('classId');
        $assessmentType = $request->get('assessmentType');
        $academicSessionId = $request->get('academicSessionId');
        $termId = $request->get('termId');
        $label = "Grade Distribution ". $courseCode;
    
        // Find the course by code
        $course = Course::where('code', $courseCode)->firstOrFail();
    
        // Start with the base query to fetch grades associated with the course
        $gradesQuery = $course->grades();
    
        if ($assessmentType) {
            // Dynamically apply the appropriate column filter on the grades query
          // Assuming assessmentType corresponds to a column name
        
            // Split the assessmentType at underscores and take the first part
            $assessmentTypeParts = explode('_', $assessmentType);
            $firstPart = ucwords($assessmentTypeParts[0]);
        
            $label .= " " . $firstPart;
        } else {
            $assessmentType = 'exam_id';
            $label .= " Exam";
        }
        $gradesQuery->whereNotNull($assessmentType);
        
        
    
        // Filter grades based on classId (if specified)
        if ($classId) {
            // Retrieve students associated with the specified school class
            $schoolClass = SchoolClass::findOrFail($classId);
            $studentIds = $schoolClass->students()->pluck('id')->toArray();
    
            // Filter grades for students in the specified class
            $gradesQuery->whereIn('user_id', $studentIds);
            $label .= " " . $schoolClass->code;
        }else {
            $label .= " All Classes";
        }
    
        // Filter grades based on academic session (if specified)
        if ($academicSessionId) {
            $academicSession = AcademicSession::findOrFail($academicSessionId);
            $gradesQuery->where('academic_session_id', $academicSessionId);
            $label .= " " . $academicSession->name;
    
            // Filter grades based on term (if specified)
            if ($termId) {
                $term = Term::findOrFail($termId);
                $gradesQuery->where('term_id', $termId);
                $label .= " " . $term->name;
            }
        } else {
            $label .= " All Sessions";
        }
    
        // Retrieve grades from the filtered grades query
        $grades = $gradesQuery->get();
    
        // Instantiate a Grade model to access the calculateGradeDistribution method
        $gradeModel = new Grade();
    
        // Calculate average complete scores for the retrieved grades based on the assessment type
        $percentage = $gradeModel->getAverageCompleteScore($grades, $assessmentType);
    
        // Calculate grade distribution using the determined percentage
        $gradeDistribution = $gradeModel->calculateGradeDistribution($grades->pluck('score')->toArray(), $percentage);

         // Calculate grade distribution
    $gradeDistributionInsightData = $grades->groupBy('grade')->map->count();

    // Calculate the total number of students who took the course
    $totalStudents = $grades->pluck('user_id')->unique()->count();

     // Labels for different grades
     $gradeLabels = ['A+', 'A', 'B', 'C', 'D', 'E', 'F'];

     // Calculate grade distribution counts for each grade label
     $gradeCounts = [];
     foreach ($gradeLabels as $glabel) {
         $gradeCounts[$glabel] = isset($gradeDistribution[$glabel]) ? $gradeDistribution[$glabel] : 0;
     }

    // Construct insights string
        $insights = "Total Students: $totalStudents\n";
        foreach ($gradeCounts as $grade => $count) {
            $insights .= "$grade Count: $count\n";
        }

        // Return both grade distribution and grades data as JSON response
        return response()->json([
            'gradeDistribution' => $gradeDistribution,
            'grades' => $grades->toArray(),
            'assessmentType' => $assessmentType,
            'percentage' => $percentage,
            'label' => $label,
            'totalStudents'=> $totalStudents,
            'gradeCounts' => $gradeCounts,
            'insights' => $insights, // Include insights in the response
        ]);

    }
    
}
