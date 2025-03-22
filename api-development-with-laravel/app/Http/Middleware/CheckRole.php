<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verifica si el usuario está autenticado
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401); // No autenticado
        }

        // Verifica si el usuario tiene el rol adecuado
        if (!in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Forbidden: You do not have access'], 403); // Acceso denegado
        }

        // Si el usuario está autenticado y tiene el rol adecuado, continúa con la solicitud
        return $next($request);
    }

}
