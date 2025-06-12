<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosPacientesController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\FotografiasPacientesController;
use App\Http\Controllers\MunicipiosController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\RadiografiasPacientesController;
use App\Http\Controllers\TelefonosPacientesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Middleware\AdminIsAuthenticated;
use App\Http\Middleware\AlumnoIsAuthenticated;
use App\Http\Middleware\MaestroIsAuthenticated;
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
    })->name('admin.home');

    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuariosController::class, 'index'])->name('usuarios.index');
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

    Route::get('/municipiosEstado/{estado}', [MunicipiosController::class, 'getMunicipiosByEstado']);

    Route::prefix('pacientes')->group(function () {
        Route::get('/', [PacientesController::class, 'index'])->name('pacientes.index');
        Route::get('/create', [PacientesController::class, 'create'])->name('pacientes.create');
        Route::post('/create', [PacientesController::class, 'store'])->name('pacientes.store');
        Route::get('/edit/{paciente}', [PacientesController::class, 'edit'])->name('pacientes.edit');
        Route::put('/edit/{paciente}', [PacientesController::class, 'update'])->name('pacientes.update');
        Route::get('/{paciente}', [PacientesController::class, 'show'])->name('pacientes.show');
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
        Route::get('/paciente/{id}', [DocumentosPacientesController::class, 'showByPacient'])->name('documentos.byPaciente');
        Route::get('/download/{id}', [DocumentosPacientesController::class, 'download'])->name('documentos.download');
    });

    Route::prefix('radiografias')->group(function () {
        Route::get('/', [RadiografiasPacientesController::class, 'index'])->name('radiografias.index');
        Route::post('/store', [RadiografiasPacientesController::class, 'store'])->name('radiografias.store');
        Route::post('/update/{id}', [RadiografiasPacientesController::class, 'update'])->name('radiografias.update');
        Route::delete('/delete/{id}', [RadiografiasPacientesController::class, 'destroy'])->name('radiografias.destroy');
        Route::get('/paciente/{id}', [RadiografiasPacientesController::class, 'showByPacient'])->name('radiografias.byPaciente');
        Route::get('/download/{id}', [RadiografiasPacientesController::class, 'download'])->name('radiografias.download');
    });

    Route::prefix('fotografias')->group(function(){
        Route::get('/', [FotografiasPacientesController::class, 'index'])->name('fotografias.index');
        Route::post('/store', [FotografiasPacientesController::class, 'store'])->name('fotografias.store');
        Route::post('/update/{id}', [FotografiasPacientesController::class, 'update'])->name('fotografias.update');
        Route::delete('/delete/{id}', [FotografiasPacientesController::class, 'destroy'])->name('fotografias.destroy');
        Route::get('/paciente/{id}', [FotografiasPacientesController::class, 'showByPacient'])->name('fotografias.byPaciente');
        Route::get('/download/{id}', [FotografiasPacientesController::class, 'download'])->name('fotografias.download');
    });

    Route::get('/telefonos/getAllByPaciente/{ID_Paciente}', [TelefonosPacientesController::class, 'getTelefonosByPaciente']);
});

Route::get('/create', [UsuariosController::class, 'create'])->name('usuarios.create');
Route::post('/create', [UsuariosController::class, 'store'])->name('usuarios.store');

Route::middleware(AlumnoIsAuthenticated::class)->group(function () {
    Route::get('/alumno', function () {
        return view('alumno.home');
    })->name('alumno.home');
});

Route::middleware(MaestroIsAuthenticated::class)->group(function () {
    Route::get('/maestro', function () {
        return view('maestro.home');
    })->name('maestro.home');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
