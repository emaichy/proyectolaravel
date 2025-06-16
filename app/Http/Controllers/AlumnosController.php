<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Grupos;
use App\Models\Semestre;
use Illuminate\Http\Request;

class AlumnosController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumnos::where('Status', 1);
        if ($request->filled('grupo')) {
            $query->where('ID_Grupo', $request->grupo);
        }
        if ($request->filled('semestre')) {
            $query->whereHas('grupo', function ($q) use ($request) {
                $q->where('ID_Semestre', $request->semestre);
            });
        }
        $alumnos = $query->with(['grupo.semestre'])->paginate(10);
        $grupos = Grupos::where('Status', 1)->get();
        $semestres = Semestre::where('Status', 1)->get();
        return view('alumno.index', compact('alumnos', 'grupos', 'semestres'));
    }
}
