<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    public function create(Request $request)
    {
        $role = $request->input('role');
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Combine first name, middle name, and last name to get full name
        $fullName = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
    
        // Check if the user already has a profile
        $existingProfile = $user->profile;

        if (!$existingProfile) {
            // Create a new profile if the user doesn't have one
            $profile = new Profile([
                'role' => $role,
                'full_name' => $fullName,
                'email' => $user->email,
            ]);

            // Save the profile to the user
            $user->profile()->save($profile);
        } else {
            // Update existing profile if needed
            $existingProfile->update([
                'role' => $role,
                'full_name' => $fullName,
            ]);
        }
        return redirect('home');
    
        // Display a specific form based on the selected role
        // switch ($role) {
        //     case 'student':
        //         return view('profiles.create_student_profile', compact('profile'));
        //     case 'teacher':
        //         return view('profiles.create_teacher_profile', compact('profile'));
        //     case 'guardian':
        //         return view('profiles.create_guardian_profile', compact('profile'));
        //     case 'staff':
        //         return view('profiles.create_staff_profile', compact('profile'));
        //     case 'school_owner':
        //         return view('profiles.create_school_owner_profile', compact('profile'));
            
        //     // Add cases for other roles
        //     default:
                // return redirect('home');
        // }
    }
    
    public function searchSchools(Request $request)
    {
        $query = $request->input('query');

        $schools = School::where('name', 'LIKE', "%$query%")->get();

        return response()->json(['schools' => $schools]);
    }

    public function checkProfile(Request $request)
    {
        $hasProfile = auth()->user()->hasProfile();

        return response()->json(['hasProfile' => $hasProfile]);
    }

    public function profile()
    {
        $user = auth()->user();
        // dd($user->school);
       
        if ($user) {
            $profile = $user->profile;
            if ($profile) {
                $role = $profile->role;
                $profilepage = "profiles.create_$role"."_profile";
                // dd($profilepage);
                return view($profilepage, compact('profile'));
            }
    
            return redirect('home');
            
        }
        return redirect('login');

        
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
    
        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }
    
        $validatedData = $request->validate([
            'phone_number' => 'sometimes|required|string',
            'gender' => ['sometimes', 'required', Rule::in(['Male', 'Female'])],
            'current_class' => 'sometimes|required|string',
            'roll_number' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'qualifications' => 'sometimes|required|string',
            'date_of_birth' => 'sometimes|required|date',
    
            // Add other fields and validation rules as needed
            'country' => 'sometimes|required|string',
            'state' => 'sometimes|required|string',
            'city' => 'sometimes|required|string',
            'school_id' => 'sometimes|required|string',
            'user_package' => 'sometimes|required|int',
            'class_id' => 'sometimes|required|int',
        ]);
    
        $locationFields = ['country', 'state', 'city', 'address'];
        $updatedField = key(array_intersect_key($validatedData, array_flip($locationFields))) ?: key($validatedData);
    
        if ($updatedField === 'address') {
            $locationString = $this->updateAddress($profile, $validatedData);
            $displayData = $locationString;
        } elseif ($updatedField === 'user_package') {
            $displayData = $this->updateUserPackage($user, $validatedData);
        } elseif ($updatedField === 'school_id') {
            $displayData = $this->updateSchool($user, $validatedData);
        }elseif ($updatedField === 'class_id') {
            $displayData = $this->updateClass($user, $validatedData);
        } else {
            $displayData = isset($validatedData[$updatedField]) ? $validatedData[$updatedField] : '';
        }
    
        $profile->update($validatedData);
    
        $newIcon = "<span class='ion ion-good float-right text-info'>$displayData</span><i class='fas fas-good float-right text-info'></i>";
    
        return response()->json([
            'response' => $validatedData,
            'message' => 'Profile updated',
            'hide_button' => true,
            'new_icon' => $newIcon,
            'updated_field' => $updatedField,
        ]);
    }
    
    private function updateAddress($profile, $validatedData)
    {
        foreach (['country', 'state', 'city', 'address'] as $locationField) {
            if (isset($validatedData[$locationField])) {
                $profile->$locationField = $validatedData[$locationField];
            }
        }
    
        return $validatedData['address'] . ', ' . $validatedData['city'] . ', ' . $validatedData['state'] . ', ' . $validatedData['country'] . '.';
    }
    
    private function updateUserPackage($user, $validatedData)
    {
        $user->user_package_id = $validatedData['user_package'];
        $user->active_package = false;
        $user->expected_expiration = null;
        $user->save();
    
        $userPackage = UserPackage::find($validatedData['user_package']);
    
        if (!$userPackage) {
            return response()->json(['error' => 'User Package not found'], 404);
        }
    
        return $userPackage->name . ' ($' . $userPackage->price . ')';
    }
    
    private function updateSchool($user, $validatedData)
    {
        $school = School::find($validatedData['school_id']);
    
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }
    
        $user->school_id = $validatedData['school_id'];
        $user->profile->class_id = null;
        $user->profile->admin_confirmed = false;
        $user->profile->teacher_confirmed = false;
        $user->profile->student_confirmed = false;
        $user->profile->class_confirmed = false;
        $user->profile->staff_confirmed = false;
        $user->profile->permission_confirm_student = false;
        // ... (other permission fields)
        $user->save();
        $user->profile->save();
        $user->classSection()->detach();
    
        return $school->name;
    }
    private function updateClass($user, $validatedData)
    {
        $classId = $validatedData['class_id'];
    
        // Assuming SchoolClass is the model for your classes table
        $schoolClass = SchoolClass::find($classId);
    
        if (!$schoolClass) {
            return response()->json(['error' => 'Class not found'], 404);
        }
    
        // Check if the user belongs to the school
        $userSchool = $user->school;
    
        if (!$userSchool || $userSchool->id !== $schoolClass->school_id) {
            return response()->json(['error' => 'User does not belong to the school of the specified class'], 403);
        }
    
        // Check if the school has the specified class
        if (!$userSchool->classes->contains($schoolClass)) {
            return response()->json(['error' => 'The school does not have the specified class'], 403);
        }
    
        // Assuming the class is confirmed when updated

        $user->profile->class_id = $classId;
        $user->profile->class_confirmed = false;
        $user->profile->save();

        $user->classSection()->detach();
    
    
        return $schoolClass->name; // Display the class name or any relevant information
    }
    
    


    public function updateProfilePicture(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Get the file from the request
        $file = $request->file('profile_picture');

        // Generate a unique filename for the file
        $filename = 'profile_picture_' . time() . '.' . $file->getClientOriginalExtension();

        // Move the file to the storage directory
        $path = $file->storeAs('profile_pictures', $filename, 'public');

        // Resize the image to 500x500 pixels
        $image = Image::make(storage_path("app/public/{$path}"))->fit(500, 500);
        $image->save();

        // Update the user's profile picture path in the database
        $user->profile->update([
            'profile_picture' => $path,
        ]);

        // Return a response with the new profile picture path
        return response()->json([
            'new_profile_picture' => asset('storage/' . $path),
        ]);
    }

}
