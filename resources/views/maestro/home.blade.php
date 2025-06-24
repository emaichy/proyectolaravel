@extends('layouts.maestro')

@section('title', 'Inicio - Panel de Maestros')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <img src="{{ asset('images/teacher_welcome.svg') }}" alt="Bienvenido Maestro" class="mb-4"
                            style="max-width:120px;">
                        <h1 class="display-5 mb-3">
                            @if (auth()->user()->maestro->Genero == 'Masculino')
                                ¡Bienvenido, {{ auth()->user()->maestro->Nombre }}!
                            @else
                                ¡Bienvenida, {{ auth()->user()->maestro->Nombre }}!
                            @endif
                        </h1>
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
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection
