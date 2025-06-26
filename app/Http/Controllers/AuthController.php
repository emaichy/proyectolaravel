<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function redirect()
{
    if (!auth()->check()) {
        return view('auth.login');
    }

    $user = auth()->user();
    switch ($user->Rol) {
        case 'Admininistrativo':
            return redirect()->route('admin.home');
        case 'Maestro':
            return redirect()->route('maestro.home');
        case 'Alumno':
            return redirect()->route('alumno.home');
        default:
            auth()->logout();
            return view('auth.login')->with('error', 'Rol no reconocido');
    }
}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $usuario = Usuarios::where('Correo', $request->Correo)->first();

        if ($usuario) {
            if ($usuario->Status == 1) {
                if (Auth::attempt(['Correo' => $request->Correo, 'password' => $request->Contrasena])) {
                    if (Auth::user()->Rol == 'Administrativo') {
                        return redirect()->route('admin.home');
                    } elseif (Auth::user()->Rol == 'Maestro') {
                        return redirect()->route('maestro.home');
                    } elseif (Auth::user()->Rol == 'Alumno') {
                        return redirect()->route('alumno.home');
                    }
                    return redirect()->route('login')->with(['error' => 'Rol no reconocido.']);
                }
                return redirect()->route('login')->with(['error' => 'Las credenciales proporcionadas son incorrectas.']);
            }
            return redirect()->route('login')->with(['error' => 'El usuario no está activo. Por favor, contacta al administrador.']);
        }
        return redirect()->route('login')->with(['error' => 'El usuario no existe. Por favor, verifica tus credenciales.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }
}
