<?php

namespace App\Http\Controllers;
use App\Models\School;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\WithdrawalRequestMail;
use App\Mail\WithdrawalRequestUserMail;
use App\Models\WithdrawalRequest;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WalletController extends Controller
{
    //
    public function userWallet($userId)
    {
        $teacher = User::findOrFail($userId);

        // Check if the user has a wallet
        if (!$teacher->wallet) {
            return redirect()->back()->with('error', 'You do not have a wallet yet. Upgrade your package to get a wallet.');
        }

        // Check if the user's package has expired
        $expirationDate = Carbon::parse($teacher->expected_expiration);
        if (Carbon::now()->greaterThan($expirationDate)) {
            return redirect()->back()->with('error', 'Your package has expired. Please update/renew your package.');
        }

        $walletBalance = $teacher->wallet->balance;

        // Retrieve the last 14 transactions and paginate them
        $transactions = $teacher->lessonTransactions()->orderBy('created_at', 'desc')->paginate(14);

        return view('teacher.wallet', compact('teacher', 'walletBalance', 'transactions'));
    }

    
    public function schoolWallet($schoolId)
    {
        $school = School::findOrFail($schoolId);
    
        // Check if the authenticated user is the school owner
        if (Auth::id() !== $school->school_owner_id) {
            abort(403, 'Unauthorized action.');
        }
    
        $walletBalance = $school->wallet->balance;
    
        // Retrieve the last 14 transactions and paginate them
        $transactions = $school->lessonTransactions()->orderBy('created_at', 'desc')->paginate(14);
    
        return view('school.wallet', compact('school', 'walletBalance', 'transactions'));
    }
    public function applyForWithdrawal(Request $request, $schoolId)
    {
        $school = School::findOrFail($schoolId);
    
        // Check if the authenticated user is the school owner
        if (Auth::id() !== $school->school_owner_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }
    
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $school->wallet->balance,
        ]);
    
        $amount = $request->input('amount');
    
        if ($school->wallet->balance < $amount) {
            return response()->json(['message' => 'Insufficient balance.'], 400);
        }
    
        // Generate a unique token
        $token = Str::random(60);
    
        // Save the withdrawal request to the database
        $withdrawalRequest = new WithdrawalRequest();
        $withdrawalRequest->school_id = $school->id;
        $withdrawalRequest->amount = $amount;
        $withdrawalRequest->token = $token;
        $withdrawalRequest->save();
    
        // Subtract the withdrawal amount from the school's wallet balance
        $school->wallet->balance -= $amount;
        $school->wallet->save();
    
        // Send the token to the user via email
        Mail::to($school->email)->send(new WithdrawalRequestMail($withdrawalRequest));
    
        return response()->json(['message' => 'Withdrawal request submitted successfully.']);
    }

    // applyForUserWithdrawal
    public function applyForUserWithdrawal(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
    
    
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $user->wallet->balance,
        ]);
    
        $amount = $request->input('amount');
    
        if ($user->wallet->balance < $amount) {
            return response()->json(['message' => 'Insufficient balance.'], 400);
        }
    
        // Generate a unique token
        $token = Str::random(60);
    
        // Save the withdrawal request to the database
        $withdrawalRequest = new WithdrawalRequest();
        $withdrawalRequest->user_id = $user->id;
        $withdrawalRequest->amount = $amount;
        $withdrawalRequest->token = $token;
        $withdrawalRequest->save();
    
        // Subtract the withdrawal amount from the school's wallet balance
        // $user->wallet->balance -= $amount;
        $user->wallet->save();
    
        // Send the token to the user via email
        Mail::to($user->email)->send(new WithdrawalRequestUserMail($withdrawalRequest));
    
        return response()->json(['message' => 'Withdrawal request submitted successfully.']);
    }
    
    
    public function updateAccount($token)
    {
        $withdrawalRequest = WithdrawalRequest::where('token', $token)->firstOrFail();
        $school = $withdrawalRequest->school;
    
        // Check if the authenticated user is the school owner
        if (Auth::id() !== $school->school_owner_id) {
            abort(403, 'Unauthorized action.');
        }
    
        return view('school.update_account', compact('school','withdrawalRequest'));
    }
    
    

    public function saveAccount(Request $request)
    {
        // Validate the request data
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'token' => 'required|string|exists:withdrawal_requests,token',
        ]);
    
        // Find the withdrawal request by token
        $withdrawalRequest = WithdrawalRequest::where('token', $request->input('token'))->firstOrFail();
        $school = $withdrawalRequest->school;
    
        // Check if the authenticated user is the school owner
        if (Auth::id() !== $school->school_owner_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }
    
        // Update the withdrawal request with the new account details
        $withdrawalRequest->account_name = $request->input('account_name');
        $withdrawalRequest->account_number = $request->input('account_number');
        $withdrawalRequest->bank_name = $request->input('bank_name');
        $withdrawalRequest->completed = false;
        $withdrawalRequest->processed_at = now();
        $withdrawalRequest->save();
    
        // Return a JSON response
        return response()->json(['message' => 'Account details updated successfully.']);
    }

    public function updateUserAccount($token)
    {
        try {
            $withdrawalRequest = WithdrawalRequest::where('token', $token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home')->with('error', 'Invalid token.');
        }

        $user = $withdrawalRequest->user;

        // Check if the authenticated user matches the user of the withdrawal request
        if (Auth::id() !== $user->id) {
            return redirect('home')->with('error', 'Unauthorized action.');
        }

        return view('teacher.update_account', compact('user', 'withdrawalRequest'));
    }

    
    public function saveUserAccount(Request $request)
    {
        // Validate the request data
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'token' => 'required|string|exists:withdrawal_requests,token',
        ]);
    
        // Find the withdrawal request by token
        $withdrawalRequest = WithdrawalRequest::where('token', $request->input('token'))->firstOrFail();
        $user = $withdrawalRequest->user;
    
        // Check if the authenticated user matches the user of the withdrawal request
        if (Auth::id() !== $user->id) {
            return redirect('home')->with('error', 'Unauthorized action.');
        }
    
        // Update the withdrawal request with the new account details
        $withdrawalRequest->account_name = $request->input('account_name');
        $withdrawalRequest->account_number = $request->input('account_number');
        $withdrawalRequest->bank_name = $request->input('bank_name');
        $withdrawalRequest->completed = false;
        $withdrawalRequest->processed_at = now();
        $withdrawalRequest->save();
    
        // Return a JSON response
        return response()->json(['message' => 'Account details updated successfully.']);
    }

}
