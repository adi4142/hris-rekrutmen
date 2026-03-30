<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user     = auth()->user();
        $userRole = $user->role
            ? str_replace(' ', '', strtolower($user->role->name))
            : null;

        $normalizedRoles = array_map(
            fn($r) => str_replace(' ', '', strtolower($r)),
            $roles
        );

        if (in_array($userRole, $normalizedRoles)) {
            return $next($request);
        }

        return $this->redirectToDashboard($userRole, $request);
    }

    protected function redirectToDashboard($userRole, $request)
    {
        $dashboardRoutes = [
            'admin'      => 'admin.dashboard',
            'superadmin' => 'admin.dashboard', // backward compat jika ada data lama
            'hrd'        => 'hrd.dashboard',
            'pelamar'    => 'applicant.dashboard',
            'tamu'       => 'applicant.dashboard',
        ];

        $currentRoute = $request->route()->getName();

        if (isset($dashboardRoutes[$userRole])) {
            $targetRoute = $dashboardRoutes[$userRole];
            if ($currentRoute === $targetRoute) {
                abort(403, 'Unauthorized action.');
            }
            return redirect()->route($targetRoute);
        }

        abort(403, 'Unauthorized action. Role tidak dikenal.');
    }
}
