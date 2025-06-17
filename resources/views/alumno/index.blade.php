@extends('layouts.app')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                confirmButtonColor: '#198754'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#ffc107'
            });
        </script>
    @endif

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Lista de Alumnos</h1>
            <a href="{{ route('alumnos.create') }}" class="btn btn-success">Agregar Alumno</a>
        </div>

        <form method="GET" action="{{ route('alumnos.index') }}" class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="grupo" class="form-label">Grupo:</label>
                <select name="grupo" id="grupo" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach ($grupos as $grupo)
                        <option value="{{ $grupo->ID_Grupo }}" {{ request('grupo') == $grupo->ID_Grupo ? 'selected' : '' }}>
                            {{ $grupo->NombreGrupo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="semestre" class="form-label">Semestre:</label>
                <select name="semestre" id="semestre" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach ($semestres as $semestre)
                        <option value="{{ $semestre->ID_Semestre }}"
                            {{ request('semestre') == $semestre->ID_Semestre ? 'selected' : '' }}>
                            {{ $semestre->Semestre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        @if ($alumnos->isEmpty())
            <div class="alert alert-warning">No se encontraron alumnos con los filtros seleccionados.</div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre completo</th>
                        <th>CURP</th>
                        <th>Sexo</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Grupo</th>
                        <th>Semestre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alumnos as $alumno)
                        <tr id="row-{{ $alumno->Matricula }}">
                            <td>{{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}</td>
                            <td>{{ $alumno->Curp }}</td>
                            <td>{{ $alumno->Sexo }}</td>
                            <td>{{ \Carbon\Carbon::parse($alumno->FechaNac)->format('d/m/Y') }}</td>
                            <td>{{ $alumno->Telefono }}</td>
                            <td>
                                {{ $alumno->Direccion }},
                                #{{ $alumno->NumeroExterior }}{{ $alumno->NumeroInterior ? ' Int. ' . $alumno->NumeroInterior : '' }},
                                CP {{ $alumno->CodigoPostal }}
                            </td>
                            <td>{{ $alumno->grupo->NombreGrupo ?? 'N/A' }}</td>
                            <td>{{ $alumno->grupo->semestre->Semestre ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('alumnos.show', $alumno->Matricula) }}" class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('alumnos.edit', $alumno->Matricula) }}" class="btn btn-sm btn-primary">Editar</a>
                                <form action="{{ route('alumnos.destroy', $alumno->Matricula) }}" method="POST"
                                    class="d-inline form-eliminar" data-id="{{ $alumno->Matricula }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $alumnos->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.form-eliminar');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const alumnoId = form.getAttribute('data-id');
                    const url = form.getAttribute('action');
                    const csrfToken = form.querySelector('input[name="_token"]').value;

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡Esta acción no se puede deshacer!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    _method: 'DELETE'
                                })
                            }).then(response => {
                                if (response.ok) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Eliminado',
                                        text: 'El alumno fue eliminado correctamente.',
                                        confirmButtonColor: '#198754'
                                    });
                                    const fila = document.getElementById(
                                        `row-${alumnoId}`);
                                    if (fila) fila.remove();
                                } else {
                                    return response.json().then(data => {
                                        throw new Error(data.message ||
                                            'Error al eliminar.');
                                    });
                                }
                            }).catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error.message,
                                    confirmButtonColor: '#dc3545'
                                });
                            });
                        }
                    });
                });
            });
        });
    </script>

@endsection
