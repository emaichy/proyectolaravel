<?php

use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\AsignacionExpedienteAlumnosController;
use App\Http\Controllers\AsignacionPacientesAlumnosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosPacientesController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\FotografiasPacientesController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\MaestrosController;
use App\Http\Controllers\MunicipiosController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\RadiografiasPacientesController;
use App\Http\Controllers\TelefonosPacientesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Middleware\AdminIsAuthenticated;
use App\Http\Middleware\AlumnoIsAuthenticated;
use App\Http\Middleware\AlumnoOrAdmin;
use App\Http\Middleware\IsAuthenticated;
use App\Http\Middleware\MaestroIsAuthenticated;
use App\Http\Middleware\MaestroOrAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'redirect'])->name('home');

Route::middleware(AdminIsAuthenticated::class)->group(function () {
    Route::get('/admin', function () {
        return view('admin.home');
        session()->forget('nav_stack');
    })->name('admin.home');

    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuariosController::class, 'index'])->name('usuarios.index');
        Route::get('/create', [UsuariosController::class, 'create'])->name('usuarios.create');
        Route::post('/create', [UsuariosController::class, 'store'])->name('usuarios.store');
        Route::get('/edit/{usuario}', [UsuariosController::class, 'edit'])->name('usuarios.edit');
        Route::put('/edit/{usuario}', [UsuariosController::class, 'update'])->name('usuarios.update');
        Route::get('/{usuario}', [UsuariosController::class, 'show'])->name('usuarios.show');
        Route::delete('/delete/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
        Route::get('/restore/{id}', [UsuariosController::class, 'restore'])->name('usuarios.restore');
    });

    Route::prefix('estados')->group(function () {
        Route::get('/', [EstadosController::class, 'index'])->name('estados.index');
        Route::get('/create', [EstadosController::class, 'create'])->name('estados.create');
        Route::post('/create', [EstadosController::class, 'store'])->name('estados.store');
        Route::get('/edit/{estado}', [EstadosController::class, 'edit'])->name('estados.edit');
        Route::put('/edit/{estado}', [EstadosController::class, 'update'])->name('estados.update');
        Route::get('/{estado}', [EstadosController::class, 'show'])->name('estados.show');
        Route::delete('/delete/{id}', [EstadosController::class, 'destroy'])->name('estados.destroy');
    });

    Route::prefix('municipios')->group(function () {
        Route::get('/', [MunicipiosController::class, 'index'])->name('municipios.index');
        Route::get('/create', [MunicipiosController::class, 'create'])->name('municipios.create');
        Route::post('/create', [MunicipiosController::class, 'store'])->name('municipios.store');
        Route::get('/edit/{municipio}', [MunicipiosController::class, 'edit'])->name('municipios.edit');
        Route::put('/edit/{municipio}', [MunicipiosController::class, 'update'])->name('municipios.update');
        Route::get('/{municipio}', [MunicipiosController::class, 'show'])->name('municipios.show');
        Route::delete('/delete/{id}', [MunicipiosController::class, 'destroy'])->name('municipios.destroy');
    });

    Route::prefix('pacientes')->group(function () {
        Route::get('/list', [PacientesController::class, 'list'])->name('pacientes.list');
        Route::get('/', [PacientesController::class, 'index'])->name('pacientes.index');
        Route::get('/create', [PacientesController::class, 'create'])->name('pacientes.create');
        Route::post('/create', [PacientesController::class, 'store'])->name('pacientes.store');
        Route::get('/edit/{paciente}', [PacientesController::class, 'edit'])->name('pacientes.edit');
        Route::put('/edit/{paciente}', [PacientesController::class, 'update'])->name('pacientes.update');
        Route::delete('/delete/{id}', [PacientesController::class, 'destroy'])->name('pacientes.destroy');
    });

    Route::prefix('telefonos')->group(function () {
        Route::get('/', [TelefonosPacientesController::class, 'index'])->name('telefonos.index');
        Route::get('/create/{ID_Paciente}', [TelefonosPacientesController::class, 'create'])->name('telefonos.create');
        Route::post('/create', [TelefonosPacientesController::class, 'store'])->name('telefonos.store');
        Route::get('/edit/{telefono}', [TelefonosPacientesController::class, 'edit'])->name('telefonos.edit');
        Route::put('/edit/{telefono}', [TelefonosPacientesController::class, 'update'])->name('telefonos.update');
        Route::get('/{telefono}', [TelefonosPacientesController::class, 'show'])->name('telefonos.show');
        Route::delete('/delete/{id}', [TelefonosPacientesController::class, 'destroy'])->name('telefonos.destroy');
    });

    Route::prefix('documentos')->group(function () {
        Route::get('/', [DocumentosPacientesController::class, 'index'])->name('documentos.index');
        Route::post('/store', [DocumentosPacientesController::class, 'store'])->name('documentos.store');
        Route::post('/update/{id}', [DocumentosPacientesController::class, 'update'])->name('documentos.update');
        Route::delete('/delete/{id}', [DocumentosPacientesController::class, 'destroy'])->name('documentos.destroy');
    });

    Route::prefix('radiografias')->group(function () {
        Route::get('/', [RadiografiasPacientesController::class, 'index'])->name('radiografias.index');
        Route::post('/store', [RadiografiasPacientesController::class, 'store'])->name('radiografias.store');
        Route::post('/update/{id}', [RadiografiasPacientesController::class, 'update'])->name('radiografias.update');
        Route::delete('/delete/{id}', [RadiografiasPacientesController::class, 'destroy'])->name('radiografias.destroy');
    });

    Route::prefix('fotografias')->group(function () {
        Route::get('/', [FotografiasPacientesController::class, 'index'])->name('fotografias.index');
        Route::post('/store', [FotografiasPacientesController::class, 'store'])->name('fotografias.store');
        Route::post('/update/{id}', [FotografiasPacientesController::class, 'update'])->name('fotografias.update');
        Route::delete('/delete/{id}', [FotografiasPacientesController::class, 'destroy'])->name('fotografias.destroy');
    });

    Route::prefix('maestros')->group(function () {
        Route::get('/', [MaestrosController::class, 'index'])->name('maestros.index');
        Route::get('/create', [MaestrosController::class, 'create'])->name('maestros.create');
        Route::post('/store', [MaestrosController::class, 'store'])->name('maestros.store');
        Route::get('/edit/{maestro}', [MaestrosController::class, 'edit'])->name('maestros.edit');
        Route::put('/edit/{maestro}', [MaestrosController::class, 'update'])->name('maestros.update');
        Route::delete('/delete/{id}', [MaestrosController::class, 'destroy'])->name('maestros.destroy');
        Route::get('/{maestro}/grupos', [MaestrosController::class, 'gestionarGrupos'])->name('maestros.grupos');
        Route::post('/{maestro}/asignar-grupo', [MaestrosController::class, 'asignarGrupo'])->name('maestros.asignar-grupo');
        Route::delete('/{maestro}/desasignar-grupo/{grupo}', [MaestrosController::class, 'desasignarGrupo'])->name('maestros.desasignar-grupo');
        Route::get('/{maestro}', [MaestrosController::class, 'show'])->name('maestros.show');
    });

    Route::prefix('alumnos')->group(function () {
        Route::get('/', [AlumnosController::class, 'index'])->name('alumnos.index');
        Route::get('/create', [AlumnosController::class, 'create'])->name('alumnos.create');
        Route::post('/store', [AlumnosController::class, 'store'])->name('alumnos.store');
        Route::get('/edit/{id}', [AlumnosController::class, 'edit'])->name('alumnos.edit');
        Route::put('/edit/{id}', [AlumnosController::class, 'update'])->name('alumnos.update');
        Route::delete('/delete/{id}', [AlumnosController::class, 'destroy'])->name('alumnos.destroy');
        Route::get('/{alumno}/grupos', [AlumnosController::class, 'gestionarGrupos'])->name('alumnos.grupos');
        Route::post('/{alumno}/asignar-grupo', [AlumnosController::class, 'asignarGrupo'])->name('alumnos.asignar-grupo');
        Route::delete('/{alumno}/desasignar-grupo/{grupo}', [AlumnosController::class, 'desasignarGrupo'])->name('alumnos.desasignar-grupo');
    });

    Route::prefix('grupos')->group(function () {
        Route::get('/', [GruposController::class, 'index'])->name('grupos.index');
        Route::get('/create', [GruposController::class, 'create'])->name('grupos.create');
        Route::post('/store', [GruposController::class, 'store'])->name('grupos.store');
        Route::get('/edit/{id}', [GruposController::class, 'edit'])->name('grupos.edit');
        Route::put('/edit/{id}', [GruposController::class, 'update'])->name('grupos.update');
        Route::delete('/delete/{id}', [GruposController::class, 'destroy'])->name('grupos.destroy');
        Route::post('/{grupo}/asignar-alumnos', [GruposController::class, 'asignarAlumnos'])->name('grupos.asignar-alumnos');
        Route::post('/{grupo}/asignar-maestros', [GruposController::class, 'asignarMaestros'])->name('grupos.asignar-maestros');
        Route::delete('/{grupo}/desasignar-alumnos', [GruposController::class, 'desasignarAlumnos'])->name('grupos.desasignar-alumnos');
        Route::delete('/{grupo}/desasignar-alumno/{alumno}', [GruposController::class, 'desasignarAlumno'])->name('grupos.desasignar-alumno');
        Route::delete('/{grupo}/desasignar-maestros', [GruposController::class, 'desasignarMaestros'])->name('grupos.desasignar-maestros');
    });

    Route::get('/telefonos/getAllByPaciente/{ID_Paciente}', [TelefonosPacientesController::class, 'getTelefonosByPaciente']);
    Route::post('/asignaciones', [AsignacionExpedienteAlumnosController::class, 'store'])->name('asignaciones.store');
    Route::delete('/asignaciones/{id}', [AsignacionExpedienteAlumnosController::class, 'destroy'])->name('asignaciones.destroy');
});

Route::middleware(AlumnoIsAuthenticated::class)->group(function () {
    Route::get('/alumno', function () {
        return view('alumno.home');
    })->name('alumno.home');
    Route::get('/alumno/perfil/{id}', [AlumnosController::class, 'perfil'])->name('alumno.perfil');
    Route::post('/alumno/updateFoto/{id}', [AlumnosController::class, 'updateFoto'])->name('alumno.updateFoto');
    Route::post('/alumno/guardarFirma/{id}', [AlumnosController::class, 'guardarFirma'])->name('alumno.guardarFirma');
    Route::get('/alumno/pacientes/{id}', [PacientesController::class, 'pacientesByAlumno'])->name('alumno.pacientes.index');
});

Route::middleware(MaestroIsAuthenticated::class)->group(function () {
    Route::get('/maestro', function () {
        return view('maestro.home');
    })->name('maestro.home');
    Route::get('/perfil/{id}', [MaestrosController::class, 'perfil'])->name('maestro.perfil');
    Route::post('/maestro/updateFoto/{id}', [MaestrosController::class, 'updateFoto'])->name('maestro.updateFoto');
    Route::post('/maestro/guardarFirma/{id}', [MaestrosController::class, 'guardarFirma'])->name('maestro.guardarFirma');
    Route::get('/grupos/{id}', [GruposController::class, 'gruposByMaestro'])->name('maestro.grupos.index');
    Route::get('/alumnos_maestro/{maestro}', [AlumnosController::class, 'alumnosByMaestro'])->name('maestro.alumnos.index');
});

Route::middleware(MaestroOrAdmin::class)->group(function () {
    Route::prefix('grupos_maestros')->group(function () {
        Route::get('/{grupo}', [GruposController::class, 'show'])->name('grupos.show');
        Route::get('/ajax/{id}', [GruposController::class, 'ajaxShow'])->name('grupos.ajax-show');
    });
    Route::get('/alumnos/{alumno}', [AlumnosController::class, 'show'])->name('alumnos.show');
});

Route::middleware(AlumnoOrAdmin::class)->group(function () {
    
});

Route::middleware(IsAuthenticated::class)->group(function () {
    Route::get('/alumno/paciente/{paciente}', [PacientesController::class, 'show'])->name('pacientes.show');
    Route::get('/alumno/paciente/{paciente}/documentos', [DocumentosPacientesController::class, 'showByPacient'])->name('documentos.byPaciente');
    Route::get('/alumno/paciente/{paciente}/documentos/download/{documento}', [DocumentosPacientesController::class, 'download'])->name('documentos.download');
    Route::get('/alumno/paciente/{paciente}/radiografias', [RadiografiasPacientesController::class, 'showByPacient'])->name('radiografias.byPaciente');
    Route::get('/alumno/paciente/{paciente}/radiografias/download/{radiografia}', [RadiografiasPacientesController::class, 'download'])->name('radiografias.download');
    Route::get('/alumno/paciente/{paciente}/fotografias', [FotografiasPacientesController::class, 'showByPacient'])->name('fotografias.byPaciente');
    Route::get('/alumno/paciente/{paciente}/fotografias/download/{fotografia}', [FotografiasPacientesController::class, 'download'])->name('fotografias.download');
    Route::get('/expedientes/{expediente}', [ExpedienteController::class, 'show'])->name('expedientes.show');
    Route::get('/municipiosEstado/{estado}', [MunicipiosController::class, 'getMunicipiosByEstado']);
    Route::get('/volver', [NavigationController::class, 'back'])->name('volver');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// CREACION Y VIZUALIZACION DE DOCUMENTOS  PARA EL EXPEDIENTE
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\NotasEvolucionController;
use App\Http\Controllers\ConsentimientoController;
//📁 Rutas de Documentos Generales
Route::get('/documentoss', [DocumentosController::class, 'index'])->name('documentoss.index');
    Route::get('/maestro/documentoss', [NotasEvolucionController::class, 'index'])
        ->name('documentoss.index');
// RUTAS PARA ALUMNOS
Route::middleware(['auth', 'alumno'])->group(function () {
    // Buscar notas
    Route::get('/alumno/documentoss', [DocumentosController::class, 'indexAlumno'])
        ->name('alumno.documentoss.index');
    Route::get('/notas-evolucion/buscar', [NotasEvolucionController::class, 'buscar'])
        ->name('notasevolucion.buscar');
    Route::get('/alumno/pacientes', [AlumnosController::class, 'misPacientes'])->name('alumno.pacientes.index');
    // Notas de evolución (CRUD)
    Route::resource('notasevolucion', NotasEvolucionController::class);
    // Consentimientos (CRUD)
    Route::resource('consentimiento', ConsentimientoController::class);
});
// RUTAS PARA MAESTROS
Route::middleware(['auth', 'maestro'])->group(function () {
// Ver Index de todos los Documentos
Route::get('/maestro/documentos/{matricula}', [DocumentosController::class, 'index'])
    ->name('maestro.documentos.index');
// NOTAS DE EVOLUCIÓN
Route::get('/maestro/notasevolucion', [NotasEvolucionController::class, 'index'])->name('maestro.notasevolucion.index');
Route::get('/maestro/notasevolucion/{notasevolucion}/edit', [NotasEvolucionController::class, 'edit'])->name('maestro.notasevolucion.edit');
Route::put('/maestro/notasevolucion/{notasevolucion}', [NotasEvolucionController::class, 'update'])->name('maestro.notasevolucion.update');
Route::get('/maestro/alumno/{matricula}/notasevolucion', [NotasEvolucionController::class, 'verNotasAlumno'])
->name('maestro.notasevolucion.alumno');
// CONSENTIMIENTOS
Route::get('/maestro/consentimiento', [ConsentimientoController::class, 'index'])->name('maestro.consentimiento.index');
Route::get('/maestro/consentimiento/{consentimiento}/edit', [ConsentimientoController::class, 'edit'])->name('maestro.consentimiento.edit');
Route::put('/maestro/consentimiento/{consentimiento}', [ConsentimientoController::class, 'update'])->name('maestro.consentimiento.update');
Route::get('/maestro/alumno/{matricula}/consentimientos', [ConsentimientoController::class, 'verConsentimientosAlumno'])->name('maestro.consentimiento.alumno');

});