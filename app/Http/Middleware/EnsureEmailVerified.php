<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureEmailVerified
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
        if (Auth::check() && !Auth::user()->email_verified_at) {
            // Jika belum verifikasi email, arahkan ke form verifikasi
            if (!$request->is('email-verify*') && !$request->is('logout')) {
                return redirect()->route('emails.verify.form');
            }
        }

        return $next($request);
    }
}
