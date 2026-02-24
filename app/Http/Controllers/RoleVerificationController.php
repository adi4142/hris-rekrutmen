<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

/**
 * RoleVerificationController
 *
 * Kode verifikasi dibaca dari config/site_settings.json (tanpa database).
 */
class RoleVerificationController extends Controller
{
    /**
     * Ambil kode verifikasi dari file JSON
     */
    private function getVerificationCodes(): array
    {
        return [
            'admin' => SuperAdminDashboardController::getSetting('keamanan.license_code_admin', 'ADMIN2026XYZ'),
            'hrd'   => SuperAdminDashboardController::getSetting('keamanan.license_code_hrd', 'HRD2026ABC'),
        ];
    }

    public function showVerifyForm()
    {
        $user     = auth()->user();
        $roleName = $user->role ? strtolower($user->role->name) : '';

        if (!in_array($roleName, ['admin', 'hrd'])) {
            return $this->redirectToDashboard($roleName);
        }

        if ($user->is_role_verified) {
            return $this->redirectToDashboard($roleName);
        }

        return view('auth.role-verify', compact('roleName'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => ['required', 'string'],
        ]);

        $user     = auth()->user();
        $roleName = $user->role ? strtolower($user->role->name) : '';

        if (!in_array($roleName, ['admin', 'hrd'])) {
            return $this->redirectToDashboard($roleName);
        }

        $codes       = $this->getVerificationCodes();
        $correctCode = $codes[$roleName] ?? null;

        if ($correctCode && $request->verification_code === $correctCode) {
            $user->is_role_verified = true;
            $user->save();

            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Verifikasi ' . strtoupper($roleName) . ' berhasil! Silakan login kembali.');
        }

        return back()->withErrors([
            'verification_code' => 'Kode verifikasi tidak valid.',
        ]);
    }

    protected function redirectToDashboard($role)
    {
        $routes = [
            'admin'       => 'admin.dashboard',
            'hrd'         => 'hrd.dashboard',
            'karyawan'    => 'employee.dashboard',
            'pelamar'     => 'applicant.dashboard',
            'tamu'        => 'applicant.dashboard',
            'super admin' => 'superadmin.dashboard',
        ];

        return redirect()->route($routes[$role] ?? 'login');
    }
}
