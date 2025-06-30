<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Estados;
use App\Models\Grupos;
use App\Models\Maestros;
use App\Models\Municipios;
use App\Models\Pacientes;
use App\Models\Semestre;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AlumnosController extends Controller
{
    public function index(Request $request)
    {
        $current = $request->fullUrl();
        session()->put('nav_stack', [$current]);
        $query = Alumnos::where('Status', 1);
        if ($request->filled('grupo')) {
            $query->where('ID_Grupo', $request->grupo);
        }
        if ($request->filled('semestre')) {
            $query->whereHas('grupo', function ($q) use ($request) {
                $q->where('ID_Semestre', $request->semestre);
            });
        }
        $alumnos = $query->with(['grupo'])->orderBy('Nombre', 'asc')->paginate(10);
        if ($alumnos->isEmpty() && $alumnos->currentPage() > 1) {
            return redirect()->route('alumnos.index', array_merge($request->except('page'), [
                'page' => $alumnos->lastPage()
            ]));
        }
        $grupos = Grupos::where('Status', 1)->get();
        $semestres = Semestre::where('Status', 1)->get();
        return view('alumno.index', compact('alumnos', 'grupos', 'semestres'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $alumno = Alumnos::with([
            'grupo',
            'usuario',
            'estado',
            'municipio',
            'asignaciones.expediente.paciente'
        ])->findOrFail($id);
        if ($user->Rol === 'Maestro') {
            $maestro = Maestros::where('ID_Usuario', $user->ID_Usuario)->firstOrFail();
            $pertenece = $maestro->grupos()
                ->where('grupos.ID_Grupo', $alumno->ID_Grupo)
                ->exists();
            if (!$pertenece) {
                return back()->with('error', 'No tienes permiso para ver este alumno.');
            }
            $layout = 'layouts.maestro';
        } else {
            $layout = 'layouts.admin';
        }
        $gruposDisponibles = Grupos::whereDoesntHave('alumnos')
            ->orWhere('ID_Grupo', $alumno->ID_Grupo)
            ->with('semestre')
            ->get();
        $expedientesAsignados = $alumno->asignaciones->pluck('ID_Expediente');
        $pacientesDisponibles = Pacientes::whereHas('expedientes', function ($q) use ($expedientesAsignados) {
            $q->whereNotIn('ID_Expediente', $expedientesAsignados);
        })
            ->get();
        return view('alumno.show', compact('alumno', 'gruposDisponibles', 'pacientesDisponibles', 'layout'));
    }

    public function create()
    {
        $estados = Estados::where('Status', 1)->get();
        $municipios = Municipios::where('Status', 1)->get();
        return view('alumno.create', compact('estados', 'municipios'));
    }

    public function store(Request $request)
    {
        $usuario = Usuarios::create([
            'Correo' => $request->Correo,
            'password' => Hash::make($request->password),
        ]);
        Alumnos::create([
            'Nombre' => $request->Nombre,
            'ApePaterno' => $request->ApePaterno,
            'ApeMaterno' => $request->ApeMaterno,
            'FechaNac' => $request->FechaNac,
            'Sexo' => $request->Sexo,
            'Direccion' => $request->Direccion,
            'NumeroExterior' => $request->NumeroExterior,
            'NumeroInterior' => $request->NumeroInterior,
            'CodigoPostal' => $request->CodigoPostal,
            'Telefono' => $request->Telefono,
            'Pais' => $request->Pais,
            'Curp' => $request->Curp,
            'ID_Usuario' => $usuario->ID_Usuario,
            'ID_Estado' => $request->ID_Estado,
            'ID_Municipio' => $request->ID_Municipio,
        ]);
        return redirect()->route('alumnos.index')->with('success', 'Alumno creado exitosamente.');
    }

    public function edit($id)
    {
        $alumno = Alumnos::find($id);
        if (!$alumno) {
            return redirect()->route('alumnos.index')->with('error', 'Alumno no encontrado.');
        }
        $usuario = $alumno->usuario;
        if (!$usuario) {
            return redirect()->route('alumnos.index')->with('error', 'Usuario no encontrado para el alumno.');
        }
        $user = auth()->user();
        $rol = $user->Rol;
        if ($rol === 'Alumno' && $user->alumno && $user->alumno->Matricula != $alumno->Matricula) {
            return redirect()->route('alumnos.index')->with('error', 'No tienes permiso para editar este alumno.');
        }
        $estados = Estados::where('Status', 1)->get();
        $municipios = Municipios::where('Status', 1)->get();
        $layout = ($rol === 'Administrativo') ? 'layouts.admin' : 'layouts.alumno';
        return view('alumno.edit', compact('alumno', 'estados', 'municipios', 'layout'));
    }

    public function update(Request $request, $id)
    {
        try {
            $alumno = Alumnos::find($id);
            if (!$alumno) {
                return back()->with('error', 'Alumno no encontrado.');
            }

            $nombreCompleto = trim("{$alumno->Nombre} {$alumno->ApePaterno} {$alumno->ApeMaterno}");
            $carpetaAlumno = Str::slug($nombreCompleto);

            // Procesar foto de perfil
            if ($request->hasFile('Foto_Alumno')) {
                $foto = $request->file('Foto_Alumno');
                $folderPerfil = public_path("alumnos/{$carpetaAlumno}/perfil");
                if (!file_exists($folderPerfil)) mkdir($folderPerfil, 0777, true);
                // Elimina la foto anterior si existe
                if ($alumno->Foto_Alumno && file_exists(public_path($alumno->Foto_Alumno))) {
                    unlink(public_path($alumno->Foto_Alumno));
                }
                $ext = $foto->getClientOriginalExtension();
                $nombreFoto = 'perfil_' . time() . '.' . $ext;
                $foto->move($folderPerfil, $nombreFoto);
                $alumno->Foto_Alumno = "alumnos/{$carpetaAlumno}/perfil/{$nombreFoto}";
            }

            // Guardar firma sólo si no existe aún
            if (!$alumno->Firma && $request->filled('firma')) {
                $firmaBase64 = $request->input('firma');
                if (preg_match('/^data:image\/(\w+);base64,/', $firmaBase64, $type)) {
                    $extension = strtolower($type[1]) === 'jpeg' ? 'jpg' : $type[1]; // Por si acaso
                    $data = substr($firmaBase64, strpos($firmaBase64, ',') + 1);
                    $data = base64_decode($data);

                    if ($data !== false) {
                        $folderFirma = public_path("alumnos/{$carpetaAlumno}/firma");
                        if (!file_exists($folderFirma)) mkdir($folderFirma, 0777, true);
                        $nombreFirma = 'firma_' . time() . '.' . $extension;
                        file_put_contents("{$folderFirma}/{$nombreFirma}", $data);
                        // Opcional: chmod("{$folderFirma}/{$nombreFirma}", 0644);
                        $alumno->Firma = "alumnos/{$carpetaAlumno}/firma/{$nombreFirma}";
                    }
                }
            }

            $user = auth()->user();
            $rol = $user->Rol;

            if ($rol === 'Administrativo') {
                // Admin puede actualizar todo
                $alumno->Nombre = $request->Nombre;
                $alumno->ApePaterno = $request->ApePaterno;
                $alumno->ApeMaterno = $request->ApeMaterno;
                $alumno->FechaNac = $request->FechaNac;
                $alumno->Sexo = $request->Sexo;
                $alumno->Direccion = $request->Direccion;
                $alumno->NumeroExterior = $request->NumeroExterior;
                $alumno->NumeroInterior = $request->NumeroInterior;
                $alumno->CodigoPostal = $request->CodigoPostal;
                $alumno->Telefono = $request->Telefono;
                $alumno->Pais = $request->Pais;
                $alumno->Curp = $request->Curp;
                $alumno->ID_Estado = $request->ID_Estado;
                $alumno->ID_Municipio = $request->ID_Municipio;
                $usuario = $alumno->usuario;
                if ($usuario) {
                    $usuario->Correo = $request->Correo;
                    if ($request->filled('password')) {
                        $usuario->password = Hash::make($request->password);
                    }
                    $usuario->save();
                }
            } else {
                // Alumno sólo puede actualizar estos campos
                $alumno->Direccion = $request->Direccion;
                $alumno->NumeroExterior = $request->NumeroExterior;
                $alumno->NumeroInterior = $request->NumeroInterior;
                $alumno->CodigoPostal = $request->CodigoPostal;
                $alumno->Telefono = $request->Telefono;
            }

            $alumno->save();

            return back()->with('success', 'Datos actualizados correctamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $alumno = Alumnos::find($id);
        if (!$alumno) {
            return redirect()->route('alumnos.index')->with('error', 'Alumno no encontrado.');
        }
        $alumno->Status = 0;
        $alumno->save();
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado exitosamente.');
    }

    public function gestionarGrupo(Alumnos $alumno)
    {
        $alumno = Alumnos::find($alumno->ID_Alumno);
        if (!$alumno) {
            return back()->with('error', 'Alumno no encontrado.');
        }
        $grupoAsignado = $alumno->ID_Grupo->first();
        if ($grupoAsignado) {
            return back()->with('error', 'El alumno ya tiene un grupo asignado.');
        }
        $gruposDisponibles = Grupos::whereDoesNotHave('alumnos')
            ->orWhere('ID_Grupo', $alumno->ID_Grupo)
            ->with('semestre')
            ->get();
        return view('alumno.gestionargrupo', [
            'alumno' => $alumno,
            'grupoAsignado' => $grupoAsignado,
            'gruposDisponibles' => $gruposDisponibles
        ]);
    }

    public function asignarGrupo(Request $request, Alumnos $alumno)
    {
        $request->validate([
            'ID_Grupo' => 'required|exists:grupos,ID_Grupo',
        ]);
        $alumno = Alumnos::find($alumno->Matricula);
        if (!$alumno) {
            return back()->with('error', 'Alumno no encontrado.');
        }
        $alumno->ID_Grupo = $request->ID_Grupo;
        $alumno->save();
        return back()->with('success', 'Grupo asignado exitosamente al alumno.');
    }


    public function desasignarGrupo(Alumnos $alumno)
    {
        $alumno = Alumnos::find($alumno->Matricula);
        if (!$alumno) {
            return back()->with('error', 'Alumno no encontrado.');
        }
        $alumno->ID_Grupo = null;
        $alumno->save();
        return back()->with('success', 'Grupo desasignado exitosamente del alumno.');
    }

    public function alumnosByMaestro($maestroId, Request $request)
    {
        $user = auth()->user();
        $current = $request->fullUrl();
        session()->put('nav_stack', [$current]);
        if ($user->Rol === 'Maestro') {
            if (!$user->maestro || $user->maestro->ID_Maestro != $maestroId) {
                return redirect()->route('maestro.home')
                    ->with('error', 'No tienes permiso para ver los alumnos de este maestro.');
            }
        }
        $maestro = Maestros::findOrFail($maestroId);
        $alumnos = Alumnos::whereIn('ID_Grupo', $maestro->grupos()->pluck('grupos.ID_Grupo'))
            ->where('Status', 1)
            ->orderBy('Nombre', 'asc')
            ->paginate(10);
        return view('maestro.alumnos', compact('alumnos'));
    }

    public function perfil($alumnoId, Request $request)
    {
        $user = auth()->user();
        $current = $request->fullUrl();
        session()->put('nav_stack', [$current]);
        $alumno = Alumnos::find($alumnoId);
        if (!$alumno) {
            return redirect()->back()->with('error', 'Alumno no encontrado.');
        }
        $alumnoAuth = auth()->user()->alumno;
        if (!$alumnoAuth || $alumnoAuth->Matricula != $alumno->Matricula) {
            return redirect()->back()->with('error', 'No tienes permiso para ver este perfil.');
        }
        $estados = Estados::where('Status', 1)->get();
        $municipios = collect();
        if ($estados->isNotEmpty()) {
            $municipios = $estados->first()->municipios()->where('Status', 1)->get();
        }
        return view('alumno.perfil', compact('alumno', 'estados', 'municipios'));
    }

    public function updateFoto(Request $request, $id)
    {
        $alumno = Alumnos::findOrFail($id);
        if (auth()->user()->id !== $alumno->usuario->id) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $nombreCompleto = $alumno->Nombre . $alumno->ApePaterno . $alumno->ApeMaterno;
        $carpetaAlumno = Str::slug($nombreCompleto);
        $folderPerfil = public_path("alumnos/{$carpetaAlumno}/perfil");
        if (!file_exists($folderPerfil)) mkdir($folderPerfil, 0777, true);
        if ($alumno->Foto_Alumno && file_exists(public_path($alumno->Foto_Alumno))) {
            unlink(public_path($alumno->Foto_Alumno));
        }
        $foto = $request->file('foto');
        $ext = $foto->getClientOriginalExtension();
        $nombreFoto = 'perfil_' . time() . '.' . $ext;
        $foto->move($folderPerfil, $nombreFoto);
        $alumno->Foto_Alumno = "alumnos/{$carpetaAlumno}/perfil/{$nombreFoto}";
        $alumno->save();
        return response()->json([
            'success' => true,
            'foto_url' => asset($alumno->Foto_Alumno)
        ]);
    }

    public function guardarFirma(Request $request, $id)
    {
        $alumno = Alumnos::findOrFail($id);
        if (auth()->user()->id !== $alumno->usuario->id) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }
        $request->validate([
            'firma' => 'required|string'
        ]);
        if ($alumno->Firma) {
            return response()->json(['error' => 'Ya tienes firma registrada.'], 400);
        }
        $firmaBase64 = $request->input('firma');
        if (preg_match('/^data:image\/(\w+);base64,/', $firmaBase64, $type)) {
            $extension = $type[1] == 'jpeg' ? 'jpg' : $type[1];
            $data = substr($firmaBase64, strpos($firmaBase64, ',') + 1);
            $data = base64_decode($data);
            $nombreCompleto = $alumno->Nombre . $alumno->ApePaterno . $alumno->ApeMaterno;
            $carpetaAlumno = Str::slug($nombreCompleto);
            $folderFirma = public_path("alumnos/{$carpetaAlumno}/firma");
            if (!file_exists($folderFirma)) mkdir($folderFirma, 0777, true);
            $nombreFirma = 'firma_' . time() . '.png';
            file_put_contents("{$folderFirma}/{$nombreFirma}", $data);
            $alumno->Firma = "alumnos/{$carpetaAlumno}/firma/{$nombreFirma}";
            $alumno->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Formato de imagen inválido.'], 422);
        }
    }
}
