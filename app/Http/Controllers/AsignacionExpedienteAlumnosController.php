<?php

namespace App\Http\Controllers;

use App\Models\AsignacionExpedienteAlumno;
use App\Models\Expediente;
use App\Models\Pacientes;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsignacionExpedienteAlumnosController extends Controller
{
    public function store(Request $request)
    {
        $paciente = Pacientes::findOrFail($request->paciente_id);
        $edad = \Carbon\Carbon::parse($paciente->FechaNac)->age;
        $tipoExpediente = $edad < 18 ? 'Pediatrico' : 'Adulto';
        $expediente = Expediente::firstOrCreate(
            ['ID_Paciente' => $request->paciente_id],
            ['TipoExpediente' => $tipoExpediente]
        );
        AsignacionExpedienteAlumno::create([
            'ID_Alumno'    => $request->alumno_id,
            'ID_Expediente' => $expediente->ID_Expediente,
        ]);
        return response()->json(['message' => 'Asignación guardada']);
    }

    public function destroy($id)
    {
        $asignacion = AsignacionExpedienteAlumno::findOrFail($id);
        $asignacion->delete();
        if (!$asignacion) {
            return response()->json(['message' => 'Asignación no encontrada'], 404);
        }
        return response()->json(['message' => 'Asignación eliminada']);
    }
}
