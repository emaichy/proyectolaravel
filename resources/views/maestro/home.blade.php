@extends('layouts.app')

@section('title', 'Inicio - Panel de Maestros')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <img src="{{ asset('images/teacher_welcome.svg') }}" alt="Bienvenido Maestro" class="mb-4"
                            style="max-width:120px;">
                        <h1 class="display-5 mb-3">¡Bienvenido, Maestro!</h1>
                        <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm mb-3"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Cerrar sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <p class="lead mb-4">
                            Este es tu panel principal. Aquí podrás gestionar tus clases, consultar el historial de tus
                            alumnos y acceder a recursos educativos.
                        </p>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <a href="" class="btn btn-primary btn-block py-3">
                                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i><br>
                                    Mis Clases
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="" class="btn btn-success btn-block py-3">
                                    <i class="fas fa-user-graduate fa-2x mb-2"></i><br>
                                    Alumnos
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="" class="btn btn-info btn-block py-3">
                                    <i class="fas fa-book-open fa-2x mb-2"></i><br>
                                    Recursos
                                </a>
                            </div>
                        </div>
                        <hr>
                        <p class="text-muted mb-0">
                            ¿Necesitas ayuda? Visita la sección de <a href="">Ayuda</a> o contacta al
                            soporte.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
