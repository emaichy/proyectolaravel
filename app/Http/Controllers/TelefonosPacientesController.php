<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use App\Models\TelefonosPacientes;
use Illuminate\Http\Request;

class TelefonosPacientesController extends Controller
{
    public function index()
    {
        $query = TelefonosPacientes::query()->where('Status', 1);
        if (request()->has('paciente_id') && request('paciente_id') != '') {
            $query->where('ID_Paciente', request('paciente_id'));
        }

        $telefonos = $query->with('paciente')->paginate(10);
        $pacientes = Pacientes::where('Status', 1)->get();

        return view('telefonos.index', compact('telefonos', 'pacientes'));
    }

    public function show($id)
    {
        $telefono = TelefonosPacientes::find($id);
        if (!$telefono) {
            return redirect()->route('telefonos.index')->with('error', 'Teléfono del paciente no encontrado.');
        }
        return view('telefonos.show', compact('telefono'));
    }

    public function create($ID_Paciente)
    {
        return view('telefonos.create', compact('ID_Paciente'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string',
            'tipo' => 'required|in:Celular,Casa,Trabajo',
            'ID_Paciente' => 'required|exists:pacientes,ID_Paciente'
        ]);
        try {
            $telefono = new TelefonosPacientes();
            $telefono->Telefono = $request->telefono;
            $telefono->Tipo = $request->tipo;
            $telefono->ID_Paciente = $request->ID_Paciente;

            $telefono->save();
            return redirect()->route('pacientes.show', $request->ID_Paciente)
                ->with('success', 'Teléfono agregado correctamente');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al guardar el teléfono: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $telefono = TelefonosPacientes::find($id);
        if (!$telefono) {
            return redirect()->route('telefonos.index')->with('error', 'Teléfono del paciente no encontrado.');
        }
        return view('telefonos.edit', compact('telefono'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'telefono' => 'required|string',
            'tipo' => 'required|in:Celular,Casa,Trabajo',
            'ID_Paciente' => 'required|exists:pacientes,ID_Paciente'
        ]);

        $telefono = TelefonosPacientes::find($id);
        if (!$telefono) {
            return response()->json(['error' => 'Teléfono no encontrado'], 404);
        }

        $telefono->Telefono = $request->telefono;
        $telefono->Tipo = $request->tipo;
        $telefono->ID_Paciente = $request->ID_Paciente;
        $telefono->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $telefono = TelefonosPacientes::find($id);
        if (!$telefono) {
            return response()->json(['error' => 'Teléfono no encontrado'], 404);
        }

        $telefono->Status = 0;
        $telefono->save();

        return response()->json(['success' => true]);
    }

    public function getTelefonosByPaciente($ID_Paciente)
    {
        $telefonos = TelefonosPacientes::where('ID_Paciente', $ID_Paciente)
            ->where('Status', 1)
            ->get()
            ->groupBy('Tipo');

        return response()->json([
            'Celular' => $telefonos['Celular'] ?? [],
            'Casa' => $telefonos['Casa'] ?? [],
            'Trabajo' => $telefonos['Trabajo'] ?? []
        ]);
    }
}
