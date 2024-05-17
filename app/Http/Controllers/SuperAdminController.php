<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ActivationEmail;
use App\Models\SchoolPackage;
use App\Models\Test;
use App\Models\School;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\UserPackage;
use App\Mail\ConfirmTransferMail;
use App\Models\Transfer;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\Curriculum;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendAcademicSessionNotification;
use App\Jobs\SendTermNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;



use Illuminate\Support\Facades\Validator; 

class SuperAdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','superadmin']);
    }
    public function manageSchoolPackage()
    {
        $packages = SchoolPackage::all();
        return view('super_admin.school_packages', compact('packages'));
    }

    public function createPackage(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'max_students' => 'required|integer|min:0',
            'max_admins' => 'required|integer|min:0',
            'max_teachers' => 'required|integer|min:0',
            'max_classes' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Added image validation
        ]);
    
        $package = SchoolPackage::create($validatedData);

        // Save the uploaded image to the storage folder
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');

            // Generate a unique filename for the file
            $filename = 'package_picture' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('packages_picture', $filename, 'public');
            $package->picture = $imagePath;
            $package->save();
        }

        // Return the created package as JSON response with picture_url
        return response()->json([
            'name' => $package->name,
            'created_at' => $package->created_at,
            'picture_url' => asset('storage/' . $package->picture), // Adjust the path based on your storage configuration
            'duration_in_days' => $package->duration_in_days,
            'max_students' => $package->max_students,
            'max_teachers' => $package->max_teachers,
            'max_admins' => $package->max_admins,
            'description' => $package->description,
        ], 201);
    }
    
    public function editPackage(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'description',
                'price',
                'duration_in_days',
                'max_students',
                'max_admins',
                'max_teachers',
                'max_classes',
                'is_active',
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = Validator::make($validatedData, [
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric|min:0',
                'duration_in_days' => 'nullable|integer|min:1',
                'max_students' => 'nullable|integer|min:0',
                'max_admins' => 'nullable|integer|min:0',
                'max_teachers' => 'nullable|integer|min:0',
                'max_classes' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the package by ID
            $package = SchoolPackage::findOrFail($id);
    
            // Update the package with the validated data
            $package->fill($validatedData);
    
            // Save the changes to the database
            $package->save();
    
            // Handle picture update separately
            if ($request->hasFile('picture')) {
                // Delete the old picture from the server
                Storage::disk('public')->delete($package->picture);
    
                // Upload the new picture
                $file = $request->file('picture');
                $filename = 'package_picture' . time() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('packages_picture', $filename, 'public');
                $package->picture = $imagePath;
                $package->save();
            }
    
            // Return the updated package as JSON response
            return response()->json($package, 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the package.' . $e->getMessage()], 500);
        }
    }
    

    public function deletePackage($id)
    {
        try {
            // Find the package by ID
            $package = SchoolPackage::findOrFail($id);

            // Delete the package picture from storage
            Storage::disk('public')->delete($package->picture);

            // Delete the package from the database
            $package->delete();

            // Return a success response
            return response()->json(['message' => 'Package deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to delete the package.'], 500);
        }
    }

    public function manageAcademicSession()
    {
        $academic_sessions = AcademicSession::all();
        return view('super_admin.academic_session', compact('academic_sessions'));
    }
    
    
    public function createAcademicSession(Request $request){
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);
    
        // Create the academic session
        $academicSession = AcademicSession::create($validatedData);
        // $academicSession = AcademicSession::first();
    
        // Retrieve all school owners
        $schoolOwners = User::whereHas('profile', function ($query) {
            $query->where('role', 'school_owner');
        })->get();
    
        // Dispatch email jobs to send notifications to school owners
        foreach ($schoolOwners as $owner) {
            // dd($owner);
            SendAcademicSessionNotification::dispatch($owner, $academicSession);
        }
    
        // Return the response
        return response()->json([
            'message' => "Academic Session Created Successfully",
        ], 201);
    }
    
    public function addTerm(Request $request, $academic_session_id)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                // Add more validation rules as needed
            ]);
    
            // Retrieve the academic session by its ID
            $academicSession = AcademicSession::findOrFail($academic_session_id);
    
            // Create a new term for the academic session
            $term = new Term();
            $term->name = $validatedData['name'];
            // Set any other term attributes as needed
            // ...
    
            // Associate the term with the academic session
            $academicSession->terms()->save($term);
    
            // Retrieve all school owners
            $schoolOwners = User::whereHas('profile', function ($query) {
                $query->where('role', 'school_owner');
            })->get();
    
            // Dispatch email jobs to send notifications to school owners
            foreach ($schoolOwners as $owner) {

                // \Log::error('Email: '. $owner->email);
                SendTermNotification::dispatch($owner, $term);
            }
    
            // Return a response indicating success
            return response()->json(['message' => 'Term added successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process
            return response()->json(['error' => 'An error occurred while adding the term'. $e->getMessage()], 500);
        }
    }
    

    public function editAcademicSession(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = Validator::make($validatedData, [
                'name' => 'nullable|string',
                
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the package by ID
            $academic_session = AcademicSession::findOrFail($id);
    
            // Update the package with the validated data
            $academic_session->fill($validatedData);
    
            // Save the changes to the database
            $academic_session->save();
    
    
            // Return the updated package as JSON response
            return response()->json($academic_session, 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the package.' . $e->getMessage()], 500);
        }
    }

    public function deleteAcademicSession($id)
    {
        try {
            // Find the academic session by ID
            $academic_session = AcademicSession::findOrFail($id);
    
            // Delete all terms associated with the academic session
            $academic_session->terms()->delete();
    
            // Delete the academic session from the database
            $academic_session->delete();
    
            // Return a success response
            return response()->json(['message' => 'Academic Session and associated terms deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to delete the Academic Session and associated terms.'], 500);
        }
    }
    

    // Controller method to fetch term details
    public function getTermDetails($id)
    {
        try {
            // Retrieve the term based on the provided ID
            $term = Term::findOrFail($id);

            // Return the term details as JSON response
            return response()->json(['term' => $term], 200);
        } catch (\Exception $e) {
            // Handle exceptions, such as term not found
            return response()->json(['error' => 'Term not found'], 404);
        }
    }

    public function editTerm(Request $request, $id)
    {
        // Find the term by its ID
        $term = Term::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            // Add more validation rules as needed
        ]);

        // Update the term with the validated data
        $term->update($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Term updated successfully'], 200);
    }


    public function deleteTerm($id)
    {
        try {
            // Find the package by ID
            $term = Term::findOrFail($id);


            // Delete the package from the database
            $term->delete();

            // Return a success response
            return response()->json(['message' => 'Term deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to delete the Term.'], 500);
        }
    }






    public function adminConfirmPayment($paymentSessionId)
    {
        $user = auth()->user();
        $transfer = Transfer::where('payment_session_id', $paymentSessionId)->first();

        if ($user && $user->profile->role === "super_admin" && $transfer) {
            return view('payment.transfer', compact('transfer'));
        }

        // If no transfer found or unauthorized user, redirect back with an error message
        return redirect()->route('home')->with('error', "No payment with the session ID: $paymentSessionId");
    }


    public function confirmTransfer(Request $request)
    {
        // dd($request->input('payment_session_id'));
        $transfer = Transfer::where('payment_session_id', $request->input('payment_session_id'))->first();
        // dd($transfer);
        $paid_for = $transfer->paid_for;
        // dd($transfer);

        if ($request->input('amount') < $transfer->amount) {
            // Handle incomplete payment
            $transfer->payment_confirmed = false;
            $transfer->status = 'payment not complete';
            $transfer->save();
            Mail::to($transfer->email)->send(new ConfirmTransferMail($transfer));
            return redirect()->route('home')->with('error', "Confirmation not Successful");
        } elseif ($request->input('amount') == $transfer->amount) {
            // Handle successful payment
            $transfer->payment_confirmed = true;
            $transfer->status = "payment complete";
            $transfer->save();

            // Call createActivation method with the request
            $this->createActivation($request, $transfer->payment_session_id, $paid_for);
            Mail::to($transfer->email)->send(new ConfirmTransferMail($transfer));
            return redirect()->route('home')->with('success', "Confirmation Successful");
        }

        return redirect()->back()->with('error', "Payment not confirmed");
    }

    public function createActivation(Request $request, $payment_session_id, $paid_for)
    {
        // Retrieve amount from the request
        $amount = $request->input('amount');
    
        // Find the transfer record by payment_session_id
        $transfer = Transfer::where('payment_session_id', $payment_session_id)->first();
    
        if ($transfer) {
            // Payment session found, proceed with activation
    
            if ($paid_for === 'user_activation') {
                // Activate user package
                $user = User::findOrFail($transfer->id_paid_for);
                // dd($user);
                $user->active_package = true;
                $user->expected_expiration = now()->addDays($user->userPackage->duration_in_days);
                $user->save();
    
                // Create wallet for the user
                $wallet = new Wallet();
                $wallet->user_id = $user->id;
                $wallet->balance = 0; // Set initial balance as 0 or any default value
                $wallet->save();
            } elseif ($paid_for === 'school_activation') {
                // Activate school package
                $school = School::findOrFail($transfer->id_paid_for);
                $school->is_active = true;
                $school->school_expected_expiration = now()->addDays($school->schoolPackage->duration_in_days);
                $school->save();
    
                // Create wallet for the school
                $wallet = new Wallet();
                $wallet->school_id = $school->id;
                $wallet->balance = 0; // Set initial balance as 0 or any default value
                $wallet->save();
            }
            elseif ($paid_for === 'school_connects') {
                // Activate school package
                $user = User::findOrFail($transfer->id_paid_for);
                if ($transfer->amount == 500) {
                    $connect = 90;
                } elseif ($transfer->amount == 1000) {
                    $connect = 210;
                }elseif ($transfer->amount == 2000) {
                    $connect = 450;
                }
                elseif ($transfer->amount == 3000) {
                    $connect = 1000;
                }else{
                    $connect = 2000;
                } 
                         // dd($user);
                $user->profile->school_connects += $connect;
                $user->profile->save();
    
            }
    
            // Record payment details in payments table
            $payment = DB::table('payments')->insertGetId([
                'user_id' => $user->id,
                'amount' => $transfer->amount,
                'paystack_reference' => $transfer->payment_session_id,
                'paid_for' => $paid_for,
                'paid_at' => now(),
                'channel' => "transfer",
                'currency' => "NGN",
                'ip_addr' => $request->ip(),
                'successful' => true,
                'reference_id' => $transfer->id_paid_for,
                // Add other fields as needed
            ]);
    
            // Retrieve the payment instance
            $payment = Payment::find($payment);
    
            if ($user) {
                // Send email to the user
                Mail::to($user->email)->send(new ActivationEmail($user));
            }
    
            // Clear payment session
            session()->forget('payment_session');
    
            // Redirect to home route with success message
            return redirect()->route('home')->with('success', "Package has been activated.");
        } else {
            // Payment not successful, handle appropriately
            return redirect()->back()->with('error', 'Payment not successful');
        }
    }

    public function manageUserPackage()
    {
        $packages = UserPackage::all();
        return view('super_admin.user_packages', compact('packages'));
    }

    public function createUserPackage(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'max_lessons_per_day' => 'required|integer|min:0',
            'max_uploads' => 'required|integer|min:0',
            
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Added image validation
        ]);
    
        $package = UserPackage::create($validatedData);

        /// Save the uploaded image to the storage folder
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');

            // Generate a unique filename for the file
            $filename = 'user_package_picture' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('user_packages_picture', $filename, 'public');

            // Resize the image to 500x500
            $resizedImage = Image::make(storage_path('app/public/' . $imagePath))->fit(540, 360);
            $resizedImage->save(storage_path('app/public/' . $imagePath));

            $package->picture = $imagePath;
            $package->save();
        }


        // Return the created package as JSON response with picture_url
        return response()->json([
            'name' => $package->name,
            'created_at' => $package->created_at,
            'picture_url' => asset('storage/' . $package->picture), // Adjust the path based on your storage configuration
            'duration_in_days' => $package->duration_in_days,
            'max_lessons_per_day' => $package->max_lessons_per_day,
            'max_uploads' => $package->max_uploads,
            
            'description' => $package->description,
        ], 201);
    }

    public function editUserPackage(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'description',
                'price',
                'duration_in_days',
                'max_lessons_per_day',
                'max_uploads',
                
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = Validator::make($validatedData, [
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric|min:0',
                'duration_in_days' => 'nullable|integer|min:1',
                'max_lessons_per_day' => 'nullable|integer|min:0',
                'max_uploads' => 'nullable|integer|min:0',
                
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the package by ID
            $package = UserPackage::findOrFail($id);
    
            // Update the package with the validated data
            $package->fill($validatedData);
    
            // Save the changes to the database
            $package->save();
    
            // Handle picture update separately
            if ($request->hasFile('picture')) {
                // Delete the old picture from the server
                Storage::disk('public')->delete($package->picture);

                // Upload the new picture
                $file = $request->file('picture');
                $filename = 'user_package_picture' . time() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('user_packages_picture', $filename, 'public');

                // Resize the image to 500x500
                $resizedImage = Image::make(storage_path('app/public/' . $imagePath))->fit(500, 500);
                $resizedImage->save(storage_path('app/public/' . $imagePath));

                $package->picture = $imagePath;
                $package->save();
            }

    
            // Return the updated package as JSON response
            return response()->json($package, 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the package.' . $e->getMessage()], 500);
        }
    }
    

    public function deleteUserPackage($id)
    {
        try {
            // Find the package by ID
            $package = UserPackage::findOrFail($id);

            // Delete the package picture from storage
            Storage::disk('public')->delete($package->picture);

            // Delete the package from the database
            $package->delete();

            // Return a success response
            return response()->json(['message' => 'Package deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to delete the package.'], 500);
        }
    }

    public function manageCurriculum()
    {
        $curricula = Curriculum::all();
        return view('super_admin.curricula', compact('curricula'));
    }
    
    public function storeCurriculum(Request $request)
    {
        $validatedData = $request->validate([
            'country' => 'required|string',
            'theme' => 'required|string',
            'description' => 'nullable|string',
            'class_level' => 'required|string',
            'subject' => 'required|string',
            'topic.*' => 'required|string', // Validate each topic
            'topic_description.*' => 'nullable|string', // Validate each description
        ]);

        // Create the curriculum
        $curriculum = Curriculum::create([
            'country' => $validatedData['country'],
            'theme' => $validatedData['theme'],
            'subject' => $validatedData['subject'],
            'description' => $validatedData['description'],
            'class_level' => $validatedData['class_level'],
        ]);

        // Attach topics to the curriculum in the pivot table
        foreach ($validatedData['topic'] as $index => $topic) {
            $description = $validatedData['topic_description'][$index] ?? null;

            $curriculum->topics()->attach(
                $topic,
                ['topic' => $topic,'description' => $description]
            );
        }

        return response()->json(['message' => 'Curriculum created successfully']);
    }

    public function editCurriculum(Request $request, $id)
    {
        try {
            // Define the fields that are expected to be in the request
            $expectedFields = [
                'name',
                'description',
                
            ];
    
            // Filter the request data to include only the expected fields
            $validatedData = $request->only($expectedFields);
    
            // Validate the request data
            $validator = Validator::make($validatedData, [
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Find the package by ID
            $curriculum = Curriculum::findOrFail($id);
    
            // Update the package with the validated data
            $curriculum->fill($validatedData);
    
            // Save the changes to the database
            $curriculum->save();
    
    
            // Return the updated package as JSON response
            return response()->json($curriculum, 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the Curriculum.' . $e->getMessage()], 500);
        }
    }
    
    public function deleteCurriculum($id)
    {
        try {
            // Find the curriculum by ID
            $curriculum = Curriculum::findOrFail($id);
    
            // Detach the related topics (delete pivot data)
            $curriculum->topics()->detach();
    
            // Delete the curriculum from the database
            $curriculum->delete();
    
            // Return a success response
            return response()->json(['message' => 'Curriculum deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to delete the curriculum. ' . $e->getMessage()], 500);
        }
    }

    public function editCurriculumTopic(Request $request, $id)
    {
        // return response()->json($id, 200);
        // dd($id);
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'topic' => 'required|string',
                'description' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Log the validated data
            \Log::info('Validated Data: ' . json_encode($request->all()));
    
            // Update the curriculum topic with the validated data using DB facade
            $affectedRows = DB::table('curricula_topics')
                ->where('id', $id)
                ->update([
                    'topic' => $request->input('topic'),
                    'description' => $request->input('description'),
                ]);
    
            // Log the number of affected rows
            \Log::info('Affected Rows: ' . $affectedRows);
    
            // Return the updated curriculum topic as JSON response
            return response()->json($id, 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to update the Curriculum Topic.' . $e->getMessage()], 500);
        }
    }
    
    

    public function deleteCurriculumTopic($id)
    {
        try {
            // Find the curriculum topic by its ID
            $curriculumTopic = DB::table('curricula_topics')->find($id);
    
            // Check if the curriculum topic exists
            if (!$curriculumTopic) {
                return response()->json(['error' => 'Curriculum Topic not found.'], 404);
            }
    
            // Delete the curriculum topic
            DB::table('curricula_topics')->where('id', $id)->delete();
    
            return response()->json(['message' => 'Curriculum Topic deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);
    
            return response()->json(['error' => 'Failed to delete the Curriculum Topic.'], 500);
        }
    }
    
    public function storeCurriculumTopic(Request $request, $curriculumId)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                
                'topic' => 'required|string',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Store the curriculum topic using DB facade
            $curriculumTopicId = DB::table('curricula_topics')->insertGetId([
                'curriculum_id' => $curriculumId,
                'topic' => $request->input('topic'),
                'description' => $request->input('description'),
            ]);

            // Retrieve the newly created curriculum topic
            $newCurriculumTopic = DB::table('curricula_topics')->find($curriculumTopicId);

            // Return the newly created curriculum topic as JSON response
            return response()->json($newCurriculumTopic, 201);
        } catch (\Exception $e) {
            \Log::error($e);

            // Return an error response or handle accordingly
            return response()->json(['error' => 'Failed to store the Curriculum Topic. ' . $e->getMessage()], 500);
        }
    }

    public function manageAllSchools()
    {
        // Call the static method to retrieve schools
        $schools = School::manageAllSchools();
        $latest_academic_session = AcademicSession::latest();
        // dd($schools);

        // Pass the $schools variable to a view or perform further operations
        return view('super_admin.all_schools', compact('schools', 'latest_academic_session'));
    }

    public function manageTest()
    {
        $tests = Test::with(['academicSession', 'term'])->get();
        $uniqueClassLevels = Curriculum::getUniqueClassLevels();
        $academicSessions = AcademicSession::all();
        $terms = Term::all();
    
        return view('super_admin.tests', compact('tests', 'uniqueClassLevels', 'academicSessions', 'terms'));
    }
    

    public function createTest(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);
    
        // Create the academic session
        $academicSession = AcademicSession::create($validatedData);
        // $academicSession = AcademicSession::first();
    
        // Retrieve all school owners
        $schoolOwners = User::whereHas('profile', function ($query) {
            $query->where('role', 'school_owner');
        })->get();
    
        // Dispatch email jobs to send notifications to school owners
        foreach ($schoolOwners as $owner) {
            // dd($owner);
            SendAcademicSessionNotification::dispatch($owner, $academicSession);
        }
    
        // Return the response
        return response()->json([
            'message' => "Academic Session Created Successfully",
        ], 201);
    }

    public function storeTest(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'type' => 'required|string|in:cognitive,class_level',
        'class_level' => 'required|string|max:255',
        'max_no_of_questions' => 'required|integer',
        'complete_score' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()->first()], 422);
    }

    $latestAcademicSession = AcademicSession::latest()->first();
    $latestTerm = Term::latest()->first();

    $test = new Test();
    $test->title = $request->title;
    $test->type = $request->type;
    $test->class_level = $request->class_level;
    $test->max_no_of_questions = $request->max_no_of_questions;
    $test->complete_score = $request->complete_score;
    $test->academic_session_id = $latestAcademicSession->id;
    $test->term_id = $latestTerm->id;
    $test->save();

    return response()->json(['message' => 'Test created successfully.']);
}

    
}
