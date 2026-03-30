<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (auth()->check()) {
            return $this->redirectToDashboard();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            $user = auth()->user();

            // Cek status akun
            if ($user->status == 'suspended') {
                auth()->logout();
                return redirect('/login')->withErrors([
                    'email' => 'Akun Anda telah ditangguhkan. Silakan hubungi Administrator.',
                ]);
            }

            if ($user->status == 'inactive') {
                $token = Str::random(64);
                $user->email_verification_code = $token;
                $user->save();

                try {
                    Mail::to($user->email)->send(new EmailVerificationMail(
                        $user->name,
                        $user->role->name ?? 'User',
                        $token
                    ));
                    $emailInfo = 'Email verifikasi telah dikirim ke ' . $user->email . '. Silakan cek inbox/spam Anda.';
                } catch (\Exception $e) {
                    $emailInfo = 'Gagal mengirim email verifikasi. Hubungi administrator.';
                }

                auth()->logout();
                return redirect('/login')
                    ->with('verification_sent', $emailInfo)
                    ->withErrors(['email' => 'Akun Anda belum aktif. ' . $emailInfo]);
            }

            $roleName = $user->role ? strtolower(str_replace(' ', '', $user->role->name)) : '';

            // Hanya Admin & HRD yang boleh login lewat halaman ini
            if (!in_array($roleName, ['admin', 'hrd'])) {
                auth()->logout();
                return redirect('/login')->withErrors([
                    'email' => 'Halaman ini hanya untuk Admin dan HRD.',
                ]);
            }

            // HRD harus verifikasi license code terlebih dahulu
            if ($roleName === 'hrd' && !$user->is_role_verified) {
                return redirect()->route('role.verify.form');
            }

            // Cek apakah perlu ganti password (login pertama)
            if ($user->needs_password_change) {
                return redirect()->route('password.change.form');
            }

            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'email' => 'Email atau password yang anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }

    protected function redirectToDashboard()
    {
        $user = auth()->user();
        $role = $user->role ? strtolower(str_replace(' ', '', $user->role->name)) : '';

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'hrd':
                return redirect()->route('hrd.dashboard');
            default:
                auth()->logout();
                return redirect('/login')->withErrors([
                    'email' => 'Akun Anda tidak memiliki role yang valid. Silakan hubungi administrator.',
                ]);
        }
    }
}
