<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Transfer;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use Carbon\Carbon;

class PaymentController extends Controller
{
    //
    public function showPackagePaymentPage(Request $request)
    {
        $user = auth()->user();
        $packageId = $request->input('package_id');
        $schoolId = $request->input('school_id');
        $price = $request->input('price');
        // dd($packageId);

        // Add your logic to display the payment page with the provided package ID and price
        // You might use a blade view or any other method to render the payment page

        return view('payment.school_payment', ['packageId' => $packageId, 'price' => $price, 'user'=>$user, 'school_id'=>$schoolId]);
    }

    public function setTransferSession(Request $request)
    {

        try {
            // return response()->json(['success' => true, 'payment_session_id' => sessioN()->get('payment_session')]);
            $request->session()->forget('payment_session');
            $request->session()->forget('payment_confirmation');
            $request->session()->forget('payment_session_expires_at');
            // $request->session()->forget('paid_for');
            // $request->session()->forget('id_paid_for');

            // $paid_for = session('paid_for');
            // $id_paid_for = session('id_paid_for');
            
            // Check if payment session doesn't already exist
            if (!$request->session()->has('payment_session')) {
                // Generate a unique payment_session_id
                $paymentSessionId = Str::random(10); // Adjust the length as needed
             
                // Store the payment_session_id, amount, and email in the session
                $payment_session = [
                    'payment_session_id' => $paymentSessionId,
                    // 'paid_for' => $paid_for,
                    // 'id_paid_for' => $id_paid_for,
                    'amount' => $request->input('amount_input'),
                    'email' => $request->input('email'),
                    'payment_marked' => false,
                    'paid_for'=>$request->input('paid_for'),
                    'payment_confirmed'=> false,
                    'payment_session_expires_at'=> now()->addMinutes(30),
                ];
                if( $request->input('id_paid_for')){
                    $payment_session['id_paid_for'] = $request->input('id_paid_for');
                 }
                 if( $request->input('package_id')){
                    $payment_session['package_id'] = $request->input('package_id');
                 }
                 
                 
     
    
                // Store the payment_session_id in the session (expires in 30 minutes)
                $request->session()->put('payment_session', $payment_session);
                // $request->session()->put('payment_confirmation', true);
                
                // Set a 30-minute session expiration (1800 seconds)
    
                return response()->json(['success' => true, 'payment_session' => $payment_session]);
            } else{
                $payment_session = $request->session()->get('payment_session');

                return response()->json(['success' => true, 'payment_session' => $payment_session, 'message' => 'Payment session already exists']);
            }
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message'=>$e->getMessage()]);
        }
    }
    
    public function removeTransferSession(Request $request)
    {
        try {
            // Remove the payment session data
            $request->session()->forget('payment_session');
            $request->session()->forget('payment_confirmation');
            $request->session()->forget('payment_session_expires_at');
    
            // Check for transfer success and set the response accordingly
            $transferSuccessful = $request->query('success', 0) == 1;
            if ($transferSuccessful == 1) {
                $message = "Transfer Successful";
                $message_type = 'success';
            }
            else{
                $message = "Transfer Not Successful";
                $message_type = 'error';
            }
            return redirect('home')->with($message_type, $message);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
    

    public function confirmTransfer(Request $request)
    {
        // $request->session()->forget('payment_session');
        //     $request->session()->forget('payment_confirmation');
        //     $request->session()->forget('payment_session_expires_at');
        // Retrieve the payment session data using the session ID
        $paymentSession = session()->get('payment_session');
        // return response()->json(['success' => false, 'message' => 'Invalid payment session', 'payment_session'=> $paymentSession]);

        if ($paymentSession) {
            // Payment is confirmed, create the order or perform necessary actions
            $confirmationLink = route('admin_confirm_payment', ['payment_session_id' => $paymentSession['payment_session_id']]);

        // Add the confirmation link to the payment session
            $paymentSession['confirmation_link'] = $confirmationLink;

            // Add the payment_marked and payment_confirmed flags to the payment session
            $paymentSession['payment_marked'] = true;
            $paymentSession['payment_confirmed'] = false;

            // Update the payment session data
            $request->session()->put('payment_session', $paymentSession);

            // Create a new Transfer record in the database
            $transfer = Transfer::create([
                'email' => $paymentSession['email'],
                'amount' => $paymentSession['amount'],
                'paid_for'=> $paymentSession['paid_for'],
                'payment_session_id' => $paymentSession['payment_session_id'],
                'id_paid_for' => $paymentSession['id_paid_for'],
                'package_id'=>$paymentSession['package_id'],
                'payment_marked' => true,
                'payment_confirmed' => false,
                'confirmation_link'=> $confirmationLink,
            ]);
            // dd($paymentSession['paid_for']);

            // Send an email notification to the admin
            $adminEmail = 'jonahuche600@gmail.com'; // Replace with the admin's email address
            Mail::to($adminEmail)->send(new PaymentConfirmationMail($transfer));

            return response()->json(['success' => true, 'transfer' => $transfer]);

            // Return a success response
        } else {
            // Invalid session ID or payment not found
            return response()->json(['success' => false, 'message' => 'Invalid payment session']);
        }
     }

    public function checkPaymentConfirmed($payment_session_id)
    {
        // Retrieve the payment session from your data source (e.g., database)
        $paymentSession = Transfer::where('payment_session_id', $payment_session_id)->first();

        if (!$paymentSession) {
            return response()->json(['success' => false, 'message' => 'Payment session not found']);
        }

        // Check if the admin has marked the payment as confirmed
        if ($paymentSession->payment_confirmed) {
            return response()->json(['success' => true, 'payment_confirmed' => true]);
        } else {
            return response()->json(['success' => true, 'payment_confirmed' => false]);
        }
    }


    public function userActivationPage(Request $request, $packageId)
    {
        // $request->session()->forget('payment_session');
        //     $request->session()->forget('payment_confirmation');
        //     $request->session()->forget('payment_session_expires_at');
        $user = auth()->user();
        $package = UserPackage::find($packageId);

        if (!$package) {
            return redirect()->back()->with('error', 'Package Not Found');
        }

        if ($package->name === 'Basic Package') {
            // Activate the Basic package for the user
            $user->active_package = true;
            $user->user_package_id = $package->id;

            // Set expected expiration based on package duration
            $user->expected_expiration = Carbon::now()->addDays($package->duration_in_days);

            // Save the changes to the user model
            $user->save();

            // Redirect to the profile route with a success message
            return redirect()->route('profile')->with('success', 'Activation successful');
        }

        // For other package types, display the activation page
        return view('payment.activate_user', ['package' => $package, 'user' => $user]);
    }



}
