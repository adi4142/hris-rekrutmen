<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\User;
use Carbon\Carbon;

/**
 * ForgotPasswordController
 * 
 * Controller untuk alur lupa password:
 * 1. User masukkan email
 * 2. Sistem kirim link verifikasi ke email
 * 3. User klik link → email terverifikasi
 * 4. User bisa ganti password
 */
class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form input email (lupa password)
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link verifikasi ke email user
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem kami.',
        ]);

        // Hapus token lama untuk email ini
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Generate token baru
        $token = Str::random(64);

        // Simpan token di database
        DB::table('password_resets')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'is_verified' => false,
            'created_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addHours(1), // Token berlaku 1 jam
        ]);

        // Kirim email verifikasi
        $verifyUrl = route('password.verify.email', [
            'token' => $token,
            'email' => $request->email,
        ]);

        try {
            Mail::to($request->email)->send(new \App\Mail\PasswordResetMail(
                User::where('email', $request->email)->first()->name,
                $verifyUrl
            ));
        } catch (\Exception $e) {
            // Jika email gagal kirim (misal: belum konfigurasi SMTP),
            // tampilkan link langsung untuk development
            return back()->with('success', 'Link verifikasi telah dikirim ke email Anda. Silakan cek inbox atau folder spam.')
                         ->with('dev_link', $verifyUrl);
        }

        return back()->with('success', 'Link verifikasi telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
    }

    /**
     * Verifikasi email dari link yang dikirim
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
        ]);

        // Cari record password reset
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Link verifikasi tidak valid atau sudah kadaluarsa.']);
        }

        // Cek apakah token sudah expired
        if (Carbon::parse($resetRecord->expires_at)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Link verifikasi sudah kadaluarsa. Silakan kirim ulang.']);
        }

        // Verifikasi token
        if (!Hash::check($request->token, $resetRecord->token)) {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Link verifikasi tidak valid.']);
        }

        // Tandai email sebagai terverifikasi
        DB::table('password_resets')
            ->where('email', $request->email)
            ->update(['is_verified' => true]);

        // Redirect ke form reset password
        return redirect()->route('password.reset.form', [
            'email' => $request->email,
        ])->with('success', 'Email berhasil diverifikasi. Silakan buat password baru.');
    }

    /**
     * Tampilkan form reset password (hanya jika email sudah verified)
     */
    public function showResetForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Akses tidak valid.']);
        }

        // Cek apakah email sudah diverifikasi
        $resetRecord = DB::table('password_resets')
            ->where('email', $email)
            ->where('is_verified', true)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Anda harus memverifikasi email terlebih dahulu.']);
        }

        // Cek apakah token sudah expired
        if (Carbon::parse($resetRecord->expires_at)->isPast()) {
            DB::table('password_resets')->where('email', $email)->delete();
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Sesi sudah kadaluarsa. Silakan kirim ulang.']);
        }

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Proses reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        // Cek sekali lagi apakah email sudah verified (keamanan berlapis)
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('is_verified', true)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Sesi tidak valid. Silakan ulangi proses.']);
        }

        // Cek apakah token sudah expired
        if (Carbon::parse($resetRecord->expires_at)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Sesi sudah kadaluarsa. Silakan kirim ulang.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus record password reset
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
