<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ActivationEmail;
use App\Models\SchoolPackage;
use App\Models\UserPackage;
use App\Mail\ConfirmTransferMail;
use App\Models\Transfer;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\Curriculum;
use Illuminate\Support\Facades\Mail;
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
        // dd($paid_for);

        // $request->validate([
        //     'amount' => 'required|numeric',
        //     // Add any other validation rules as per your requirements
        // ]);

        $amount = $request->input('amount');
       
        $transfer = Transfer::where('payment_session_id', $payment_session_id)->first();



        if ($transfer) {



                if ($paid_for === 'user_activation') {
                    // Activate user package
                    $user = User::where('id', $transfer->id_paid_for)->first();
                    // dd($user);
                    $user->active_package = true;
                    $user->expected_expiration = now()->addDays($user->userPackage->duration_in_days);
                    $user->save();
                } elseif ($paid_for === 'school_activation') {

                    $user = $school->school_owner;
                    // Activate school package
                    $school = $payment->school;
                    $school->is_active = true;
                    $school->school_expected_expiration = now()->addDays($school->schoolPackage->duration_in_days);
                    $school->save();
        
                    // Create a wallet for the School
                    $wallet = new Wallet();
                    $wallet->school_id = $school->id;
                    $wallet->balance = 0; // Set initial balance as 0 or any other default value
                    $wallet->save();
                }

            $payment = DB::table('payments')->insertGetId([
                'user_id' => $user->id,
                'amount' => $transfer['amount'],
                // 'reference_id'=>$request->merchant_id,
                'paystack_reference' => $transfer['payment_session_id'],
                'paid_for' => $paid_for,
                // 'created_at' => now(),
                'paid_at'=>  now(),
                'channel' =>"transfer",
                'currency' => "NGN",
                'ip_addr' => $request->ip(),
                'successful' => true,
                'reference_id'=> $transfer['id_paid_for'],
                // Add other fields as needed
            ]);

                $payment = Payment::find($payment);


                if ($user ) {
                    
                    // Send email to the user
                    Mail::to($user->email)->send(new ActivationEmail($user));
                }
               
                session()->forget('payment_session');

            // Redirect to viewprofile route with the user ID associated with the payment
            return redirect()->route('home')->with('success', "Package has been activated.");
        } else {
            // Payment not successful, handle appropriately
            return redirect()->back()->with('error', 'Payment not Successful');
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
            $resizedImage = Image::make(storage_path('app/public/' . $imagePath))->fit(500, 500);
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

    
}
