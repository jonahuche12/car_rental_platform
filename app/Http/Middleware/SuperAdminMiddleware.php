<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
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
        if (auth()->check()) {
            // Check if the authenticated user has the role of super_admin
            if (auth()->user()->profile && auth()->user()->profile->role === 'super_admin') {
                return $next($request);
            }
        }

        // Redirect to home or show an unauthorized page
        return redirect('/home')->with('error', 'Unauthorized access');
    }
}
