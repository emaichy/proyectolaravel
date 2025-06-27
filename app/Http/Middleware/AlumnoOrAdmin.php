<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AlumnoOrAdmin
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
        $rol = Auth::user()->Rol;
        if ($rol === 'Alumno' || $rol === 'Administrativo') {
            return $next($request);
        }
        if ($rol === 'Maestro') {
            return redirect()->route('maestro.home')->with('error', 'No tienes permisos para acceder a esta página.');
        }
        return redirect()->route('login')->with('error', 'No tienes permisos para acceder a esta página.');
    }
}
