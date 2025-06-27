<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    public function show($expedienteId){
        $expediente = Expediente::with([
        'paciente',
        'anexos',
        'alumnos',
    ])->findOrFail($expedienteId);
    return view('pacientes.historialclinico', compact('expediente'));
    }
}
