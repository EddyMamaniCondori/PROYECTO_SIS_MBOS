<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();

            // Redirecciones por rol
            if ($user->hasRole('Super Administrador')) {
                return redirect()->route('panel');
            }

            if ($user->hasRole('Administrativo')) {
                return redirect()->route('panel.mbos');
            }

            if ($user->hasRole('Tesorero')) {
                return redirect()->route('dashboard.tesorero');
            }

            if ($user->hasRole('Secretaria')) {
                return redirect()->route('dashboard.secretario');
            }

            if ($user->hasRole('Pastor')) {
                return redirect()->route('dashboard.pastor');
            }

            // fallback
            return redirect()->route('panel');
        }

        return $next($request);
    }
}
