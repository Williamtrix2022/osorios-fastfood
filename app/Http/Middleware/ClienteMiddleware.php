<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClienteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sugerencia de tipo para eliminar la advertencia de Intelephense
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();

        // 1. Verificar si el usuario está autenticado
        if (!$auth->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        // 2. Verificar si el usuario es cliente
        if ($auth->user()->role !== 'cliente') {
            abort(403, 'No tienes permiso para acceder a esta sección');
        }

        return $next($request);
    }
}