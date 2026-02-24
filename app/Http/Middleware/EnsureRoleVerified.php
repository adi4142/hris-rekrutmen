<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Middleware EnsureRoleVerified
 * 
 * Memastikan user dengan role admin/hrd sudah memasukkan license code
 * sebelum bisa mengakses dashboard.
 * 
 * User dengan role selain admin/hrd langsung diizinkan (bypass).
 */
class EnsureRoleVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();
        $roleName = $user->role ? strtolower($user->role->name) : '';

        // Hanya role admin dan hrd yang perlu verifikasi tambahan
        $rolesRequiringVerification = ['admin', 'hrd'];

        if (in_array($roleName, $rolesRequiringVerification)) {
            // Cek apakah sudah role_verified
            if (!$user->is_role_verified) {
                // Redirect ke halaman verifikasi kode
                return redirect()->route('role.verify.form');
            }
        }

        // Role lain langsung diizinkan
        return $next($request);
    }
}
