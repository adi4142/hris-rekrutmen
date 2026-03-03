<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class PasswordChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan form ganti password paksa
     */
    public function showChangeForm()
    {
        $user = auth()->user();
        if (!$user->needs_password_change) {
            return redirect()->route('dashboard');
        }

        return view('auth.change-password');
    }

    /**
     * Proses ganti password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->needs_password_change = 0; // Reset flag
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Password berhasil diperbarui. Selamat datang di sistem!');
    }
}
