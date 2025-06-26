@extends('layouts.admin')

@section('title', 'Panel Administrativo')

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="display-4 font-weight-bold">Bienvenido al Panel Administrativo</h1>
                <p class="lead text-muted">Gestione usuarios, revise reportes y administre el sistema de manera eficiente.
                </p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Cerrar sesión</button>
        </form>
        <div class="row justify-content-center">
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
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-graduate fa-3x mb-3 text-info"></i>
                        <h5 class="card-title">Alumnos</h5>
                        <p class="card-text">Consulte y gestione la información de los alumnos registrados.</p>
                        <a href="/alumnos" class="btn btn-info text-white">Ver Alumnos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-secondary"></i>
                        <h5 class="card-title">Maestros</h5>
                        <p class="card-text">Consulte y administre la información de los maestros.</p>
                        <a href="/maestros" class="btn btn-secondary text-white">Ver Maestros</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-layer-group fa-3x mb-3 text-dark"></i>
                        <h5 class="card-title">Grupos</h5>
                        <p class="card-text">Gestione los grupos y asigne alumnos y maestros.</p>
                        <a href="/grupos" class="btn btn-dark">Ver Grupos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-injured fa-3x mb-3 text-danger"></i>
                        <h5 class="card-title">Pacientes</h5>
                        <p class="card-text">Acceda y gestione los datos de los pacientes atendidos.</p>
                        <a href="/pacientes" class="btn btn-danger text-white">Ver Pacientes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-ellipsis-h fa-3x mb-3 text-muted"></i>
                        <h5 class="card-title">Otras Opciones</h5>
                        <p class="card-text">Acceda a funcionalidades adicionales del sistema.</p>
                        <a href="/otras-opciones" class="btn btn-outline-secondary">Ver Más</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush