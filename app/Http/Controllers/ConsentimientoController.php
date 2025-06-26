<?php

namespace App\Http\Controllers;

use App\Models\Consentimiento;
use App\Models\Alumnos;
use App\Models\Pacientes;
use App\Models\Expediente;
use App\Models\Semestre;
use App\Models\Grupos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ConsentimientoController extends Controller
{
  public function index(Request $request)
{
    $usuario = auth()->user();
    $paciente_id = $request->query('paciente_id');

    // Alumno
    if ($usuario->Rol === 'Alumno') {
        $alumno = $usuario->alumno;

        if (!$alumno) {
            abort(403, 'Acceso no autorizado: No eres un alumno válido.');
        }

        if (!$paciente_id) {
            abort(403, 'Paciente no especificado.');
        }

        $paciente = Pacientes::find($paciente_id);
        if (!$paciente) {
            abort(404, 'Paciente no encontrado.');
        }

        $consentimientos = Consentimiento::where('ID_Alumno', $alumno->Matricula)
            ->where('ID_Paciente', $paciente_id)
            ->with(['alumno', 'paciente', 'expediente', 'semestre', 'grupo'])
            ->get();

        return view('consentimiento.index', compact('consentimientos', 'paciente'));
    }

    // Maestro
    elseif ($usuario->Rol === 'Maestro') {
        $maestro = $usuario->maestro;

        if (!$maestro) {
            abort(403, 'No tienes grupo asignado como maestro.');
        }

        $grupoId = $maestro->ID_Grupo;

        $consentimientos = Consentimiento::whereHas('alumno', function ($query) use ($grupoId) {
                $query->where('ID_Grupo', $grupoId);
            })
            ->with(['alumno', 'paciente', 'expediente', 'semestre', 'grupo'])
            ->get();

        return view('consentimiento.index', compact('consentimientos'));
    }

    // Cualquier otro rol
    else {
        abort(403, 'Acceso no autorizado.');
    }
}



public function create(Request $request)
{
    $usuario = auth()->user();
    if (!$usuario || !$usuario->alumno) {
        abort(403, 'Acceso no autorizado: No eres un alumno válido.');
    }

    $alumno = $usuario->alumno;
    $pacientesAsignados = $alumno->asignaciones()->with('paciente')->get()->pluck('paciente')->filter();

    $paciente_id = $request->query('paciente_id');
    $pacienteSeleccionado = $pacientesAsignados->firstWhere('ID_Paciente', $paciente_id);

    if (!$pacienteSeleccionado) {
        abort(403, 'El paciente no está asignado a este alumno.');
    }

    return view('consentimiento.create', [
        'alumno' => $alumno,
        'pacientes' => $pacientesAsignados,
        'expedientes' => Expediente::all(),
        'semestres' => Semestre::all(),
        'grupos' => Grupos::all(),
        'paciente_id' => $paciente_id,
        'pacienteSeleccionado' => $pacienteSeleccionado
    ]);
}



    public function store(Request $request)
    {
         $usuario = auth()->user();
        if (!$usuario || !$usuario->alumno) {
            abort(403, 'Acceso no autorizado.');
        }
        $data = $request->validate([
            'ID_Alumno' => 'required|exists:alumnos,Matricula',
            'ID_Paciente' => 'required|exists:pacientes,ID_Paciente',
            'ID_Expediente' => 'required|exists:expedientes,ID_Expediente',
            'ID_Semestre' => 'nullable|exists:semestres,ID_Semestre',
            'ID_Grupo' => 'nullable|exists:grupos,ID_Grupo',
            'fecha' => 'required|date',
            'descripcion_tratamiento' => 'required|string',
            'alumno_tratante' => 'required|string',
            'docentes' => 'required|string',
            'declaracion' => 'required|string',
            'nombre_paciente' => 'nullable|string',
            'nombre_alumno' => 'nullable|string',
            'nombre_docentes' => 'nullable|string',
            'nombre_testigo' => 'nullable|string',
            'firma_paciente' => 'nullable|string',
            'firma_alumno' => 'nullable|string',
            'firma_docentes' => 'nullable|string',
            'firma_testigo' => 'nullable|string',
        ]);

        $consentimiento = Consentimiento::create($data);

        // Cargar relaciones necesarias para el PDF
        $consentimiento->load(['alumno', 'paciente', 'expediente', 'semestre', 'grupo']);

        $pdf = Pdf::loadView('consentimiento.pdf', ['consentimiento' => $consentimiento]);
        $pdfPath = 'consentimientos/consentimiento_' . $consentimiento->id . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $consentimiento->pdf_document = $pdfPath;
        $consentimiento->save();

        return redirect()->route('consentimiento.index', ['paciente_id' => $request->ID_Paciente])
    ->with('success', 'Carta de consentimiento guardada correctamente.');
    }

    public function show(Consentimiento $consentimiento)
    {
        return view('consentimiento.show', [
            'consentimiento' => $consentimiento->load(['alumno', 'paciente', 'expediente', 'semestre', 'grupo'])
        ]);
    }

    public function edit(Consentimiento $consentimiento)
    {
        return view('consentimiento.edit', [
            'consentimiento' => $consentimiento,
            'alumnos' => Alumnos::all(),
            'pacientes' => Pacientes::all(),
            'expedientes' => Expediente::all(),
            'semestres' => Semestre::all(),
            'grupos' => Grupos::all()
        ]);
    }

    public function update(Request $request, Consentimiento $consentimiento)
    {
        $data = $request->validate([
            'ID_Alumno' => 'required|exists:alumnos,Matricula',
            'ID_Paciente' => 'required|exists:pacientes,ID_Paciente',
            'ID_Expediente' => 'required|exists:expedientes,ID_Expediente',
            'ID_Semestre' => 'nullable|exists:semestres,ID_Semestre',
            'ID_Grupo' => 'nullable|exists:grupos,ID_Grupo',
            'fecha' => 'required|date',
            'descripcion_tratamiento' => 'required|string',
            'alumno_tratante' => 'required|string',
            'docentes' => 'required|string',
            'declaracion' => 'required|string',
            'nombre_paciente' => 'nullable|string',
            'nombre_alumno' => 'nullable|string',
            'nombre_docentes' => 'nullable|string',
            'nombre_testigo' => 'nullable|string',
            'firma_paciente' => 'nullable|string',
            'firma_alumno' => 'nullable|string',
            'firma_docentes' => 'nullable|string',
            'firma_testigo' => 'nullable|string',
        ]);

        // Eliminar PDF anterior si existe
        if ($consentimiento->pdf_document && Storage::disk('public')->exists($consentimiento->pdf_document)) {
            Storage::disk('public')->delete($consentimiento->pdf_document);
        }

        $consentimiento->update($data);

        // Cargar relaciones necesarias para el PDF
        $consentimiento->load(['alumno', 'paciente', 'expediente', 'semestre', 'grupo']);

        $pdf = Pdf::loadView('consentimiento.pdf', ['consentimiento' => $consentimiento]);
        $pdfPath = 'consentimientos/consentimiento_' . $consentimiento->id . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $consentimiento->pdf_document = $pdfPath;
        $consentimiento->save();

        return redirect()->route('consentimiento.index')->with('success', 'Carta de consentimiento actualizada correctamente.');
    }

    public function destroy(Consentimiento $consentimiento)
    {
        if ($consentimiento->pdf_document && Storage::disk('public')->exists($consentimiento->pdf_document)) {
            Storage::disk('public')->delete($consentimiento->pdf_document);
        }

        $consentimiento->delete();

        return redirect()->route('consentimiento.index')->with('success', 'Carta de consentimiento eliminada.');
    }
    public function verConsentimientosAlumno($matricula)
{
    $usuario = auth()->user();

    if ($usuario->Rol !== 'Maestro') {
        abort(403, 'Acceso no autorizado');
    }

    $alumno = Alumnos::where('Matricula', $matricula)->first();

    if (!$alumno) {
        abort(404, 'Alumno no encontrado');
    }

    $consentimientos = Consentimiento::where('ID_Alumno', $matricula)
        ->with(['alumno', 'paciente', 'expediente', 'semestre', 'grupo'])
        ->get();

    return view('documentoss.index', compact('consentimientos', 'alumno'));
}
}
