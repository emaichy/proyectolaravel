@extends('layouts.app')

@section('title', 'Panel Administrativo')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col text-center">
            <h1 class="display-4 font-weight-bold">Bienvenido al Panel Administrativo</h1>
            <p class="lead text-muted">Gestione usuarios, revise reportes y administre el sistema de manera eficiente.</p>
        </div>
    </div>
    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Cerrar sesión</button>
                    </form>
    <div class="row justify-content-center">
        <!-- Gestión de Usuarios -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Usuarios</h5>
                    <p class="card-text">Administre los usuarios del sistema, cree, edite o elimine cuentas.</p>
                    <a href="/usuarios" class="btn btn-primary">Gestionar Usuarios</a>
                </div>
            </div>
        </div>
        <!-- Reportes -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Revise y genere reportes detallados sobre la actividad del sistema.</p>
                    <a href="" class="btn btn-success">Ver Reportes</a>
                </div>
            </div>
        </div>
        <!-- Configuración -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-cogs fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">Configuración</h5>
                    <p class="card-text">Ajuste las preferencias y configuraciones generales del sistema.</p>
                    <a href="" class="btn btn-warning text-white">Configurar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Font Awesome CDN para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush