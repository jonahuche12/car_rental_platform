<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;

use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
 
    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function handleGoogleCallback()
    {
        try {
            $google_user = Socialite::driver('google')->user();
            $user = User::where('google_id', $google_user->getId())->first();
            
            if (!$user) {
                $new_user = User::create([
                    'first_name' => $google_user->user['given_name'] ?? '', // Access first name using user['given_name']
                    'last_name' => $google_user->user['family_name'] ?? '', // Access last name using user['family_name']
                    'middle_name' => $google_user->user['middle_name'] ?? '',
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                ]);
                \Auth::login($new_user);
    
                return redirect()->intended('home');
            } else {
                \Auth::login($user);
    
                return redirect()->intended('home');
            }
        } catch (\Throwable $th) {
            dd("Something Went wrong " . $th->getMessage());
        }
    }
    
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
 
    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (\Throwable $th) {
            //throw $th;
        }
 
        // $user->token;
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }
 
    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();
 
        // $user->token;
    }

    public function redirectToApple()
    {
        return Socialite::driver('apple')->redirect();
    }
 
    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleAppleCallback()
    {
        $user = Socialite::driver('apple')->user();
 
        // $user->token;
    }
}
