<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validate incoming login request data
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            // User is authenticated, store user info in session
            $user = Auth::user();

            // Store user details in session
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            // You can store more user info in session as needed

            // Redirect to intended page or dashboard
            return redirect()->route('auth.login');
        } else {
            // Authentication failed
            return back()->withErrors([
                'email' => 'The provided credentials are incorrect.',
            ]);
        }
    }
}
