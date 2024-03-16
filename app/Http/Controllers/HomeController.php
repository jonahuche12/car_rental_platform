<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolPackage;
use App\Models\School;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\UserPackage;
use App\Models\Qualification;
use Illuminate\Support\Facades\Validator; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $showRoleSelectionModal = session('showRoleSelectionModal', false);
        $totalPackagesCount = null;
        $totalSchoolsCount = null;
        $students = null;
        $totalUserPackagesCount = null;
        $school = null;
        

        // Check if the user has a profile
        if (auth()->user()->hasProfile()) {
            $profile = auth()->user()->profile;
            $userRole = $profile->role;
            if ($profile->admin_confirmed || $profile->teacher_confirmed || $profile->staff_confirmed || $profile->student_confirmed) {
                
                $school = auth()->user()->school()->first();
            }

            if ($userRole == 'super_admin') {
                // Assuming you have models named SchoolPackage for packages and School for schools
                $totalPackagesCount = SchoolPackage::count();
                $totalSchoolsCount = School::count();
                $totalUserPackagesCount = UserPackage::count();
            } elseif ($userRole == "school_owner") {
                $totalSchoolsCount = School::where('school_owner_id', auth()->user()->id)->count();
            } elseif ($userRole == "admin" && $profile->admin_confirmed) {
                // Retrieve the actual School model instance
                $school = auth()->user()->school()->first();
            } elseif ($userRole == "teacher" ) {
                // Retrieve the actual School model instance
                // $students =
            }
        }

        return view('home', compact('showRoleSelectionModal', 'totalPackagesCount', 'totalSchoolsCount', 'totalUserPackagesCount', 'school', 'students'));
    }

    public function viewCurriculum($classId)
    {
        try {
            // Find the class by ID
            $class = SchoolClass::findOrFail($classId);
            $curriculum =$class->curriculum();
            // dd($curriculum);


            // Check if curriculum is found
            if ($curriculum) {
                // You can pass the $curriculum variable to the view or perform other actions
                return view('curriculum.view_curriculum', ['curricula' => $curriculum]);
            } else {
                // Handle the case when curriculum is not found
                return redirect()->back()->with('error','No Goverment Approved Curriculum Found');
            }
        } catch (\Exception $e) {
            \Log::error($e);

            // Handle exceptions or return an error response
            return redirect()->back()->with('error', 'Failed to retrieve curriculum. ');
        }
    }



    public function storeQualification(Request $request)
    {
        try {
            $user = auth()->user(); // Assuming the user is authenticated
    
            $validatedData = $request->validate([
                'certificate' => 'required|string',
                'school_attended' => 'required|string',
                'starting_year' => 'required|numeric',
                'completion_year' => 'required|numeric',
            ]);
    
            // Add user_id to the validated data
            $validatedData['user_id'] = $user->id;
    
            // Create the qualification
            $qualification = Qualification::create($validatedData);
    
            return response()->json([
                'success' => 'Qualification saved successfully',
                'data' => $qualification,
            ]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to save qualification. Please try again.'], 500);
        }
    }
    public function showStudent($studentId)
    {
        $user = auth()->user();

        // Check if the user is authenticated
        if (!$user) {
            
            return redirect()->route('home')->with('error', 'Unauthorized for this action');
        }

        // Find the student
        $student = User::find($studentId);
        // dd($student->userClassSection->courses);

        // Check if the student exists and is a student
        if ($student && $student->profile->role == 'student') {
            // Check if the user is an admin or a form teacher of the same class as the student
            if ($user->profile->role == 'admin' && $user->school_id == $student->school_id) {
                return view('school.student_page', compact('student'));
            } elseif ($user->profile->role == 'teacher') {
                // dd($student);
                // Check if the user is a form teacher of any class sections that the student belongs to
                foreach ($student->userClassSection->formTeachers as $formTeacher) {
                   if($formTeacher->id == $user->id){
                        return view('school.student_page', compact('student'));
                   }
                }
            }elseif ($user->profile->role == "student" && $student->id == $user->id) {
                return view('school.student_page', compact('student'));
                
            }
        }

        // If the user is not authorized or the student doesn't exist, return unauthorized error
        
        return redirect()->route('home')->with('error', 'Unauthorized for this action');
    }

    public function submitCourse(Request $request)
    {
        try {
            $user = auth()->user();
            $user->courses()->sync($request->courses);

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Failed to enroll in course.'. $e->getMessage()], 500);
        }
    }


    
}
