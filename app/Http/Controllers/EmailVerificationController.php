<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Tampilkan form verifikasi email
     */
    public function showVerifyForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika sudah diverifikasi, redirect ke dashboard
        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-email');
    }

    /**
     * Proses verifikasi kode email
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user->verification_code === $request->verification_code) {
            $user->email_verified_at = now();
            $user->verification_code = null; // Clear code after use
            $user->save();

            return redirect()->route('dashboard')
                ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
        }

        return back()->withErrors(['verification_code' => 'Kode verifikasi tidak valid.']);
    }

    /**
     * Kirim ulang kode verifikasi
     */
    public function resend(Request $request)
    {
        $user = Auth::user();
        
        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        $code = strtoupper(substr(md5(rand()), 0, 6));
        $user->verification_code = $code;
        $user->save();

        // Send Email
        Mail::to($user->email)->send(new \App\Mail\EmailVerificationMail(
            $user->name,
            $user->role->name ?? 'User',
            $code
        ));

        return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }
}
