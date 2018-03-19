<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class Auth0Controller extends Controller
{

    protected $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        // dd(\Auth::check());
        $isLoggedIn = \Auth::check();
        return view('welcome')
          ->with('isLoggedIn', $isLoggedIn);
    }

    public function login()
    {
        return \App::make('auth0')->login(null, null, ['scope' => 'openid profile email'], 'code');
    }

    public function logout()
    {
        \Auth::logout();
        return  \Redirect::intended('/');
    }

    public function dump()
    {
        $isLoggedIn = \Auth::check();
        return view('dump')
          ->with('isLoggedIn', $isLoggedIn)
          ->with('user',\Auth::user()->getUserInfo())
          ->with('accessToken',\Auth::user()->getAuthPassword());
    }

    /**
     * Callback action that should be called by auth0, logs the user in.
     */
    public function callback()
    {
        // Get a handle of the Auth0 service (we don't know if it has an alias)
        $service = \App::make('auth0');
        // dd($service->getUser());

        // Try to get the user information
        $profile = $service->getUser();
        // Get the user related to the profile
        $auth0User = $this->userRepository->getUserByUserInfo($profile);

        if ($auth0User) {
            // If we have a user, we are going to log him in, but if
            // there is an onLogin defined we need to allow the Laravel developer
            // to implement the user as he wants an also let him store it.
            if ($service->hasOnLogin()) {
                $user = $service->callOnLogin($auth0User);
            } else {
                // If not, the user will be fine
                $user = $auth0User;
            }
            \Auth::login($user, $service->rememberUser());
        }

        return \Redirect::intended('/');
    }
}
