<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Estados;
use App\Models\Grupos;
use App\Models\Maestros;
use App\Models\Municipios;
use App\Models\Pacientes;
use App\Models\Semestre;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlumnosController extends Controller
{
    public function index(Request $request)
    {
        $current = $request->fullUrl();
        session()->put('nav_stack', [$current]);
        $query = Alumnos::where('Status', 1);
        if ($request->filled('grupo')) {
            $query->where('ID_Grupo', $request->grupo);
        }
        if ($request->filled('semestre')) {
            $query->whereHas('grupo', function ($q) use ($request) {
                $q->where('ID_Semestre', $request->semestre);
            });
        }
        $alumnos = $query->with(['grupo'])->orderBy('Nombre', 'asc')->paginate(10);
        if ($alumnos->isEmpty() && $alumnos->currentPage() > 1) {
            return redirect()->route('alumnos.index', array_merge($request->except('page'), [
                'page' => $alumnos->lastPage()
            ]));
        }
        $grupos = Grupos::where('Status', 1)->get();
        $semestres = Semestre::where('Status', 1)->get();
        return view('alumno.index', compact('alumnos', 'grupos', 'semestres'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $alumno = Alumnos::with([
            'grupo',
            'usuario',
            'estado',
            'municipio',
            'asignaciones.paciente'
        ])->findOrFail($id);
        if ($user->Rol === 'Maestro') {
            $maestro = Maestros::where('ID_Usuario', $user->ID_Usuario)->firstOrFail();
            $pertenece = $maestro->grupos()
                ->where('grupos.ID_Grupo', $alumno->ID_Grupo)
                ->exists();
            if (!$pertenece) {
                return back()->with('error', 'No tienes permiso para ver este alumno.');
            }
            $layout = 'layouts.maestro';
        } else {
            $layout = 'layouts.admin';
        }
        $gruposDisponibles = Grupos::whereDoesntHave('alumnos')
            ->orWhere('ID_Grupo', $alumno->ID_Grupo)
            ->with('semestre')
            ->get();
        $asignados = $alumno->asignaciones->pluck('ID_Paciente');
        $pacientesDisponibles = Pacientes::whereNotIn('ID_Paciente', $asignados)->get();
        return view('alumno.show', compact('alumno', 'gruposDisponibles', 'pacientesDisponibles', 'layout'));
    }

    public function create()
    {
        $estados = Estados::where('Status', 1)->get();
        $municipios = Municipios::where('Status', 1)->get();
        return view('alumno.create', compact('estados', 'municipios'));
    }

    public function store(Request $request)
    {
        $usuario = Usuarios::create([
            'Correo' => $request->Correo,
            'password' => Hash::make($request->password),
        ]);
        Alumnos::create([
            'Nombre' => $request->Nombre,
            'ApePaterno' => $request->ApePaterno,
            'ApeMaterno' => $request->ApeMaterno,
            'FechaNac' => $request->FechaNac,
            'Sexo' => $request->Sexo,
            'Direccion' => $request->Direccion,
            'NumeroExterior' => $request->NumeroExterior,
            'NumeroInterior' => $request->NumeroInterior,
            'CodigoPostal' => $request->CodigoPostal,
            'Telefono' => $request->Telefono,
            'Pais' => $request->Pais,
            'Curp' => $request->Curp,
            'ID_Usuario' => $usuario->ID_Usuario,
            'ID_Estado' => $request->ID_Estado,
            'ID_Municipio' => $request->ID_Municipio,
        ]);
        return redirect()->route('alumnos.index')->with('success', 'Alumno creado exitosamente.');
    }

    public function edit($id)
    {
        $alumno = Alumnos::find($id);
        if (!$alumno) {
            return redirect()->route('alumnos.index')->with('error', 'Alumno no encontrado.');
        }
        $usuario = $alumno->usuario;
        if (!$usuario) {
            return redirect()->route('alumnos.index')->with('error', 'Usuario no encontrado para el alumno.');
        }
        $estados = Estados::where('Status', 1)->get();
        $municipios = Municipios::where('Status', 1)->get();
        return view('alumno.edit', compact('alumno', 'estados', 'municipios'));
    }

    public function update(Request $request, $id)
    {
        $alumno = Alumnos::find($id);
        if (!$alumno) {
            return redirect()->route('alumnos.index')->with('error', 'Alumno no encontrado.');
        }
        $usuario = $alumno->usuario;
        if (!$usuario) {
            return redirect()->route('alumnos.index')->with('error', 'Usuario no encontrado para el alumno.');
        }
        $usuario->update([
            'Correo' => $request->Correo,
            'password' => Hash::make($request->password),
        ]);
        $alumno->update([
            'Nombre' => $request->Nombre,
            'ApePaterno' => $request->ApePaterno,
            'ApeMaterno' => $request->ApeMaterno,
            'FechaNac' => $request->FechaNac,
            'Sexo' => $request->Sexo,
            'Direccion' => $request->Direccion,
            'NumeroExterior' => $request->NumeroExterior,
            'NumeroInterior' => $request->NumeroInterior,
            'CodigoPostal' => $request->CodigoPostal,
            'Telefono' => $request->Telefono,
            'Pais' => $request->Pais,
            'Curp' => $request->Curp,
            'ID_Usuario' => $usuario->ID_Usuario,
            'ID_Estado' => $request->ID_Estado,
            'ID_Municipio' => $request->ID_Municipio,
        ]);
        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $alumno = Alumnos::find($id);
        if (!$alumno) {
            return redirect()->route('alumnos.index')->with('error', 'Alumno no encontrado.');
        }
        $alumno->Status = 0;
        $alumno->save();
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado exitosamente.');
    }

    public function gestionarGrupo(Alumnos $alumno)
    {
        $alumno = Alumnos::find($alumno->ID_Alumno);
        if (!$alumno) {
            return back()->with('error', 'Alumno no encontrado.');
        }
        $grupoAsignado = $alumno->ID_Grupo->first();
        if ($grupoAsignado) {
            return back()->with('error', 'El alumno ya tiene un grupo asignado.');
        }
        $gruposDisponibles = Grupos::whereDoesNotHave('alumnos')
            ->orWhere('ID_Grupo', $alumno->ID_Grupo)
            ->with('semestre')
            ->get();
        return view('alumno.gestionargrupo', [
            'alumno' => $alumno,
            'grupoAsignado' => $grupoAsignado,
            'gruposDisponibles' => $gruposDisponibles
        ]);
    }

    public function asignarGrupo(Request $request, Alumnos $alumno)
    {
        $request->validate([
            'ID_Grupo' => 'required|exists:grupos,ID_Grupo',
        ]);
        $alumno = Alumnos::find($alumno->Matricula);
        if (!$alumno) {
            return back()->with('error', 'Alumno no encontrado.');
        }
        $alumno->ID_Grupo = $request->ID_Grupo;
        $alumno->save();
        return back()->with('success', 'Grupo asignado exitosamente al alumno.');
    }


    public function desasignarGrupo(Alumnos $alumno)
    {
        $alumno = Alumnos::find($alumno->Matricula);
        if (!$alumno) {
            return back()->with('error', 'Alumno no encontrado.');
        }
        $alumno->ID_Grupo = null;
        $alumno->save();
        return back()->with('success', 'Grupo desasignado exitosamente del alumno.');
    }

    public function alumnosByMaestro($id, Request $request)
    {
        $user = auth()->user();
        $current = $request->fullUrl();
        session()->put('nav_stack', [$current]);
        if ($user->Rol === 'Maestro') {
            if (!$user->maestro || $user->maestro->ID_Maestro != $id) {
                return redirect()->route('maestro.home')
                    ->with('error', 'No tienes permiso para ver los alumnos de este maestro.');
            }
        }
        $maestro = Maestros::findOrFail($id);
        $alumnos = Alumnos::whereIn('ID_Grupo', $maestro->grupos()->pluck('grupos.ID_Grupo'))
            ->where('Status', 1)
            ->orderBy('Nombre', 'asc')
            ->paginate(10);

        if ($alumnos->isEmpty()) {
            return redirect()->route('alumnos.index')
                ->with('error', 'No hay alumnos asignados a este maestro.');
        }

        return view('maestro.alumnos', compact('alumnos'));
    }
}
