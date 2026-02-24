<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * LoginController
 * 
 * Controller untuk autentikasi login/logout
 * Menggunakan auth() helper untuk konsistensi (bukan Auth facade)
 */
class LoginController extends Controller
{
    /**
     * Tampilkan form login
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Jika user sudah login, redirect ke dashboard sesuai role
        if (auth()->check()) {
            return $this->redirectToDashboard();
        }
        
        return view('auth.login');
    }

    /**
     * Proses login user
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        // Coba autentikasi menggunakan auth() helper
        if (auth()->attempt($credentials)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Cek apakah user perlu verifikasi role tambahan
            $user = auth()->user();

            // Cek apakah akun aktif
            if ($user->status == 'suspended') {
                auth()->logout();
                return redirect('/login')->withErrors([
                    'name' => 'Akun Anda telah ditangguhkan. Silakan hubungi Super Administrator.',
                ]);
            }

            $roleName = $user->role ? strtolower($user->role->name) : '';
            
            // Admin dan HRD harus verifikasi license code
            if (in_array($roleName, ['admin', 'hrd']) && !$user->is_role_verified) {
                return redirect()->route('role.verify.form');
            }

            // Redirect ke dashboard sesuai role
            return $this->redirectToDashboard();
        }

        // Login gagal
        return back()->withErrors([
            'name' => 'Email atau password yang anda masukkan salah.',
        ])->withInput($request->only('name'));
    }

    /**
     * Proses logout user
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Redirect user ke dashboard sesuai role
     * 
     * PENTING: Method ini memastikan setiap role diarahkan ke dashboard yang tepat
     * untuk menghindari redirect loop
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToDashboard()
    {
        $user = auth()->user();
        $role = $user->role ? str_replace(' ', '', strtolower($user->role->name)) : '';

        // Redirect berdasarkan role
        switch ($role) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard');

            case 'admin':
                return redirect()->route('admin.dashboard');
            
            case 'hrd':
                return redirect()->route('hrd.dashboard');
            
            case 'pelamar':
            case 'tamu':
                return redirect()->route('applicant.dashboard');
            
            default:
                // Untuk role yang tidak dikenal, logout dan tampilkan error
                auth()->logout();
                return redirect('/login')->withErrors([
                    'name' => 'Akun Anda tidak memiliki role yang valid. Silakan hubungi administrator.',
                ]);
        }
    }
}
