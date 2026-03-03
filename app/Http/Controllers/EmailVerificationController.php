<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Tampilkan form verifikasi email (untuk manual input jika masih diinginkan/fallback)
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
     * Proses verifikasi kode email (untuk input manual)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user->email_verification_code === $request->verification_code) {
            $user->email_verified_at = now();
            $user->email_verification_code = null; // Clear code after use
            $user->status = 'active'; // Ensure status is active
            $user->save();

            return redirect()->route('dashboard')
                ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
        }

        return back()->withErrors(['verification_code' => 'Kode verifikasi tidak valid.']);
    }

    /**
     * Proses verifikasi via LINK (Click Link)
     */
    public function verifyLink($token)
    {
        $user = User::where('email_verification_code', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Tautan verifikasi tidak valid atau sudah kedaluwarsa.');
        }

        $user->email_verified_at = now();
        $user->email_verification_code = null;
        $user->status = 'active';
        $user->save();

        // Jika user sudah login, arahkan ke dashboard. Jika belum, suruh login.
        if (Auth::check() && Auth::id() === $user->user_id) {
            return redirect()->route('login')
                ->with('success', 'Email berhasil diverifikasi! Akun Anda kini aktif.');
        }

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Akun Anda kini aktif. Silakan masuk.');
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

        // Generate new token
        $token = \Illuminate\Support\Str::random(64);
        $user->email_verification_code = $token;
        $user->save();

        // Send Email
        Mail::to($user->email)->send(new \App\Mail\EmailVerificationMail(
            $user->name,
            $user->role->name ?? 'User',
            $token
        ));

        return back()->with('success', 'Tautan verifikasi baru telah dikirim ke email Anda.');
    }
}
