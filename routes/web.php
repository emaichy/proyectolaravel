<?php

use App\Http\Controllers\AuthController;
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

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::middleware(AdminIsAuthenticated::class)->group(function () {
    Route::get('/admin', function () {
        return view('admin.home');
    })->name('admin.home');

    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuariosController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/create', [UsuariosController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/edit/{usuario}', [UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/edit/{usuario}', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::get('/usuario/{usuario}', [UsuariosController::class, 'show'])->name('usuarios.show');
    Route::delete('/usuarios/delete/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
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
