<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Grupos;
use App\Models\Maestros;
use Illuminate\Http\Request;

class GruposController extends Controller
{
    public function index()
    {
        $grupos = Grupos::where('Status', 1)->paginate(10);
        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        $alumnos = Alumnos::all();
        $maestros = Maestros::all();
        return view('grupos.create', compact('alumnos', 'maestros'));
    }

    public function store(Request $request)
    {
        $grupo = Grupos::create($request->only('nombre'));
        $grupo->alumnos()->attach($request->input('alumnos', []));
        $grupo->maestros()->attach($request->input('maestros', []));
        return redirect()->route('grupos.index')->with('success', 'Grupo creado correctamente.');
    }

    public function show($id)
    {
        $grupo = Grupos::with(['alumnos', 'maestros'])->findOrFail($id);
        $todosLosGrupos = Grupos::where('Status', 1)->get();
        $alumnosDisponibles = Alumnos::where('Status', 1)
            ->whereNull('ID_Grupo')
            ->get();
        $maestrosDisponibles = Maestros::where('Status', 1)->get();
        return view('grupos.show', compact('grupo', 'todosLosGrupos', 'alumnosDisponibles', 'maestrosDisponibles'));
    }

    public function edit($id)
    {
        $grupo = Grupos::findOrFail($id);
        $alumnos = Alumnos::all();
        $maestros = Maestros::all();
        return view('grupos.edit', compact('grupo', 'alumnos', 'maestros'));
    }

    public function update(Request $request, $id)
    {
        $grupo = Grupos::findOrFail($id);
        $grupo->update($request->only('nombre'));
        $grupo->alumnos()->sync($request->input('alumnos', []));
        $grupo->maestros()->sync($request->input('maestros', []));
        return redirect()->route('grupos.index')->with('success', 'Grupo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $grupo = Grupos::findOrFail($id);
        $grupo->delete();
        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado correctamente.');
    }

    public function asignarAlumnos(Request $request, $id)
    {
        $grupo = Grupos::findOrFail($id);
        $alumnosIds = $request->input('alumnos', []);
        foreach ($alumnosIds as $idAlumno) {
            $alumno = Alumnos::find($idAlumno);
            if ($alumno && $alumno->ID_Grupo === null) {
                $alumno->ID_Grupo = $grupo->ID_Grupo;
                $alumno->save();
            }
        }
        return back()->with('success', 'Alumnos asignados correctamente.');
    }

    public function asignarMaestros(Request $request, $id)
    {
        $grupo = Grupos::findOrFail($id);
        $grupo->maestros()->syncWithoutDetaching($request->input('maestros', []));
        return redirect()->route('grupos.show', $id)->with('success', 'Maestros asignados correctamente.');
    }

    public function desasignarAlumno($grupoId, $alumnoId)
    {
        $grupo = Grupos::findOrFail($grupoId);
        $alumno = Alumnos::where('Matricula', $alumnoId)
            ->where('ID_Grupo', $grupo->ID_Grupo)
            ->first();
        if (!$alumno) {
            return redirect()->route('grupos.show', $grupoId)
                ->with('error', 'Alumno no encontrado o no pertenece a este grupo.');
        }
        $alumno->ID_Grupo = null;
        $alumno->save();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('grupos.show', $grupoId)
            ->with('success', 'Alumno desasignado correctamente.');
    }

    public function desasignarAlumnos(Request $request, $id)
    {
        $grupo = Grupos::findOrFail($id);
        $alumnosIds = $request->input('alumnos');
        if (!$alumnosIds) {
            return redirect()->route('grupos.show', $id)
                ->with('error', 'No se seleccionó ningún alumno para quitar.');
        }
        if (!is_array($alumnosIds)) {
            $alumnosIds = [$alumnosIds];
        }
        Alumnos::whereIn('Matricula', $alumnosIds)
            ->where('ID_Grupo', $grupo->ID_Grupo)
            ->update(['ID_Grupo' => null]);
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('grupos.show', $id)
            ->with('success', 'Alumnos desasignados correctamente.');
    }

    public function desasignarMaestros(Request $request, $id)
    {
        $grupo = Grupos::findOrFail($id);
        $maestrosIds = $request->input('maestros');
        if (!$maestrosIds) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No se seleccionó ningún maestro para quitar.'], 422);
            }
            return redirect()->route('grupos.show', $id)
                ->with('error', 'No se seleccionó ningún maestro para quitar.');
        }
        if (!is_array($maestrosIds)) {
            $maestrosIds = [$maestrosIds];
        }
        $grupo->maestros()->detach($maestrosIds);
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('grupos.show', $id)
            ->with('success', 'Maestros desasignados correctamente.');
    }

    public function ajaxShow($id)
    {
        $grupo = Grupos::with(['alumnos', 'maestros'])->findOrFail($id);
        $alumnosDisponibles = Alumnos::whereNull('ID_Grupo')
            ->orWhere('ID_Grupo', $grupo->ID_Grupo)
            ->get();

        return response()->json([
            'html' => view('grupos.detalle', compact('grupo', 'alumnosDisponibles'))->render()
        ]);
    }
}
