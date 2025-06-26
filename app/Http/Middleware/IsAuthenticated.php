<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        $rol = Auth::user()->Rol ?? '';
        if (in_array($rol, ['Maestro', 'Administrativo', 'Alumno'])) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'No tienes permisos para acceder a esta página.');
    }
}
