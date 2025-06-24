<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaestroOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }
        $rol = Auth::user()->Rol;
        if ($rol === 'Maestro' || $rol === 'Administrativo') {
            return $next($request);
        }
        if ($rol === 'Alumno') {
            return redirect()->route('alumno.home')->with('error', 'Noa tienes permisos para acceder a esta página.');
        }
        return redirect()->route('login')->with('error', 'Noa tienes permisos para acceder a esta página.');
    }
}