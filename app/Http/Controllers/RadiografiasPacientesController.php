<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use App\Models\RadiografiasPacientes;
use Illuminate\Http\Request;

class RadiografiasPacientesController extends Controller
{
    public function index()
    {
        $query = RadiografiasPacientes::query()->where('Status', 1);

        if (request()->filled('paciente_id')) {
            $query->where('ID_Paciente', request('paciente_id'));
        }
        $radiografias = $query->paginate(12);
        $pacientes=Pacientes::where('Status', 1)->get();
        return view('radiografias.index', compact('radiografias'));
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

            $file = $request->file('RutaArchivo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('/images/pacientes/radiografias'), $filename);

            $radiografia = new RadiografiasPacientes();
            $radiografia->RutaArchivo = $filename;
            $radiografia->ID_Paciente = $request->ID_Paciente;

            if (!$radiografia->save()) {
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

        $rutaAnterior = public_path('radiografias/pacientes/' . $radiografia->RutaArchivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }

        $archivo = $request->file('RutaArchivo');
        $nuevoNombre = uniqid() . '_' . $archivo->getClientOriginalName();
        $archivo->move(public_path('radiografias/pacientes'), $nuevoNombre);

        $radiografia->RutaArchivo = $nuevoNombre;
        $radiografia->save();

        return response()->json(['message' => 'Radiografía actualizada correctamente']);
    }

    public function destroy($id)
    {
        $radiografia = RadiografiasPacientes::findOrFail($id);
        try {
            if ($radiografia->RutaArchivo && file_exists(public_path('radiografias/pacientes/' . $radiografia->RutaArchivo))) {
                unlink(public_path('radiografias/pacientes/' . $radiografia->RutaArchivo));
            }
            $radiografia->delete();
            return response()->json(['message' => 'Radiografía eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar radiografía'], 500);
        }
    }

    public function download($id)
    {
        $radiografia = RadiografiasPacientes::findOrFail($id);
        $filePath = public_path('radiografias/pacientes/' . $radiografia->RutaArchivo);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($filePath);
    }
}
