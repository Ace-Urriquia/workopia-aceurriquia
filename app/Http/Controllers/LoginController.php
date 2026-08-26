<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //  show login form
    // @route GET/login
    public function login() : View {
        return view('auth.login');
    }

    // Authenticate user
    // @route POST / login

     public function authenticate(Request $request) : RedirectResponse {
        $credentials = $request->validate([  
            'email' => 'required|string|email|max:100',
            'password'=> 'required|string',     

        ]);

        // Attemp to authenticate user

        if(Auth::attempt($credentials)){
            //Regenerate the session to prevent fixation attacks
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success','You are now logged in!');
        }

        //if auth fails, redirect back with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records'
        ])->onlyInput('email');
        

    }

    //@desc LOGOUT USER
    //@route POST / logout
    public function logout(Request $request) : RedirectResponse {
        Auth::logout();

        // invalidate session

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


}
