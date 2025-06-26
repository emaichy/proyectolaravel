<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\AsignacionPacientesAlumnos;
use App\Models\Maestros;
use App\Models\Pacientes;
use App\Models\RadiografiasPacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RadiografiasPacientesController extends Controller
{
    public function index()
    {
        $query = RadiografiasPacientes::query()->where('Status', 1);

        if (request()->filled('paciente_id')) {
            $query->where('ID_Paciente', request('paciente_id'));
        }
        $radiografias = $query->with('paciente')->paginate(12);
        $pacientes = Pacientes::where('Status', 1)->get();
        return view('radiografias.index', compact('radiografias', 'pacientes'));
    }

    public function showByPacient($pacienteId)
    {
        $user = auth()->user();
        $paciente = Pacientes::find($pacienteId);
        if (!$paciente) {
            return back()->with('error', 'Paciente no encontrado.');
        }
        $allow = false;
        if ($user->Rol === 'Alumno') {
            $alumno = Alumnos::where('ID_Usuario', $user->ID_Usuario)->first();
            if ($alumno && $alumno->asignaciones()->where('ID_Paciente', $pacienteId)->exists()) {
                $allow = true;
            }
        } elseif ($user->Rol === 'Maestro') {
            $maestro = Maestros::where('ID_Usuario', $user->ID_Usuario)->first();
            if ($maestro) {
                $hasPaciente = AsignacionPacientesAlumnos::whereHas('alumno.grupo.maestros', function ($q) use ($maestro) {
                    $q->where('maestros.ID_Maestro', $maestro->ID_Maestro);
                })
                    ->where('ID_Paciente', $pacienteId)
                    ->exists();
                if ($hasPaciente) $allow = true;
            }
        } elseif ($user->Rol === 'Administrativo') {
            $allow = true;
        }
        if (!$allow) {
            return back()->with('error', 'No tienes permisos para ver estas radiografías.');
        }
        $radiografias = RadiografiasPacientes::where('ID_Paciente', $pacienteId)
            ->where('Status', 1)
            ->get();
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
        return view('radiografias.showByPacient', [
            'radiografias' => $radiografias,
            'paciente' => $paciente,
            'layout' => $layout,
        ]);
    }

    public function store(Request $request)
    {
        try {
            if (!$request->hasFile('RutaArchivo')) {
                return response()->json(['message' => 'Archivo no recibido'], 400);
            }

            $paciente = Pacientes::find($request->ID_Paciente);
            if (!$paciente) {
                return response()->json(['message' => 'Paciente no encontrado'], 404);
            }

            $nombrePaciente = $paciente->Nombre . $paciente->ApePaterno . $paciente->ApeMaterno;
            $carpetaPaciente = Str::slug($nombrePaciente);
            $rutaCompleta = public_path("pacients/{$carpetaPaciente}/images/radiografias");

            if (!file_exists($rutaCompleta)) {
                mkdir($rutaCompleta, 0777, true);
            }

            $file = $request->file('RutaArchivo');
            $extension = $file->getClientOriginalExtension();
            $nombreArchivo = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
            $file->move($rutaCompleta, $nombreArchivo);

            $radiografia = new RadiografiasPacientes();
            $radiografia->RutaArchivo = "pacients/{$carpetaPaciente}/images/radiografias/{$nombreArchivo}";
            $radiografia->Tipo = $request->Tipo;
            $radiografia->ID_Paciente = $request->ID_Paciente;

            if (!$radiografia->save()) {
                unlink($rutaCompleta . '/' . $nombreArchivo);
                return response()->json(['message' => 'Error al guardar en la base de datos'], 500);
            }

            return response()->json([
                'message' => 'Radiografía guardada exitosamente',
                'radiografia' => $radiografia
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Excepción capturada',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $radiografia = RadiografiasPacientes::find($id);

        if (!$radiografia) {
            return response()->json(['message' => 'Radiografía no encontrada'], 404);
        }

        $request->validate([
            'RutaArchivo' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $paciente = Pacientes::find($radiografia->ID_Paciente);
        $nombrePaciente = $paciente->Nombre . $paciente->ApePaterno . $paciente->ApeMaterno;
        $carpetaPaciente = Str::slug($nombrePaciente);
        $rutaCompleta = public_path("pacients/{$carpetaPaciente}/images/radiografias");

        if (!file_exists($rutaCompleta)) {
            mkdir($rutaCompleta, 0777, true);
        }

        $rutaAnterior = public_path($radiografia->RutaArchivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }

        $archivo = $request->file('RutaArchivo');
        $extension = $archivo->getClientOriginalExtension();
        $nuevoNombre = time() . '_' . Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
        $archivo->move($rutaCompleta, $nuevoNombre);

        $radiografia->RutaArchivo = "pacients/{$carpetaPaciente}/images/radiografias/{$nuevoNombre}";
        $radiografia->save();

        return response()->json(['message' => 'Radiografía actualizada correctamente']);
    }

    public function destroy($id)
    {
        $radiografia = RadiografiasPacientes::findOrFail($id);
        try {
            $rutaArchivo = public_path($radiografia->RutaArchivo);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            $radiografia->delete();
            return response()->json(['message' => 'Radiografía eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar radiografía: ' . $e->getMessage()], 500);
        }
    }

    public function download($pacienteId, $radiografiaId)
    {
        $user = auth()->user();
        $radiografia = RadiografiasPacientes::findOrFail($radiografiaId);
        if ($radiografia->ID_Paciente != $pacienteId) {
            return back()->with('error', 'No tienes acceso a este documento.');
        }
        if ($user->Rol === 'Alumno') {
            $alumno = Alumnos::where('ID_Usuario', $user->ID_Usuario)->first();
            if (!$alumno || !$alumno->asignaciones()->where('ID_Paciente', $pacienteId)->exists()) {
                return back()->with('error', 'No tienes permisos para descargar este documento.');
            }
        }
        $filePath = public_path($radiografia->RutaArchivo);
        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }
        return response()->download($filePath);
    }
}
