<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAdminPermissions
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

        // Check if the user has a profile
        if ($user && $user->hasProfile()) {
            $profile = $user->profile;

            // Define the required permissions for the route
            $requiredPermissions = [
                'confirm_student' => 'permission_confirm_student',
                'confirm_admin' => 'permission_confirm_admin',
                'confirm_teacher' => 'permision_confirm_teacher',
                'create_lesson' => 'permission_create_lesson',
                'create_course' => 'permision_create_course',
                'create_class' => 'permision_create_class',
                'create_event' => 'permission_create_event',
                'confirm_staff' => 'permision_confirm_staff',
            ];

            // Check if admin is confirmed and has the required permission
            foreach ($requiredPermissions as $routeName => $permission) {
                if ($profile->role == 'school_owner' ||($profile->admin_confirmed && $profile->$permission)) {
                    return $next($request);
                }
            }
        }

        // If not, redirect with an error message
        return redirect('home')->with('error', 'You do not have the required permission.');
    }
}
