<?php

namespace App\Http\Controllers;

use App\Models\AsignacionPacientesAlumnos;
use Illuminate\Http\Request;

class AsignacionPacientesAlumnosController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id' => 'required|exists:alumnos,Matricula',
            'paciente_id' => 'required|exists:pacientes,ID_Paciente',
        ]);
        AsignacionPacientesAlumnos::create([
            'ID_Alumno' => $request->alumno_id,
            'ID_Paciente' => $request->paciente_id
        ]);
        return response()->json(['message' => 'Asignación guardada']);
    }

    public function destroy($id)
    {
        $asignacion = AsignacionPacientesAlumnos::findOrFail($id);
        $asignacion->delete();
        if (!$asignacion) {
            return response()->json(['message' => 'Asignación no encontrada'], 404);
        }
        return response()->json(['message' => 'Asignación eliminada']);
    }
}
