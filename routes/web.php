<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\MunicipiosController;
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
});

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
