<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\AsignacionExpedienteAlumno;
use App\Models\AsignacionPacientesAlumnos;
use App\Models\DocumentosPacientes;
use App\Models\Estados;
use App\Models\Expediente;
use App\Models\FotografiasPacientes;
use App\Models\Maestros;
use App\Models\Pacientes;
use App\Models\RadiografiasPacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PacientesController extends Controller
{
    public function index(Request $request)
    {
        $current = $request->fullUrl();
        session()->put('nav_stack', [$current]);
        $pacientes = Pacientes::where('Status', 1)->paginate(10);
        return view('pacientes.index', [
            'pacientes' => $pacientes,
            'info' => $pacientes->isEmpty() ? 'No hay pacientes registrados. Por favor, crea uno nuevo.' : null
        ]);
    }

    public function show($pacienteId)
    {
        $user = auth()->user();
        $paciente = Pacientes::find($pacienteId);

        if (!$paciente) {
            return back()->with('error', 'Paciente no encontrado.');
        }

        $allow = false;

        switch ($user->Rol) {
            case 'Maestro':
                $maestro = Maestros::where('ID_Usuario', $user->ID_Usuario)->first();
                if ($maestro) {
                    $expedientes = Expediente::where('ID_Paciente', $pacienteId)->pluck('ID_Expediente');
                    $hasPaciente = AsignacionExpedienteAlumno::whereIn('ID_Expediente', $expedientes)
                        ->whereHas('alumno.grupo.maestros', function ($q) use ($maestro) {
                            $q->where('maestros.ID_Maestro', $maestro->ID_Maestro);
                        })
                        ->exists();
                    if ($hasPaciente) $allow = true;
                }
                $layout = 'layouts.maestro';
                break;
            case 'Alumno':
                $alumno = Alumnos::where('ID_Usuario', $user->ID_Usuario)->first();
                if ($alumno) {
                    $expedientes = Expediente::where('ID_Paciente', $pacienteId)->pluck('ID_Expediente');
                    $hasPaciente = AsignacionExpedienteAlumno::whereIn('ID_Expediente', $expedientes)
                        ->where('ID_Alumno', $alumno->Matricula)
                        ->exists();
                    if ($hasPaciente) $allow = true;
                }
                $layout = 'layouts.alumno';
                break;
            case 'Administrativo':
                $allow = true;
                $layout = 'layouts.admin';
                break;
            default:
                $layout = 'layouts.app';
                break;
        }
        if (!$allow) {
            return back()->with('error', 'No tienes permiso para ver este paciente.');
        }
        return view('pacientes.show', compact('paciente', 'layout'));
    }

    public function create()
    {
        $estados = Estados::where('Status', 1)->get();
        $municipios = collect();
        if ($estados->isNotEmpty()) {
            $municipios = $estados->first()->municipios()->where('Status', 1)->get();
        }
        return view('pacientes.create', compact('estados', 'municipios'));
    }

    public function store(Request $request)
    {
        $paciente = new Pacientes();
        $paciente->Nombre = $request->Nombre;
        $paciente->ApePaterno = $request->ApePaterno;
        $paciente->ApeMaterno = $request->ApeMaterno;
        $paciente->FechaNac = $request->FechaNac;
        $paciente->Sexo = $request->Sexo;
        $paciente->Direccion = $request->Direccion;
        $paciente->NumeroExterior = $request->NumeroExterior;
        $paciente->NumeroInterior = $request->NumeroInterior;
        $paciente->CodigoPostal = $request->CodigoPostal;
        $paciente->Pais = $request->Pais;
        $paciente->TipoPaciente = $request->TipoPaciente;
        $paciente->ID_Estado = $request->ID_Estado;
        $paciente->ID_Municipio = $request->ID_Municipio;

        $nombrePaciente = Str::slug($request->Nombre . $request->ApePaterno . $request->ApeMaterno);
        $rutaPaciente = public_path("pacients/{$nombrePaciente}");
        $rutaImages = "{$rutaPaciente}/images";
        $rutaDocs = "{$rutaPaciente}/docs";

        if (!file_exists($rutaImages)) {
            mkdir($rutaImages, 0777, true);
        }
        if (!file_exists($rutaDocs)) {
            mkdir($rutaDocs, 0777, true);
        }

        if ($request->hasFile('Foto_Paciente')) {
            $file = $request->file('Foto_Paciente');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move($rutaImages, $filename);
            $paciente->Foto_Paciente = "pacients/{$nombrePaciente}/images/{$filename}";
        }

        $paciente->save();
        return redirect()->route('pacientes.index')->with('success', 'Paciente creado exitosamente.');
    }

    public function edit($id)
    {
        $paciente = Pacientes::find($id);
        if (!$paciente) {
            return redirect()->route('pacientes.index')->with('error', 'Paciente no encontrado.');
        }
        $estados = Estados::where('Status', 1)->get();
        $municipios = collect();
        return view('pacientes.edit', compact('paciente', 'estados', 'municipios'));
    }

    public function update(Request $request, $id)
    {
        $paciente = Pacientes::find($id);
        if (!$paciente) {
            return redirect()->route('pacientes.index')->with('error', 'Paciente no encontrado.');
        }

        $paciente->Nombre = $request->Nombre;
        $paciente->ApePaterno = $request->ApePaterno;
        $paciente->ApeMaterno = $request->ApeMaterno;
        $paciente->FechaNac = $request->FechaNac;
        $paciente->Sexo = $request->Sexo;
        $paciente->Direccion = $request->Direccion;
        $paciente->NumeroExterior = $request->NumeroExterior;
        $paciente->NumeroInterior = $request->NumeroInterior;
        $paciente->CodigoPostal = $request->CodigoPostal;
        $paciente->Pais = $request->Pais;
        $paciente->TipoPaciente = $request->TipoPaciente;
        $paciente->ID_Estado = $request->ID_Estado;
        $paciente->ID_Municipio = $request->ID_Municipio;

        if ($request->hasFile('Foto_Paciente')) {
            $nombrePaciente = Str::slug($request->Nombre . $request->ApePaterno . $request->ApeMaterno);
            $rutaImages = public_path("pacients/{$nombrePaciente}/images");

            if (!file_exists($rutaImages)) {
                mkdir($rutaImages, 0777, true);
            }
            if ($paciente->Foto_Paciente && file_exists(public_path($paciente->Foto_Paciente))) {
                unlink(public_path($paciente->Foto_Paciente));
            }

            $file = $request->file('Foto_Paciente');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move($rutaImages, $filename);
            $paciente->Foto_Paciente = "pacients/{$nombrePaciente}/images/{$filename}";
        }

        $paciente->save();
        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    private function eliminarDirectorioRecursivo($ruta)
    {
        if (!file_exists($ruta)) {
            return;
        }
        if (is_file($ruta) || is_link($ruta)) {
            unlink($ruta);
            return;
        }
        $archivos = scandir($ruta);
        foreach ($archivos as $archivo) {
            if ($archivo === '.' || $archivo === '..') {
                continue;
            }
            $this->eliminarDirectorioRecursivo($ruta . DIRECTORY_SEPARATOR . $archivo);
        }
        rmdir($ruta);
    }

    public function destroy($id)
    {
        $paciente = Pacientes::find($id);
        if (!$paciente) {
            return redirect()->route('pacientes.index')->with('error', 'Paciente no encontrado.');
        }

        if ($paciente->Foto_Paciente && file_exists(public_path($paciente->Foto_Paciente))) {
            unlink(public_path($paciente->Foto_Paciente));
        }

        $nombrePaciente = Str::slug($paciente->Nombre . $paciente->ApePaterno . $paciente->ApeMaterno);
        $directorioPaciente = public_path("pacients/{$nombrePaciente}");
        $this->eliminarDirectorioRecursivo($directorioPaciente);
        $paciente->Status = 0;
        $paciente->Foto_Paciente = null;
        $paciente->save();
        DocumentosPacientes::where('ID_Paciente', $paciente->ID_Paciente)->delete();
        FotografiasPacientes::where('ID_Paciente', $paciente->ID_Paciente)->delete();
        RadiografiasPacientes::where('ID_Paciente', $paciente->ID_Paciente)->delete();
        $expedientes = \App\Models\Expediente::where('ID_Paciente', $paciente->ID_Paciente)->pluck('ID_Expediente');
        AsignacionExpedienteAlumno::whereIn('ID_Expediente', $expedientes)->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado exitosamente.');
    }

    public function list()
    {
        $con_expediente = \App\Models\Expediente::pluck('ID_Paciente');
        $pacientes = \App\Models\Pacientes::whereNotIn('ID_Paciente', $con_expediente)
            ->where('Status', 1)
            ->select('ID_Paciente', 'Nombre', 'ApePaterno', 'ApeMaterno')
            ->orderBy('Nombre', 'asc')
            ->get();
        return response()->json($pacientes);
    }

    public function pacientesByAlumno($alumnoId, Request $request)
    {
        $user = auth()->user();
        $current = $request->fullUrl();
        session()->put('nav_stack', [$current]);
        if ($user->Rol === 'Alumno') {
            if (!$user->alumno || $user->alumno->Matricula != $alumnoId) {
                return redirect()->route('alumno.home')
                    ->with('error', 'No tienes permiso para ver los pacientes de este alumno.');
            }
        }
        $alumno = Alumnos::findOrFail($alumnoId);
        $expedienteIds = $alumno->asignaciones()->pluck('ID_Expediente')->toArray();
        $pacienteIds = Expediente::whereIn('ID_Expediente', $expedienteIds)
            ->pluck('ID_Paciente')
            ->unique()
            ->toArray();
        $pacientes = Pacientes::whereIn('ID_Paciente', $pacienteIds)->get();
        return view('alumno.pacientes', compact('pacientes'));
    }
}
