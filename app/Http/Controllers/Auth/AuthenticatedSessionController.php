<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\Guard; // Importamos el contrato de Guard para la sugerencia de tipo
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

        // Sugerencia de tipo (FIX para Intelephense)
        // 1. Hintamos el tipo del Guard de autenticación (auth())
        /** @var Guard $authGuard */
        $authGuard = auth();

        // 2. Hintamos el tipo del objeto User retornado ($user)
        /** @var \App\Models\User $user */
        $user = $authGuard->user();

        // Redirigir según el rol del usuario
        if ($user->role === 'administrador') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'empleado') {
            return redirect()->intended(route('empleado.dashboard'));
        } else {
            // Asumo que la ruta del cliente es 'cliente.menu' o similar
            return redirect()->intended(route('cliente.menu')); 
        }
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