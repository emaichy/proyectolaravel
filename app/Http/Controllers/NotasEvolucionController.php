<?php

namespace App\Http\Controllers;

use App\Models\NotaEvolucion;
use App\Models\Alumnos;
use App\Models\Pacientes;
use App\Models\Expediente;
use App\Models\Semestre;
use App\Models\Grupos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class NotasEvolucionController extends Controller
{
    public function index()
{
    $usuario = auth()->user();

    // Si es Alumno
    if ($usuario->Rol === 'Alumno') {
        $alumno = $usuario->alumno;

        if (!$alumno) {
            abort(403, 'Acceso no autorizado: No eres un alumno válido.');
        }

        $notas = NotaEvolucion::where('ID_Alumno', $alumno->Matricula)
            ->with(['alumno', 'paciente', 'expediente', 'semestre', 'grupo'])
            ->get();
    }
    // Si es Maestro
elseif ($usuario->Rol === 'Maestro') {
    $maestro = $usuario->maestro;

    if (!$maestro) {
        abort(403, 'No tienes grupo asignado como maestro.');
    }

    $grupoId = $maestro->ID_Grupo;

    $notas = NotaEvolucion::whereHas('alumno', function ($query) use ($grupoId) {
        $query->where('ID_Grupo', $grupoId);
    })
    ->with(['alumno', 'paciente', 'expediente', 'semestre', 'grupo'])
    ->get();
}

    // Cualquier otro rol
    else {
        abort(403, 'Acceso no autorizado.');
    }

    return view('notasevolucion.index', compact('notas'));
}

   public function create(Request $request)
{
    $usuario = auth()->user();

    $pacienteSeleccionado = null;
    $expedientes = collect();

    if ($request->has('paciente_id')) {
        $pacienteSeleccionado = Pacientes::find($request->input('paciente_id'));

        if ($pacienteSeleccionado) {
        $expedientes = Expediente::where('ID_Paciente', $pacienteSeleccionado->ID_Paciente)->get();
        }
    }

    $semestres = Semestre::all();
    $grupos = Grupos::all();

    if ($usuario && $usuario->alumno && $usuario->alumno->Firma) {
    $firmaAlumnoUrl = asset($usuario->alumno->Firma); 
}



    return view('notasevolucion.create', compact('expedientes', 'semestres', 'grupos', 'pacienteSeleccionado'));
}

    public function store(Request $request)
    {
        $usuario = auth()->user();
        if (!$usuario || !$usuario->alumno) {
            abort(403, 'Acceso no autorizado.');
        }

       $data = $request->validate([
    'ID_Paciente' => 'required|exists:pacientes,ID_Paciente',
    'ID_Expediente' => 'required|exists:expedientes,ID_Expediente',
    'ID_Semestre' => 'required|exists:semestres,ID_Semestre',
    'ID_Grupo' => 'required|exists:grupos,ID_Grupo',
    'fecha' => 'required|date',
    'presion_arterial' => 'nullable|string',
    'frecuencia_cardiaca' => 'nullable|string',
    'frecuencia_respiratoria' => 'nullable|string',
    'temperatura' => 'nullable|string',
    'oximetria' => 'nullable|string',
    'tratamiento_realizado' => 'nullable|string',
    'descripcion_tratamiento' => 'nullable|string',
    'firma_catedratico' => 'nullable|string',
    'firma_alumno' => 'nullable|string',
    'firma_paciente' => 'nullable|string',
]);

$data['ID_Alumno'] = $usuario->alumno->Matricula;

$nota = NotaEvolucion::create($data);

$nota->load(['alumno', 'paciente', 'expediente', 'semestre', 'grupo']);

$pdf = Pdf::loadView('notasevolucion.pdf', [
    'nota' => $nota,
]);

$pdfPath = 'notas_evolucion/nota_' . $nota->ID_Nota . '.pdf';
Storage::disk('public')->put($pdfPath, $pdf->output());

$nota->update(['pdf_document' => $pdfPath]);

return redirect()->route('notasevolucion.index')->with('success', 'Nota de evolución guardada correctamente.');
    }

  
  
  
  
  
    public function show(NotaEvolucion $notasevolucion)
    {
        $notasevolucion->load(['alumno', 'paciente', 'expediente', 'semestre', 'grupo']);
        $jsonPath = "notas_evolucion_json/nota_{$notasevolucion->ID_Nota}.json";
        $nota = Storage::exists($jsonPath) ? json_decode(Storage::get($jsonPath), true) : [];

        return view('notasevolucion.show', compact('notasevolucion', 'nota'));
    }

    
    
    
   
   
    public function edit(NotaEvolucion $notasevolucion)
{
    
    $usuario = auth()->user();
    $jsonPath = "notas_evolucion_json/nota_{$notasevolucion->ID_Nota}.json";
    $datosExtras = Storage::exists($jsonPath) ? json_decode(Storage::get($jsonPath), true) : [];

    if ($usuario->Rol === 'Maestro') {
    return view('notasevolucion.edit', [
        'nota' => $notasevolucion,
        'datosExtras' => $datosExtras,
        'alumnos' => Alumnos::all(),
        'pacientes' => Pacientes::all(),
        'expedientes' => Expediente::all(),
        'semestres' => Semestre::all(),
        'grupos' => Grupos::all(),
        'paciente_id' => $notasevolucion->ID_Paciente
    ]);
}


  $paciente_id = request()->query('paciente_id', $notasevolucion->ID_Paciente);

    return view('notasevolucion.edit', [
        'nota' => $notasevolucion,
        'datosExtras' => $datosExtras,
        'alumnos' => Alumnos::all(),
        'pacientes' => Pacientes::all(),
        'expedientes' => Expediente::all(),
        'semestres' => Semestre::all(),
        'grupos' => Grupos::all(),
        'paciente_id' => $notasevolucion->ID_Paciente
        
    ]);
}






public function update(Request $request, $id)
{
    $notasevolucion = NotaEvolucion::findOrFail($id);
    $usuario = auth()->user();

    if ($usuario->Rol === 'Maestro') {
        $validated = $request->validate([
            'firma_catedratico' => 'required|string',
        ]);

        $notasevolucion->update([
            'firma_catedratico' => $validated['firma_catedratico'],
        ]);
    } else {
        // Alumno u otro rol con permisos completos
        $validated = $request->validate([
            'ID_Alumno' => 'required|exists:alumnos,Matricula',
            'ID_Paciente' => 'required|exists:pacientes,ID_Paciente',
            'ID_Expediente' => 'required|exists:expedientes,ID_Expediente',
            'ID_Semestre' => 'required|exists:semestres,ID_Semestre',
            'ID_Grupo' => 'required|exists:grupos,ID_Grupo',
            'fecha' => 'required|date',
            'presion_arterial' => 'nullable|string',
            'frecuencia_cardiaca' => 'nullable|string',
            'frecuencia_respiratoria' => 'nullable|string',
            'temperatura' => 'nullable|string',
            'oximetria' => 'nullable|string',
            'tratamiento_realizado' => 'nullable|string',
            'descripcion_tratamiento' => 'nullable|string',
            'firma_catedratico' => 'nullable|string',
            'firma_alumno' => 'nullable|string',
            'firma_paciente' => 'nullable|string',
        ]);

        $notasevolucion->update($validated);
    }

    // Actualizar PDF para ambos
    if ($notasevolucion->pdf_document && Storage::disk('public')->exists($notasevolucion->pdf_document)) {
        Storage::disk('public')->delete($notasevolucion->pdf_document);
    }

    $notasevolucion->load(['alumno', 'paciente', 'expediente', 'semestre', 'grupo']);

    $pdf = Pdf::loadView('notasevolucion.pdf', ['nota' => $notasevolucion]);
    $pdfPath = 'notas_evolucion/nota_' . $notasevolucion->ID_Nota . '.pdf';

    Storage::disk('public')->put($pdfPath, $pdf->output());
    $notasevolucion->update(['pdf_document' => $pdfPath]);

    return redirect()->route('notasevolucion.index')->with('success', 'Nota actualizada correctamente');
}

   

    public function destroy(NotaEvolucion $notasevolucion)
    {
        if ($notasevolucion->pdf_document) {
            Storage::disk('public')->delete($notasevolucion->pdf_document);
        }

        $jsonPath = "notas_evolucion_json/nota_{$notasevolucion->ID_Nota}.json";
        if (Storage::exists($jsonPath)) {
            Storage::delete($jsonPath);
        }

        $notasevolucion->delete();
        return redirect()->route('notasevolucion.index')->with('success', 'Nota de evolución eliminada.');
    }

    public function formFirmarCatedratico($id)
    {
        $nota = NotaEvolucion::findOrFail($id);
        return view('notasevolucion.guardaFirmaCatedratico', compact('nota'));
    }
 public function verNotasAlumno($matricula)
{
    $usuario = auth()->user();

    if ($usuario->Rol !== 'Maestro') {
        abort(403, 'Acceso no autorizado');
    }

    $alumno = Alumnos::where('Matricula', $matricula)->firstOrFail();

    $notas = NotaEvolucion::where('ID_Alumno', $matricula)
        ->with(['alumno', 'paciente', 'expediente', 'semestre', 'grupo'])
        ->get();

    return view('notasevolucion.index', compact('notas', 'alumno'));
}


}
