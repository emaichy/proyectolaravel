@extends('layouts.app')

@section('title', 'Inicio Alumno')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h2>Bienvenido, Alumno</h2>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline float-end">
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm">Cerrar sesión</button>
                    </form>
                </div>
                <div class="card-body">
                    <p class="lead text-center mb-4">
                        Accede a tus datos académicos, consulta tu historial clínico y mantente informado sobre tus actividades.
                    </p>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <a href="" class="text-decoration-none text-dark">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="fas fa-user-graduate fa-2x mb-2 text-primary"></i>
                                        <h5 class="card-title">Mi Perfil</h5>
                                        <p class="card-text">Consulta y edita tu información personal.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="" class="text-decoration-none text-dark">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="fas fa-notes-medical fa-2x mb-2 text-success"></i>
                                        <h5 class="card-title">Historial Clínico</h5>
                                        <p class="card-text">Revisa tu historial clínico y citas médicas.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="" class="text-decoration-none text-dark">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="fas fa-calendar-alt fa-2x mb-2 text-warning"></i>
                                        <h5 class="card-title">Actividades</h5>
                                        <p class="card-text">Mantente al tanto de tus actividades y eventos.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">Historial Clínico IUFIM &copy; {{ date('Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush