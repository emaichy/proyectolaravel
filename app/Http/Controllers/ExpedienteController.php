<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Expediente;
use App\Models\Maestros;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    public function show($expedienteId)
    {
        $expediente = Expediente::with([
            'paciente',
            'anexos',
            'alumnos.grupo.maestros',
        ])->findOrFail($expedienteId);
        $user = auth()->user();
        $allow = false;
        if ($user->Rol === 'Alumno') {
            $alumno = Alumnos::where('ID_Usuario', $user->ID_Usuario)->first();
            if ($alumno && $expediente->alumnos->contains('Matricula', $alumno->Matricula)) {
                $allow = true;
            }
        } elseif ($user->Rol === 'Maestro') {
            $maestro = Maestros::where('ID_Usuario', $user->ID_Usuario)->first();
            if ($maestro) {
                foreach ($expediente->alumnos as $alumno) {
                    if ($alumno->grupo && $alumno->grupo->maestros->contains('ID_Maestro', $maestro->ID_Maestro)) {
                        $allow = true;
                        break;
                    }
                }
            }
        } elseif ($user->Rol === 'Administrativo') {
            $allow = true;
        }
        if (!$allow) {
            return back()->with('error', 'No tienes permisos para ver este expediente.');
        }
        switch ($user->Rol) {
            case 'Maestro':
                $layout = 'layouts.maestro';
                break;
            case 'Alumno':
                $layout = 'layouts.alumno';
                break;
            case 'Administrativo':
                $layout = 'layouts.admin';
                break;
            default:
                $layout = 'layouts.app';
                break;
        }
        return view('pacientes.historialclinico', compact('expediente', 'layout'));
    }
}
