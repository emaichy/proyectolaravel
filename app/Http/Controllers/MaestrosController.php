<?php

namespace App\Http\Controllers;

use App\Models\Maestros;
use App\Models\Usuarios;
use App\Models\Estados;
use App\Models\Grupos;
use App\Models\Municipios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MaestrosController extends Controller
{
    public function index()
    {
        $maestros = Maestros::where('Status', 1)->paginate(10);
        return view('maestro.index', compact('maestros'));
    }

    public function show($id)
    {
        $maestro = Maestros::with(['grupos' => function ($query) {
            $query->withCount('alumnos')->with('alumnos');
        }])
            ->findOrFail($id);
        if (!$maestro) {
            return redirect()->route('maestros.index')->with('error', 'Maestro no encontrado.');
        }
        $estados = Estados::where('Status', 1)->get();
        $municipios = collect();
        if ($estados->isNotEmpty()) {
            $municipios = $estados->first()->municipios()->where('Status', 1)->get();
        }
        return view('maestro.show', compact('maestro', 'estados', 'municipios'));
    }

    public function create()
    {
        $estados = Estados::all();
        $municipios = Municipios::all();
        return view('maestro.create', compact('estados', 'municipios'));
    }

    public function store(Request $request)
    {
        $usuario = Usuarios::create([
            'Correo' => $request->Correo,
            'password' => Hash::make($request->password),
            'Rol' => 'Maestro',
        ]);
        Maestros::create([
            'Nombre' => $request->Nombre,
            'ApePaterno' => $request->ApePaterno,
            'ApeMaestro' => $request->ApeMaestro,
            'Especialidad' => $request->Especialidad,
            'Firma' => $request->Firma ?? null,
            'FechaNac' => $request->FechaNac,
            'Sexo' => $request->Sexo,
            'Direccion' => $request->Direccion,
            'NumeroExterior' => $request->NumeroExterior,
            'NumeroInterior' => $request->NumeroInterior,
            'CodigoPostal' => $request->CodigoPostal,
            'Pais' => $request->Pais,
            'Telefono' => $request->Telefono,
            'CedulaProfesional' => $request->CedulaProfesional,
            'ID_Estado' => $request->ID_Estado,
            'ID_Municipio' => $request->ID_Municipio,
            'ID_Usuario' => $usuario->ID_Usuario,
        ]);
        return redirect()->route('maestros.index')->with('success', 'Maestro creado exitosamente');
    }

    public function edit($id)
    {
        $maestro = Maestros::find($id);
        $usuario = $maestro->usuario;
        $estados = Estados::all();
        $municipios = Municipios::all();

        return view('maestro.edit', compact('maestro', 'usuario', 'estados', 'municipios'));
    }

    public function update(Request $request, $id)
    {
        $maestro = Maestros::find($id);
        $usuario = $maestro->usuario;
        $usuario->Correo = $request->Correo;
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        $maestro->update([
            'Nombre' => $request->Nombre,
            'ApePaterno' => $request->ApePaterno,
            'ApeMaestro' => $request->ApeMaestro,
            'Especialidad' => $request->Especialidad,
            'Firma' => $request->Firma ?? null,
            'FechaNac' => $request->FechaNac,
            'Sexo' => $request->Sexo,
            'Direccion' => $request->Direccion,
            'NumeroExterior' => $request->NumeroExterior,
            'NumeroInterior' => $request->NumeroInterior,
            'CodigoPostal' => $request->CodigoPostal,
            'Pais' => $request->Pais,
            'Telefono' => $request->Telefono,
            'CedulaProfesional' => $request->CedulaProfesional,
            'ID_Estado' => $request->ID_Estado,
            'ID_Municipio' => $request->ID_Municipio
        ]);
        return redirect()->route('maestros.index')->with('success', 'Maestro actualizado correctamente');
    }

    public function destroy($id)
    {
        $maestro = Maestros::find($id);
        $maestro->Status = 0;
        $maestro->save();
        return redirect()->route('maestros.index')->with('success', 'Maestro eliminado correctamente');
    }

    public function gestionarGrupos(Maestros $maestro)
    {
        $gruposAsignados = $maestro->grupos()->with('semestre')->get();
        $gruposDisponibles = Grupos::whereDoesntHave('maestros')->with('semestre')->get();
        return view('maestro.gestionargrupos', [
            'maestro' => $maestro,
            'gruposAsignados' => $gruposAsignados,
            'gruposDisponibles' => $gruposDisponibles
        ]);
    }

    public function asignarGrupo(Request $request, Maestros $maestro)
    {
        $request->validate([
            'ID_Grupo' => 'required|exists:grupos,ID_Grupo'
        ]);
        $grupoYaAsignado = $maestro->grupos()->where('grupos.ID_Grupo', $request->ID_Grupo)->exists();
        if ($grupoYaAsignado) {
            return back()->with('error', 'Este grupo ya está asignado a este maestro.');
        }
        $maestro->grupos()->attach($request->ID_Grupo, ['Status' => 1]);
        return back()->with('success', 'Grupo asignado correctamente');
    }

    public function desasignarGrupo(Maestros $maestro, Grupos $grupo)
    {
        $maestro->grupos()->detach($grupo->ID_Grupo);
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Grupo desasignado correctamente');
    }
}
