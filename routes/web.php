<?php

use App\Http\Controllers\UsuariosController;
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
    return view('welcome');
});
Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/create', [UsuariosController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios/create', [UsuariosController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/edit/{usuario}', [UsuariosController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/edit/{usuario}', [UsuariosController::class, 'update'])->name('usuarios.update');
Route::get('/usuario/{usuario}', [UsuariosController::class, 'show'])->name('usuarios.show');
Route::delete('/usuarios/delete/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');