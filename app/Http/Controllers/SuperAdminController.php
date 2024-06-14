<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ActivationEmail;
use App\Models\SchoolPackage;
use App\Models\Test;
use App\Models\Answer;
use App\Models\Question;
use App\Models\School;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\UserPackage;
use App\Mail\ConfirmTransferMail;
use App\Models\Transfer;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\WithdrawalRequest;
use App\Events\WithdrawalFailedQueued; // Import the event class
use Illuminate\Support\Str;

use App\Models\Curriculum;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalCompleted;
use App\Mail\UpdateAccountDetails;
use App\Mail\WithdrawalFailed;
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
                // Update the user package
                $user->user_package_id = $transfer->package_id;
                $user->profile->increment('school_connects', 200);
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
                $school->school_package_id = $transfer->package_id;
                $school->is_active = true;
                $school->school_expected_expiration = now()->addDays($school->schoolPackage->duration_in_days);
                $school->save();
    
                // Create wallet for the school
                $wallet = new Wallet();
                $wallet->school_id = $school->id;
                $wallet->balance = 0; // Set initial balance as 0 or any default value
                $wallet->save();
    
            } elseif ($paid_for === 'school_connects') {
                // Activate school package
                $user = User::findOrFail($transfer->id_paid_for);
                if ($transfer->amount == 500) {
                    $connect = 90;
                } elseif ($transfer->amount == 1000) {
                    $connect = 210;
                } elseif ($transfer->amount == 2000) {
                    $connect = 450;
                } elseif ($transfer->amount == 3000) {
                    $connect = 1000;
                } else {
                    $connect = 2000;
                }
                // Update user's school connects
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
    


    public function storeTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:cognitive,class_level',
            'class_level' => 'required|string|max:255',
            'max_no_of_questions' => 'required|integer',
            'complete_score' => 'required|integer',
            'duration' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
    
        $latestAcademicSession = AcademicSession::latest()->first();
        $latestTerm = Term::latest()->first();
    
        // Check for existing test with the same type and term_id
        $existingTest = Test::where('type', $request->type)
                            ->where('academic_session_id', $latestAcademicSession->id)
                            ->where('class_level', $request->class_level)
                            ->where('term_id', $latestTerm->id)
                            ->first();
    
        if ($existingTest) {
            return response()->json(['message' => "You have already created a $request->type Test For this Term"], 400);
        }
    
        $test = new Test();
        $test->title = $request->title;
        $test->type = $request->type;
        $test->class_level = $request->class_level;
        $test->max_no_of_questions = $request->max_no_of_questions;
        $test->complete_score = $request->complete_score;
        $test->duration = $request->duration;
        $test->academic_session_id = $latestAcademicSession->id;
        $test->term_id = $latestTerm->id;
        $test->save();
    
        return response()->json(['message' => 'Test created successfully.']);
    }
    public function showTest($id)
    {
        $test = Test::findOrFail($id);
        return view('super_admin.show_test', compact('test'));
    }
    public function storeQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_id' => 'required|exists:tests,id',
            'question' => 'required|string',
            'answer_type' => 'required|string|in:radio,checkbox',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
    
        $question = new Question();
        $question->test_id = $request->test_id;
        $question->question = $request->question;
        $question->answer_type = $request->answer_type;
    
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('questions', 'public');
                $imagePaths[] = $path;
            }
            $question->images = $imagePaths; // The mutator will handle conversion to a comma-separated string
        }
    
        $question->save();
    
        return response()->json(['message' => 'Question added successfully.'], 200);
    }
    
    public function updateQuestion(Request $request, $id)
    {
        // Find the question or fail if not found
        $question = Question::findOrFail($id);

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer_type' => 'required|string|in:radio,checkbox',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // If validation fails, return a 400 response with the first error message
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        // Update the question's text and answer type
        $question->question = $request->question;
        $question->answer_type = $request->answer_type;

        // Get existing images as an array
        $existingImages = $question->images;

        // Handle new images upload
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('questions', 'public');
                $existingImages[] = $path;
            }
            // Update the question's images
            $question->images = $existingImages;
        }

        // Save the updated question
        $question->save();

        // Return a success response
        return response()->json(['message' => 'Question updated successfully.', 'images' => $question->images], 200);
    }

    
    public function getQuestionImages($id)
    {
        $question = Question::findOrFail($id);
        return response()->json(['images' => $question->images], 200);
    }
    public function getAnswerImages($id)
    {
        $answer = Answer::findOrFail($id);
        return response()->json(['images' => $answer->images], 200);
    }
    public function removeQuestionImage(Request $request, $id)
    {
        // Find the question by ID, or fail with a 404 error if not found
        $question = Question::findOrFail($id);
    
        // Validate the incoming request to ensure 'image' is present and is a string
        $validator = Validator::make($request->all(), [
            'image' => 'required|string'
        ]);
    
        // If validation fails, return a 400 error with the first validation error message
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
    
        // Get the image to remove from the request
        $imageToRemove = $request->image;
    
        // Get the existing images of the question as an array
        $existingImages = $question->images;
    
        // Check if the image to remove exists in the existing images array
        if (($key = array_search($imageToRemove, $existingImages)) !== false) {
            // Remove the image from the array
            unset($existingImages[$key]);
            // Reindex the array to maintain the proper structure
            $existingImages = array_values($existingImages);
    
            // Delete the image from the storage
            Storage::disk('public')->delete($imageToRemove);
    
            // Update the question's images with the new array
            $question->images = $existingImages;
    
            // Save the updated question to the database
            $question->save();
    
            // Return a success response
            return response()->json(['message' => 'Image removed successfully.', 'images' => $question->images], 200);
        }
    
        // If the image was not found in the existing images array, return a 404 error
        return response()->json(['message' => 'Image not found.'], 404);
    }
    public function destroyQuestion($id)
    {
        $question = Question::findOrFail($id);
        
        // Delete associated images
        foreach ($question->images as $image) {
            Storage::disk('public')->delete($image); // Ensure you're using the correct disk
        }
        
        $question->delete();
        
        return response()->json(['message' => 'Question and associated images deleted successfully.']);
    }

    public function storeAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
            'is_correct' => 'required|boolean',
            'score_point'=>'nullable|integer',
            'answer_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $answer = new Answer();
        $answer->question_id = $request->question_id;
        $answer->answer = $request->answer;
        $answer->score_point = $request->score_point;
        $answer->is_correct = $request->is_correct;

        if ($request->hasFile('answer_images')) {
            $imagePaths = [];
            foreach ($request->file('answer_images') as $image) {
                $path = $image->store('answers', 'public');
                $imagePaths[] = $path;
            }
            $answer->images = $imagePaths;
        }

        $answer->save();

        return response()->json(['message' => 'Answer added successfully.'], 200);
    }


    public function removeAnswerImage(Request $request, $id)
    {
        // Find the question by ID, or fail with a 404 error if not found
        $answer = Answer::findOrFail($id);
    
        // Validate the incoming request to ensure 'image' is present and is a string
        $validator = Validator::make($request->all(), [
            'image' => 'required|string'
        ]);
    
        // If validation fails, return a 400 error with the first validation error message
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
    
        // Get the image to remove from the request
        $imageToRemove = $request->image;
    
        // Get the existing images of the question as an array
        $existingImages = $answer->images;
    
        // Check if the image to remove exists in the existing images array
        if (($key = array_search($imageToRemove, $existingImages)) !== false) {
            // Remove the image from the array
            unset($existingImages[$key]);
            // Reindex the array to maintain the proper structure
            $existingImages = array_values($existingImages);
    
            // Delete the image from the storage
            Storage::disk('public')->delete($imageToRemove);
    
            // Update the question's images with the new array
            $answer->images = $existingImages;
    
            // Save the updated question to the database
            $answer->save();
    
            // Return a success response
            return response()->json(['message' => 'Image removed successfully.', 'images' => $answer->images], 200);
        }
    
        // If the image was not found in the existing images array, return a 404 error
        return response()->json(['message' => 'Image not found.'], 404);
    }

    public function updateAnswer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'answer' => 'required|string',
            'edit_is_correct' => 'required|boolean',
            'edit_score_point'=>'nullable|integer',
            'new_answer_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $answer->answer = $request->answer;
        $answer->score_point = $request->edit_score_point;
        $answer->is_correct = $request->edit_is_correct;

        $existingImages = $answer->images;

        if ($request->hasFile('new_answer_images')) {
            foreach ($request->file('new_answer_images') as $image) {
                $path = $image->store('answers', 'public');
                $existingImages[] = $path;
            }
            $answer->images = $existingImages;
        }

        $answer->save();

        return response()->json(['message' => 'Answer updated successfully.', 'images' => $answer->images], 200);
    }

    public function destroyAnswer($id)
    {
        $answer = Answer::findOrFail($id);
        
        // Delete associated images
        foreach ($answer->images as $image) {
            Storage::disk('public')->delete($image); // Ensure you're using the correct disk
        }
        
        $answer->delete();
        
        return response()->json(['message' => 'Answer and associated images deleted successfully.']);
    }

    public function updateTest(Request $request, Test $test)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:cognitive,class_level',
            'max_no_of_questions' => 'required|integer|min:1',
            'complete_score' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
        ]);

        // Update the test with the new data
        $test->update([
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'max_no_of_questions' => $request->input('max_no_of_questions'),
            'complete_score' => $request->input('complete_score'),
            'duration' => $request->input('duration'),
        ]);

        // Return a JSON response indicating success
        return response()->json([
            'message' => 'Test updated successfully!',
            'test' => $test,
        ]);
    }
    public function destroyTest(Test $test)
    {
        // Delete related questions and answers
        $test->questions->each(function($question) {
            $question->answers()->delete();
            $question->delete();
        });

        // Delete the test
        $test->delete();

        // Return a JSON response indicating success
        return response()->json([
            'message' => 'Test deleted successfully!',
        ]);
    }
    public function manageWithdrawals(Request $request)
    {
        $completedWithdrawals = WithdrawalRequest::where('completed', true)->paginate(4, ['*'], 'completed_page');
        $notCompletedWithdrawals = WithdrawalRequest::where('completed', false)->paginate(4, ['*'], 'not_completed_page');
    
        // Set the default active tab based on the current page parameter
        if ($request->has('not_completed_page')) {
            $request->session()->put('active_tab', 'not-completed');
        } else {
            $request->session()->put('active_tab', 'completed');
        }
    
        return view('super_admin.withdrawals', compact('completedWithdrawals', 'notCompletedWithdrawals'));
    }
    
    public function updateActiveTab(Request $request)
    {
        $request->session()->put('active_tab', $request->input('active_tab'));
        return response()->json(['status' => 'success']);
    }
    
    public function markAsCompleted(Request $request)
    {
        $ids = $request->ids;
        $action = $request->action;
    
        if ($action == 'complete') {
            foreach ($ids as $id) {
                $withdrawal = WithdrawalRequest::find($id);
                if ($withdrawal) {
                    if (empty($withdrawal->account_name) || empty($withdrawal->account_number) || empty($withdrawal->bank_name)) {
                        // Generate a unique token for updating account details
                        $token = Str::random(60);
                        $withdrawal->update(['token' => $token]);
    
                        // Queue the email notification to update account details
                        if ($withdrawal->user_id) {
                            Mail::to($withdrawal->user->email)->queue(new UpdateAccountDetails($withdrawal));
                        } elseif ($withdrawal->school_id) {
                            Mail::to($withdrawal->school->schoolOwner->email)->queue(new UpdateAccountDetails($withdrawal));
                        }
                    } else {
                        $withdrawal->completed = true;
                        $withdrawal->processed_at = now();
                        $withdrawal->save();
    
                        // Queue the email notification
                        if ($withdrawal->user_id) {
                            Mail::to($withdrawal->user->email)->queue(new WithdrawalCompleted($withdrawal));
                        } elseif ($withdrawal->school_id) {
                            Mail::to($withdrawal->school->schoolOwner->email)->queue(new WithdrawalCompleted($withdrawal));
                        }
                    }
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Selected withdrawals have been processed.']);
        } elseif ($action == 'fail') {
            $withdrawals_id = [];
            foreach ($ids as $id) {
                $withdrawal = WithdrawalRequest::find($id);
                if ($withdrawal) {
                    // Add amount back to user or school wallet
                    if ($withdrawal->user_id) {
                        $user = User::find($withdrawal->user_id);
                        $user->wallet->balance += $withdrawal->amount;
                        $user->save();
                        $user->wallet->save();
    
                        // Queue the email notification
                        Mail::to($user->email)->queue(new WithdrawalFailed($withdrawal));
                        event(new WithdrawalFailedQueued($withdrawal));
                    } elseif ($withdrawal->school_id) {
                        $school = School::find($withdrawal->school_id);
                        $school->wallet->balance += $withdrawal->amount;
                        $school->save();
                        $school->wallet->save();
    
                        // Queue the email notification
                        Mail::to($school->schoolOwner->email)->queue(new WithdrawalFailed($withdrawal));
                        event(new WithdrawalFailedQueued($withdrawal));
                    }
                    $withdrawals_id[] = $withdrawal->id;
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Selected withdrawals have been marked as failed and amounts returned to wallets.',
                'withdrawals_id' => $withdrawals_id
            ]);
        }
    
        return response()->json(['status' => 'error', 'message' => 'Invalid action.']);
    }
    

}
