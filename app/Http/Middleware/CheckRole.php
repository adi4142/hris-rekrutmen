<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Middleware CheckRole
 * 
 * Middleware untuk memeriksa role user yang sedang login
 * dan mengarahkan ke dashboard yang sesuai dengan role-nya
 * 
 * PENTING: Gunakan auth()->user() bukan Auth facade untuk konsistensi
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles - Daftar role yang diizinkan mengakses route
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login menggunakan auth() helper
        if (!auth()->check()) {
            return redirect('login');
        }

        // Ambil user yang sedang login
        $user = auth()->user();
        
        // Ambil nama role user (lowercase untuk konsistensi)
        $userRole = $user->role ? strtolower($user->role->name) : null;

        // Jika role user ada dalam daftar role yang diizinkan, lanjutkan request
        if (in_array($userRole, $roles)) {
            return $next($request);
        }
        
        // Jika role tidak sesuai, redirect ke dashboard sesuai role
        // PENTING: Gunakan route name untuk menghindari redirect loop
        return $this->redirectToDashboard($userRole, $request);
    }

    /**
     * Redirect user ke dashboard sesuai dengan role-nya
     * 
     * @param string|null $userRole
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToDashboard($userRole, $request)
    {
        // Definisikan mapping role ke route dashboard
        $dashboardRoutes = [
            'admin' => 'admin.dashboard',
            'hrd' => 'hrd.dashboard',
            'pelamar' => 'applicant.dashboard',
            'tamu' => 'applicant.dashboard',
            'karyawan' => 'employee.dashboard',
        ];

        // Cek apakah user sudah berada di dashboard yang sesuai
        // untuk menghindari redirect loop
        $currentRoute = $request->route()->getName();
        
        if (isset($dashboardRoutes[$userRole])) {
            $targetRoute = $dashboardRoutes[$userRole];
            
            // Jika sudah di route yang benar, jangan redirect lagi
            if ($currentRoute === $targetRoute) {
                return abort(403, 'Unauthorized action.');
            }
            
            return redirect()->route($targetRoute);
        }

        // Jika role tidak dikenal, tampilkan error 403
        abort(403, 'Unauthorized action. Role tidak dikenal.');
    }
}
