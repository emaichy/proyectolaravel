@extends('layouts.maestro')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-0">Alumnos asignados</h2>
                        <small class="text-muted">Listado de alumnos de tu(s) grupo(s)</small>
                    </div>
                </div>
                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Acceso denegado',
                            text: '{{ session('error') }}',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>
                @endif
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
                @if ($alumnos->count())
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Foto</th>
                                            <th>Nombre</th>
                                            <th>Matrícula</th>
                                            <th>Grupo</th>
                                            <th>Correo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alumnos as $alumno)
                                            <tr>
                                                @php
                                                    if ($alumno->Foto_Alumno) {
                                                        $foto = asset('storage/' . $alumno->Foto_Alumno);
                                                    } elseif ($alumno->Sexo == 'Masculino') {
                                                        $foto = asset('alumno.png');
                                                    } elseif ($alumno->Sexo == 'Femenino') {
                                                        $foto = asset('alumna.png');
                                                    } else {
                                                        $foto = asset('usuario.png');
                                                    }
                                                @endphp
                                                <td>
                                                    <img src="{{ $foto }}" alt="Foto Alumno" class="rounded-circle"
                                                        width="48" height="48" style="object-fit: cover;">
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">{{ $alumno->Nombre }}
                                                        {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $alumno->Matricula }}</span>
                                                </td>
                                                <td>
                                                    {{ $alumno->grupo ? $alumno->grupo->NombreGrupo : 'Sin grupo' }}
                                                </td>
                                                <td>
                                                    {{ $alumno->usuario ? $alumno->usuario->Correo : 'N/A' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('alumnos.show', $alumno->Matricula) }}"
                                                        class="btn btn-outline-info btn-sm">
                                                        <i class="bi bi-eye"></i> Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning text-center mt-5 p-4 shadow-sm" role="alert">
                        <img src="{{ asset('vacio.png') }}" alt="Sin alumnos" width="80"
                            class="mb-3">
                        <h4 class="fw-bold mb-2">¡Sin alumnos asignados!</h4>
                        <p class="mb-0">Por el momento no tienes alumnos asignados a tu(s) grupo(s).</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $alumnos->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
