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
        $grupo = Grupos::with(['alumnos', 'maestro'])->findOrFail($id);
        $todosLosGrupos = Grupos::where('Status', 1)->get();
        $alumnosDisponibles = Alumnos::whereNull('ID_Grupo')
            ->orWhere('ID_Grupo', $grupo->ID_Grupo)
            ->get();

        return view('grupos.show', compact('grupo', 'todosLosGrupos', 'alumnosDisponibles'));
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
        $grupo->alumnos()->syncWithoutDetaching($request->input('alumnos', []));
        return redirect()->route('grupos.show', $id)->with('success', 'Alumnos asignados correctamente.');
    }

    public function asignarMaestros(Request $request, $id)
    {
        $grupo = Grupos::findOrFail($id);
        $grupo->maestros()->sync($request->input('maestros', []));
        return redirect()->route('grupos.show', $id)->with('success', 'Maestros asignados correctamente.');
    }

    public function desasignarAlumnos(Request $request, $id)
    {
        $grupo = Grupos::findOrFail($id);
        $grupo->alumnos()->detach($request->input('alumnos', []));
        return redirect()->route('grupos.show', $id)->with('success', 'Alumnos desasignados correctamente.');
    }

    public function desasignarMaestros(Request $request, $id)
    {
        $grupo = Grupos::findOrFail($id);
        $grupo->maestros()->detach($request->input('maestros', []));
        return redirect()->route('grupos.show', $id)->with('success', 'Maestros desasignados correctamente.');
    }

    public function ajaxShow($id)
    {
        $grupo = Grupos::with(['alumnos', 'maestro'])->findOrFail($id);
        $alumnosDisponibles = Alumnos::whereNull('ID_Grupo')
            ->orWhere('ID_Grupo', $grupo->ID_Grupo)
            ->get();

        return response()->json([
            'html' => view('grupos.detalle', compact('grupo', 'alumnosDisponibles'))->render()
        ]);
    }
}
