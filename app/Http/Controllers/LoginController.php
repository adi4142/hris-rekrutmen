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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba autentikasi menggunakan auth() helper
        if (auth()->attempt($credentials)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke dashboard sesuai role
            return $this->redirectToDashboard();
        }

        // Login gagal
        return back()->withErrors([
            'email' => 'Email atau password yang anda masukkan salah.',
        ])->withInput($request->only('email'));
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
        $role = $user->role ? strtolower($user->role->name) : '';

        // Redirect berdasarkan role
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            
            case 'hrd':
                return redirect()->route('hrd.dashboard');
            
            case 'karyawan':
                return redirect()->route('employee.dashboard');
            
            case 'pelamar':
            case 'tamu':
                return redirect()->route('applicant.dashboard');
            
            default:
                // Untuk role yang tidak dikenal, logout dan tampilkan error
                auth()->logout();
                return redirect('/login')->withErrors([
                    'email' => 'Akun Anda tidak memiliki role yang valid. Silakan hubungi administrator.',
                ]);
        }
    }
}
