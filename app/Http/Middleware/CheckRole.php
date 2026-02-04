<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        $userRole = $user->role ? strtolower($user->role->name) : null;

        if (in_array($userRole, $roles)) {
             return $next($request);
        }
        
        // redirect based on role or show unauthorized
        if ($userRole === 'admin' || $userRole === 'hrd') {
            return redirect('/dashboard');
        } elseif ($userRole === 'karyawan') {
            return redirect()->route('attendance.index');
        } elseif ($userRole === 'tamu') {
             return redirect()->route('applicant.dashboard');
        }

        abort(403, 'Unauthorized action.');
    }
}
