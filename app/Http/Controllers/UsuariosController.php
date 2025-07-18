<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $current = $request->fullUrl();
        session()->put('nav_stack', [$current]);
        $usuarios = Usuarios::where('Status', 1)->paginate(10);
        if ($usuarios->isEmpty()) {
            return redirect()->route('usuarios.create')->with('info', 'No hay usuarios registrados. Por favor, crea un nuevo usuario.');
        }
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $usuario = new Usuarios();
        $usuario->Correo = $request->Correo;
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente!');
    }

    public function show(Usuarios $usuario)
    {
        if (!$usuario) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }
        return view('usuarios.show', compact('usuario'));
    }

    public function edit($id)
    {
        $usuario = Usuarios::find($id);
        if (!$usuario) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuarios $usuario)
    {
        if (!$usuario) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }
        $usuario->Correo = $request->Correo;
        if ($request->has('password') && $request->password != '') {
            $usuario->password = Hash::make($request->password);
        }
        $usuario->Rol = $request->Rol;
        $usuario->save();
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente!');
    }

    public function destroy($id)
    {
        $usuario = Usuarios::find($id);
        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.']);
        }
        $usuario->update(['Status' => 0]);
        switch ($usuario->Rol) {
            case 'Administrativo':
                if ($usuario->administradores) {
                    $usuario->administradores->update(['Status' => 0]);
                }
                break;
            case 'Maestro':
                if ($usuario->maestros) {
                    $usuario->maestros->update(['Status' => 0]);
                }
                break;
            case 'Alumno':
                if ($usuario->alumnos) {
                    $usuario->alumnos->update(['Status' => 0]);
                }
                break;
        }
        return response()->json(['success' => true, 'message' => 'Usuario eliminado exitosamente!']);
    }
}
