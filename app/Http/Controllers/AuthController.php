<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        // If already logged in, redirect them to their home base
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Check if "Remember Me" was checked in the form
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->redirectUserByRole(Auth::user());
        }

        return back()
            ->withErrors(['username' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('username')); // Keeps the username in the box for the user
    }

    /**
     * Centralized logic to send users to the right place
     */
    private function redirectUserByRole($user) {
        $role = (int) $user->role;

        if ($role === 1) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('student.exams');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
