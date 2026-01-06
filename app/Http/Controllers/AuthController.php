<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'super_admin') {
                return redirect()->route('admin.dashboard');
<<<<<<< HEAD
            } elseif ($user->role === 'owner') {
=======
            } elseif (in_array($user->role, ['owner', 'cashier'])) {
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
                return redirect()->route('owner.dashboard');
            }

            // For now redirect tenant owners to landing (or later tenant dashboard)
            return redirect()->route('landing.index')->with('success', 'You are logged in!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
