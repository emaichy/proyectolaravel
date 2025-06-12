<?php

namespace App\Http\Controllers;

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

    public function showByPacient($id)
    {
        $radiografias = RadiografiasPacientes::where('ID_Paciente', $id)
            ->where('Status', 1)
            ->get();

        return view('radiografias.showByPacient', [
            'radiografias' => $radiografias,
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
            $rutaCompleta = public_path('images/radiografias/' . $carpetaPaciente);
            if (!file_exists($rutaCompleta)) {
                mkdir($rutaCompleta, 0777, true);
            }
            $file = $request->file('RutaArchivo');
            $nombreOriginal = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nombreArchivo = time() . '_' . Str::slug(pathinfo($nombreOriginal, PATHINFO_FILENAME)) . '.' . $extension;
            $file->move($rutaCompleta, $nombreArchivo);

            $radiografia = new RadiografiasPacientes();
            $radiografia->RutaArchivo = $carpetaPaciente . '/' . $nombreArchivo;
            $radiografia->Tipo = $request->Tipo;
            $radiografia->ID_Paciente = $request->ID_Paciente;

            if (!$radiografia->save()) {
                if (file_exists($rutaCompleta . '/' . $nombreArchivo)) {
                    unlink($rutaCompleta . '/' . $nombreArchivo);
                }
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
        $rutaCompleta = public_path('docs/' . $carpetaPaciente);
        $rutaAnterior = public_path('docs/' . $radiografia->RutaArchivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }
        $archivo = $request->file('RutaArchivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $extension = $archivo->getClientOriginalExtension();
        $nuevoNombre = time() . '_' . Str::slug(pathinfo($nombreOriginal, PATHINFO_FILENAME)) . '.' . $extension;
        $archivo->move($rutaCompleta, $nuevoNombre);
        $radiografia->RutaArchivo = $carpetaPaciente . '/' . $nuevoNombre;
        $radiografia->save();

        return response()->json(['message' => 'Radiografía actualizada correctamente']);
    }

    public function destroy($id)
    {
        $radiografia = RadiografiasPacientes::findOrFail($id);
        try {
            $radiografia = RadiografiasPacientes::findOrFail($id);
            $rutaArchivo = public_path('images/radiografias/' . $radiografia->RutaArchivo);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            $radiografia->delete();
            return response()->json(['message' => 'Radiografía eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar radiografía'. $e->getMessage()], 500);
        }
    }

    public function download($id)
    {
        $radiografia = RadiografiasPacientes::findOrFail($id);
        $filePath = public_path('images/radiografias/' . $radiografia->RutaArchivo);
        if (!file_exists($filePath)) {
            abort(404, 'Archivo no econtrado');
        }
        return response()->download($filePath);
    }
}
