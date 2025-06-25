<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\DocumentosPacientes;
use App\Models\Pacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentosPacientesController extends Controller
{
    public function index()
    {
        $query = DocumentosPacientes::query()->where('Status', 1);

        if (request()->filled('tipo')) {
            $query->where('Tipo', request('tipo'));
        }

        if (request()->filled('paciente_id')) {
            $query->where('ID_Paciente', request('paciente_id'));
        }

        $documentos = $query->with('paciente')->paginate(12);
        $pacientes = Pacientes::where('Status', 1)->get();

        return view('docs.index', compact('documentos', 'pacientes'));
    }

    public function showByPacient($pacienteId)
    {
        $user = auth()->user();
        $paciente = Pacientes::find($pacienteId);
        if (!$paciente) {
            return back()->with('error', 'Paciente no encontrado.');
        }
        if ($user->Rol === 'Alumno') {
            $alumno = Alumnos::where('Matricula', $user->Matricula)->first();
            if (!$alumno || !$alumno->asignaciones()->where('ID_Paciente', $pacienteId)->exists()) {
                return back()->with('error', 'No tienes permisos para ver estos documentos.');
            }
        }
        $documentos = DocumentosPacientes::where('ID_Paciente', $pacienteId)
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

        return view('docs.showByPacient', [
            'documentos' => $documentos,
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
            $rutaCompleta = public_path("pacients/{$carpetaPaciente}/docs");
            if (!file_exists($rutaCompleta)) {
                mkdir($rutaCompleta, 0777, true);
            }

            $file = $request->file('RutaArchivo');
            $extension = $file->getClientOriginalExtension();
            $nombreArchivo = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
            $file->move($rutaCompleta, $nombreArchivo);

            $documento = new DocumentosPacientes();
            $documento->RutaArchivo = "pacients/{$carpetaPaciente}/docs/{$nombreArchivo}";
            $documento->Tipo = $request->Tipo;
            $documento->ID_Paciente = $request->ID_Paciente;

            if (!$documento->save()) {
                if (file_exists($rutaCompleta . '/' . $nombreArchivo)) {
                    unlink($rutaCompleta . '/' . $nombreArchivo);
                }
                return response()->json(['message' => 'Error al guardar en la base de datos'], 500);
            }

            return response()->json([
                'message' => 'Documento guardado exitosamente',
                'documento' => $documento
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al procesar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $documento = DocumentosPacientes::find($id);

        if (!$documento) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }
        $request->validate([
            'RutaArchivo' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);
        $paciente = Pacientes::find($documento->ID_Paciente);
        $nombrePaciente = $paciente->Nombre . $paciente->ApePaterno . $paciente->ApeMaterno;
        $carpetaPaciente = Str::slug($nombrePaciente);
        $rutaCompleta = public_path("pacients/{$carpetaPaciente}/docs");
        if (!file_exists($rutaCompleta)) {
            mkdir($rutaCompleta, 0777, true);
        }
        $rutaAnterior = public_path($documento->RutaArchivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }
        $archivo = $request->file('RutaArchivo');
        $extension = $archivo->getClientOriginalExtension();
        $nuevoNombre = time() . '_' . Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
        $archivo->move($rutaCompleta, $nuevoNombre);
        $documento->RutaArchivo = "pacients/{$carpetaPaciente}/docs/{$nuevoNombre}";
        $documento->save();

        return response()->json(['message' => 'Documento actualizado correctamente']);
    }

    public function destroy($id)
    {
        $documento = DocumentosPacientes::findOrFail($id);
        try {
            if ($documento->RutaArchivo) {
                $rutaArchivo = public_path($documento->RutaArchivo);
                if (file_exists($rutaArchivo)) {
                    unlink($rutaArchivo);
                }
            }
            $documento->delete();
            return response()->json(['message' => 'Documento eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar documento: ' . $e->getMessage()], 500);
        }
    }

    public function download($pacienteId, $documentoId)
    {
        $user = auth()->user();
        $documento = DocumentosPacientes::findOrFail($documentoId);
        if ($documento->ID_Paciente != $pacienteId) {
            return back()->with('error', 'No tienes acceso a este documento.');
        }
        if ($user->Rol === 'Alumno') {
            $alumno = Alumnos::where('ID_Usuario', $user->ID_Usuario)->first();
            if (!$alumno || !$alumno->asignaciones()->where('ID_Paciente', $pacienteId)->exists()) {
                return back()->with('error', 'No tienes permisos para descargar este documento.');
            }
        }
        $filePath = public_path($documento->RutaArchivo);
        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }
        return response()->download($filePath);
    }
}
