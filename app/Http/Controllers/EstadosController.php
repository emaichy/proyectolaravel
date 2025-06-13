<?php

namespace App\Http\Controllers;

use App\Models\Estados;
use Illuminate\Http\Request;

class EstadosController extends Controller
{
    public function index()
    {
        $estados = Estados::where('Status', 1)->paginate(10);
        return view('estados.inicio', compact('estados'));
    }

    public function show(Estados $estado)
    {
        if (!$estado) {
            return redirect()->route('estados.index')->with('error', 'Estado no encontrado.');
        }
        return view('estados.show', compact('estado'));
    }

    public function create()
    {
        return view('estados.create');
    }

    public function store(Request $request)
    {
        $estado = new Estados();
        $estado->NombreEstado = $request->NombreEstado;
        $estado->save();
        return redirect()->route('estados.index')->with('success', 'Estado creado exitosamente.');
    }

    public function edit($id)
    {
        $estado = Estados::find($id);
        if (!$estado) {
            return redirect()->route('estados.index')->with('error', 'Estado no encontrado.');
        }
        return view('estados.edit', compact('estado'));
    }

    public function update(Request $request, Estados $estado)
    {
        if (!$estado) {
            return redirect()->route('estados.index')->with('error', 'Estado no encontrado.');
        }
        $estado->NombreEstado = $request->NombreEstado;
        $estado->Status = $request->Status;
        $estado->save();
        return redirect()->route('estados.index')->with('success', 'Estado actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $estado = Estados::find($id);
        if (!$estado) {
            return redirect()->route('estados.index')->with('error', 'Estado no encontrado.');
        }
        $estado->update(['Status' => 0]);
        return redirect()->route('estados.index')->with('success', 'Estado eliminado exitosamente.');
    }
}
