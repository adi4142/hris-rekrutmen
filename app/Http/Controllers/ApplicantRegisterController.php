<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApplicantRegisterController extends Controller
{
    /**
     * Tampilkan form registrasi khusus pelamar
     */
    public function showRegistrationForm()
    {
        // Pastikan role Pelamar ada
        $role = Role::where('name', 'Pelamar')->first();
        if (!$role) {
            // Jika tidak ada, coba cari role 'Tamu' atau buat baru
            $role = Role::where('name', 'Tamu')->first();
            if (!$role) {
                $role = Role::create([
                    'name' => 'Pelamar',
                    'description' => 'Role untuk pelamar lowongan pekerjaan'
                ]);
            }
        }

        return view('auth.applicant-register');
    }

    /**
     * Proses registrasi pelamar
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Ambil role Pelamar
        $role = Role::where('name', 'Pelamar')->first();
        if (!$role) {
            $role = Role::where('name', 'Tamu')->first();
        }

        if (!$role) {
            return back()->withErrors(['error' => 'Sistem belum siap. Role Pelamar tidak ditemukan.']);
        }

        $token = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => $role->roles_id,
            'email_verification_code' => $token,
            'status' => 'inactive', // Inactive until verified
            'is_role_verified' => 1, // Pelamar tidak butuh license code
        ]);

        // Kirim Email Verifikasi dengan Link
        try {
            Mail::to($user->email)->send(new \App\Mail\EmailVerificationMail(
                $user->name,
                $role->name,
                $token
            ));
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email verifikasi: " . $e->getMessage());
        }

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk mengaktifkan akun.');
    }
}
