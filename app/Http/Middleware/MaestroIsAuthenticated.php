<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaestroIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }
        if(Auth::user()->role !== 'Maestro') {
            if(Auth::user()->role === 'Alumno') {
                return redirect()->route('alumno.home')->with('error', 'No tienes permisos para acceder a esta página.');
            } elseif(Auth::user()->role === 'Administrativo') {
                return redirect()->route('admin.home')->with('error', 'No tienes permisos para acceder a esta página.');
            }
            // Si el usuario no es Maestro, Alumno o Administrativo, redirigir a la página de inicio del Maestro
            return redirect()->route('maestro.home')->with('error', 'No tienes permisos para acceder a esta página.');
        }
        return $next($request);
    }
}
