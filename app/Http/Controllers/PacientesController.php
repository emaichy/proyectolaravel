<?php

namespace App\Http\Controllers;

use App\Models\Estados;
use App\Models\Pacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PacientesController extends Controller
{
    public function index()
    {
        $pacientes = Pacientes::where('Status', 1)->paginate(10);
        return view('pacientes.inicio', [
            'pacientes' => $pacientes,
            'info' => $pacientes->isEmpty() ? 'No hay pacientes registrados. Por favor, crea uno nuevo.' : null
        ]);
    }

    public function show($id)
    {
        $paciente = Pacientes::find($id);
        if (!$paciente) {
            return redirect()->route('pacientes.index')->with('error', 'Paciente no encontrado.');
        }
        $estados = Estados::where('Status', 1)->get();
        $municipios = collect();
        if ($estados->isNotEmpty()) {
            $municipios = $estados->first()->municipios()->where('Status', 1)->get();
        }
        return view('pacientes.show', compact('paciente'));
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
        if ($request->hasFile('Foto_Paciente')) {
            $file = $request->file('Foto_Paciente');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/pacientes'), $filename);
            $paciente->Foto_Paciente = $filename;
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
            if ($paciente->Foto_Paciente) {
                $rutaFoto = public_path('images/pacientes/' . $paciente->Foto_Paciente);
                if (Storage::exists($rutaFoto)) {
                    Storage::delete($rutaFoto);
                }
            }
            $file = $request->file('Foto_Paciente');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/pacientes'), $filename);
            $paciente->Foto_Paciente = $filename;
        }
        $paciente->save();
        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $paciente = Pacientes::find($id);
        if (!$paciente) {
            return redirect()->route('pacientes.index')->with('error', 'Paciente no encontrado.');
        }
        $paciente->Status = 0;
        $paciente->save();
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado exitosamente.');
    }
}
