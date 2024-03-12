<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolPackage;
use App\Models\School;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    //
    public function manageSchools()
    {
        $user = auth()->user();
        $schools= $user->ownedSchools;
        // dd($schools);
        return view('school_owner.manage_schools', compact('schools'));
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
        $data = $request->all();
        // dd($data['current_fieldset']);
        // $request->session()->forget('validated_data');
        // $request->session()->forget('currentFieldset');

        // $request->session()->forget('nextFieldset');
    
        try {
            switch ($data['current_fieldset']) {
                case 'school_information':
                    
                    return $this->validateAndSaveSchoolInformation($request, $data);
    
                case 'location':
                    return $this->validateAndSaveLocation($request, $data);
    
                case 'contact_information':
                    return $this->validateAndSaveContactInformation($request, $data);
    
                case 'social_media':
                    // break;
                    return $this->validateAndSaveSocialMedia($request, $data);
    
                default:
                    return response()->json(['error' => 'Invalid fieldset']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation error', 'errors' => $e->errors()]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Server eerror: ' . $e->getMessage()]);
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
                // Add validation rules for other fields as needed
            ]);

            // Find the school by ID
            $school = School::findOrFail($schoolId);

            // Update the school with the validated data
            $school->update($validatedData);

            if ($request->hasFile('logo')) {
                // Delete the old picture from the server
                Storage::disk('public')->delete($school->logo);
    
                // Upload the new picture
                $file = $request->file('logo');
                $filename = 'logo_picture' . time() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('logos_picture', $filename, 'public');
                $school->logo = $imagePath;
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
            // Find the package by ID
            $school = School::findOrFail($id);

            // Delete the package picture from storage
            Storage::disk('public')->delete($school->logo);

            // Delete the package from the database
            $school->delete();

            // Return a success response
            return response()->json(['message' => 'School deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            // Return an error response or handle accordingly
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
            return view('school_owner.show_owner', compact('school', 'totalStudents', 'totalTeachers', 'eventsCount'));
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
    
        if ($user->id === $school->school_owner_id){
            return view("school.show_$view", compact('school'));
           
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

    
}
