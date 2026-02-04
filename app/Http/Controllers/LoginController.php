<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
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

            $user = Auth::user();
            // Check if user has role and get the role name
            $role = $user->role ? strtolower($user->role->name) : '';

            if ($role === 'admin' || $role === 'hrd') {
                return redirect()->intended(route('dashboard'));
            } elseif ($role === 'karyawan') {
                return redirect()->route('attendance.index');
            } elseif ($role === 'tamu') {
                return redirect()->route('applicant.dashboard');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang anda masukkan salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
