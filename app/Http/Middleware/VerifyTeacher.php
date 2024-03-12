<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user has the 'teacher' role (you can customize this based on your setup)
            if ($user->profile->role == 'teacher') {
                // Check if the teacher belongs to the school and has teacher_confirmed as true
                if ($user->school && $user->profile->teacher_confirmed) {
                    return $next($request);
                }
            }
        }

        // Redirect or respond with an error based on your application's needs
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}
