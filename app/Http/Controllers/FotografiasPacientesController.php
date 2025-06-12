<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use App\Models\FotografiasPacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FotografiasPacientesController extends Controller
{
    public function index()
    {
        $query = FotografiasPacientes::query()->where('Status', 1);

        if (request()->filled('tipo')) {
            $query->where('Tipo', request('tipo'));
        }

        if (request()->filled('paciente_id')) {
            $query->where('ID_Paciente', request('paciente_id'));
        }

        $fotografias = $query->with('paciente')->paginate(12);
        $pacientes = Pacientes::where('Status', 1)->get();

        return view('fotografias.index', compact('fotografias', 'pacientes'));
    }

    public function showByPacient($id)
    {
        $fotografias = FotografiasPacientes::where('ID_Paciente', $id)
            ->where('Status', 1)
            ->get();

        return view('fotografias.showByPacient', [
            'fotografias' => $fotografias,
            'pacienteId' => $id
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
            $rutaCompleta = public_path('images/' . $carpetaPaciente . '/fotografias');

            if (!file_exists($rutaCompleta)) {
                mkdir($rutaCompleta, 0777, true);
            }

            $file = $request->file('RutaArchivo');
            $extension = $file->getClientOriginalExtension();
            $nombreArchivo = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
            $file->move($rutaCompleta, $nombreArchivo);

            $fotografia = new FotografiasPacientes();
            $fotografia->RutaArchivo = $carpetaPaciente . '/fotografias/' . $nombreArchivo;
            $fotografia->Tipo = $request->Tipo;
            $fotografia->ID_Paciente = $request->ID_Paciente;

            if (!$fotografia->save()) {
                unlink($rutaCompleta . '/' . $nombreArchivo);
                return response()->json(['message' => 'Error al guardar en la base de datos'], 500);
            }

            return response()->json([
                'message' => 'Fotografía guardada exitosamente',
                'fotografia' => $fotografia
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
        $fotografia = FotografiasPacientes::find($id);

        if (!$fotografia) {
            return response()->json(['message' => 'Fotografía no encontrada'], 404);
        }

        $request->validate([
            'RutaArchivo' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $paciente = Pacientes::find($fotografia->ID_Paciente);
        $nombrePaciente = $paciente->Nombre . $paciente->ApePaterno . $paciente->ApeMaterno;
        $carpetaPaciente = Str::slug($nombrePaciente);
        $rutaCompleta = public_path('images/' . $carpetaPaciente . '/fotografias');

        if (!file_exists($rutaCompleta)) {
            mkdir($rutaCompleta, 0777, true);
        }

        $rutaAnterior = public_path('images/' . $fotografia->RutaArchivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }

        $archivo = $request->file('RutaArchivo');
        $extension = $archivo->getClientOriginalExtension();
        $nuevoNombre = time() . '_' . Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
        $archivo->move($rutaCompleta, $nuevoNombre);

        $fotografia->RutaArchivo = $carpetaPaciente . '/fotografias/' . $nuevoNombre;
        $fotografia->save();

        return response()->json(['message' => 'Fotografía actualizada correctamente']);
    }

    public function destroy($id)
    {
        $fotografia = FotografiasPacientes::findOrFail($id);
        try {
            $rutaArchivo = public_path('images/' . $fotografia->RutaArchivo);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            $fotografia->delete();
            return response()->json(['message' => 'Fotografía eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar fotografía: ' . $e->getMessage()], 500);
        }
    }

    public function download($id)
    {
        $fotografia = FotografiasPacientes::findOrFail($id);
        $filePath = public_path('images/' . $fotografia->RutaArchivo);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($filePath);
    }
}
