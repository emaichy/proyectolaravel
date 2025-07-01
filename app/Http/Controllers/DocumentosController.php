<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use Illuminate\Http\Request;

class DocumentosController extends Controller
{
public function index($matricula)
{
    $alumno = Alumnos::where('Matricula', $matricula)->first();

    if (!$alumno) {
        abort(404, "No se encontró alumno con matrícula $matricula");
    }

    return view('documentoss.index', compact('alumno'));
}

public function indexAlumno()
{
    $user = auth()->user();
    $alumno = $user->alumno;

    if (!$alumno) {
        abort(403, 'No tienes un alumno asociado.');
    }

    $asignacion = $alumno->asignaciones()->with('expediente.paciente')->first();
    $paciente = optional(optional($asignacion)->expediente)->paciente;

    return view('documentoss.index', compact('alumno', 'paciente'));
}





}