<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

// Show register form
// @route get / register
    public function register() : View {
        return View('auth.register');
    }

    // Show Store user in database 
    // @route POST / register
    public function store(Request $request) : RedirectResponse {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password'=> 'required|string|min:8|confirmed',     

        ]);

        //Hash the password
        $validatedData['password'] = Hash::make($validatedData['password']);

        //Create user
        $user = User::create($validatedData);

        return redirect()->route('login')->with('success','You are registered and can log in!');

    }
}
