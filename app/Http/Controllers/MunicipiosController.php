<?php

namespace App\Http\Controllers;

use App\Models\Municipios;
use Illuminate\Http\Request;

class MunicipiosController extends Controller
{
    public function index()
    {
        $municipios = Municipios::with('estado')
            ->where('Status', 1)
            ->orderBy('NombreMunicipio', 'asc')
            ->paginate(10);
        return view('municipios.index', compact('municipios'));
    }

    public function show($id)
    {
        return view('municipios.show', ['id' => $id]);
    }

    public function create()
    {
        return view('municipios.create');
    }

    public function store(Request $request)
    {
        $municipio = new Municipios();
        $municipio->NombreMunicipio = $request->NombreMunicipio;
        $municipio->ID_Estado = $request->ID_Estado;
        $municipio->save();
        return redirect()->route('municipios.index')->with('success', 'Municipio creado exitosamente.');
    }

    public function edit($id)
    {
        $municipio = Municipios::find($id);
        if (!$municipio) {
            return redirect()->route('municipios.index')->with('error', 'Municipio no encontrado.');
        }
        return view('municipios.edit', compact('municipio'));
    }

    public function update(Request $request, $id)
    {
        $municipio = Municipios::find($id);
        if (!$municipio) {
            return redirect()->route('municipios.index')->with('error', 'Municipio no encontrado.');
        }
        $municipio->NombreMunicipio = $request->NombreMunicipio;
        $municipio->ID_Estado = $request->ID_Estado;
        $municipio->Status = $request->Status;
        $municipio->save();
        return redirect()->route('municipios.index')->with('success', 'Municipio actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $municipio = Municipios::find($id);
        if (!$municipio) {
            return redirect()->route('municipios.index')->with('error', 'Municipio no encontrado.');
        }
        $municipio->update(['Status' => 0]);
        return redirect()->route('municipios.index')->with('success', 'Municipio eliminado exitosamente.');
    }

    public function getMunicipiosByEstado($estadoId)
    {
        $municipios = Municipios::where('ID_Estado', $estadoId)->get();
        return response()->json($municipios);
    }
}
