<?php

namespace App\Http\Controllers;

use App\Models\DocumentosPacientes;
use App\Models\Pacientes;
use Illuminate\Http\Request;

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


    public function showByPacient($id)
    {
        $documentos = DocumentosPacientes::where('ID_Paciente', $id)
            ->where('Status', '!=', 0)
            ->get();

        return view('docs.showByPacient', [
            'documentos' => $documentos,
            'pacienteId' => $id
        ]);
    }

    public function create($ID_Paciente)
    {
        return view('docs.create', compact('ID_Paciente'));
    }

    public function store(Request $request)
    {
        logger(request()->all());

        try {
            if (!$request->hasFile('RutaArchivo')) {
                return response()->json(['message' => 'Archivo no recibido'], 400);
            }

            $file = $request->file('RutaArchivo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('docs/pacientes'), $filename);

            $documento = new DocumentosPacientes();
            $documento->RutaArchivo = $filename;
            $documento->Tipo = $request->Tipo;
            $documento->ID_Paciente = $request->ID_Paciente;
            $documento->Status = 1;

            if (!$documento->save()) {
                return response()->json(['message' => 'Error al guardar en la base de datos'], 500);
            }

            return response()->json([
                'message' => 'Documento guardado exitosamente',
                'documento' => $documento
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
        $documento = DocumentosPacientes::find($id);

        if (!$documento) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }
        $request->validate([
            'RutaArchivo' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);
        $rutaAnterior = public_path('docs/pacientes/' . $documento->RutaArchivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }
        $archivo = request()->file('RutaArchivo');
        $nuevoNombre = uniqid() . '_' . $archivo->getClientOriginalName();
        $archivo->move(public_path('docs/pacientes'), $nuevoNombre);
        $documento->RutaArchivo = $nuevoNombre;
        $documento->save();

        return response()->json(['message' => 'Documento actualizado correctamente']);
    }


    public function destroy($id)
    {
        $documento = DocumentosPacientes::findOrFail($id);
        try {
            if ($documento->RutaArchivo && file_exists(public_path('docs/pacientes/' . $documento->RutaArchivo))) {
                unlink(public_path('docs/pacientes/' . $documento->RutaArchivo));
            }
            $documento->delete();
            return response()->json(['message' => 'Documento eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar documento'], 500);
        }
    }

    public function download($id)
    {
        $documento = DocumentosPacientes::findOrFail($id);
        $filePath = public_path('docs/pacientes/' . $documento->RutaArchivo);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($filePath);
    }
}
