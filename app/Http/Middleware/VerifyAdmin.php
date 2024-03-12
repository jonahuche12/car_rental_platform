<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAdmin
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
        $user = auth()->user();

        // Check if the user is authenticated and has a profile
        if ($user && $user->hasProfile() && ($user->profile->admin_confirmed || $user->profile->role== 'school_owner')) {
            return $next($request);
        }

        // If not, redirect with an error message or do any other action
        return redirect()->route('home')->with('error', 'You do not have the required permission.');
    }
}
