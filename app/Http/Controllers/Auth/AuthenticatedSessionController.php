<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Persona;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirecciones por rol usando Spatie
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
        return redirect()->route('panel');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
