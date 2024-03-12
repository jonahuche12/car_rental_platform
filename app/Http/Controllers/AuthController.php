<?php


namespace App\Http\Controllers;
// AuthController.php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function showRegistrationForm()
    {
        return view('register'); // Assuming you have a register.blade.php view file
    }

    public function showLoginForm()
    {
        return view('login'); // Assuming you have a login.blade.php view file
    }

    public function showConfirmEmailForm($token)
    {
        // Add logic to handle showing the confirm email form if needed
        return view('confirm_email'); // Assuming you have a confirm_email.blade.php view file
    }

    public function register(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'confirmation_token' => Str::random(32), // Generate a confirmation token
        ]);

        // Send confirmation email to the user (you can use Laravel's built-in Mail functionality or a package like Laravel Mailer)

        // Return a response
        return response()->json(['message' => 'Registration successful. Please check your email for confirmation.'], 201);
    }

    public function login(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Authenticate user
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate token
        $token = auth()->user()->createToken('authToken')->accessToken;

        // Return a response with the token
        return response()->json(['token' => $token]);
    }

    public function confirmEmail($token)
    {
        // Find the user with the given confirmation token
        $user = User::where('confirmation_token', $token)->first();

        // Check if user exists
        if (!$user) {
            return response()->json(['message' => 'Invalid confirmation token'], 404);
        }

        // Update the user's email_verified_at field
        $user->update([
            'email_verified_at' => now(),
            'confirmation_token' => null,
        ]);

        // Return a response
        return response()->json(['message' => 'Email confirmed successfully']);
    }

}

