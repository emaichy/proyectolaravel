@extends('layouts.admin')

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
                                <button class="btn btn-sm btn-success asignar-paciente-btn"
                                    data-alumno-id="{{ $alumno->Matricula }}"
                                    data-alumno-nombre="{{ $alumno->Nombre }} {{ $alumno->ApePaterno }}">
                                    Asignar Paciente
                                </button>
                                <a href="{{ route('alumnos.show', $alumno->Matricula) }}"
                                    class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('alumnos.edit', $alumno->Matricula) }}"
                                    class="btn btn-sm btn-primary">Editar</a>
                                <button type="button" class="btn btn-sm btn-danger btn-eliminar-alumno"
                                    data-id="{{ $alumno->Matricula }}"
                                    data-url="{{ route('alumnos.destroy', $alumno->Matricula) }}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $alumnos->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
        <div class="modal fade" id="modalAsignarPaciente" tabindex="-1" aria-labelledby="modalAsignarPacienteLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="formAsignarPaciente">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAsignarPacienteLabel">Asignar Paciente a <span
                                    id="alumnoNombre"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="alumno_id" id="modalAlumnoId">
                            <div class="mb-3">
                                <label for="pacienteSelect" class="form-label">Paciente:</label>
                                <select class="form-select" id="pacienteSelect" name="paciente_id" style="width:100%;">
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Asignar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-eliminar-alumno').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const alumnoId = btn.getAttribute('data-id');
                    const url = btn.getAttribute('data-url');
                    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');

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
                                    'X-CSRF-TOKEN': csrf,
                                    'X-Requested-With': 'XMLHttpRequest',
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
                                    const row = document.getElementById('row-' +
                                        alumnoId);
                                    if (row) row.remove();
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
        $(document).ready(function() {
            $('.asignar-paciente-btn').on('click', function() {
                let alumnoId = $(this).data('alumno-id');
                let nombre = $(this).data('alumno-nombre');
                $('#modalAlumnoId').val(alumnoId);
                $('#alumnoNombre').text(nombre);
                $.get("{{ route('pacientes.list') }}", function(data) {
                    let select = $('#pacienteSelect');
                    select.empty();
                    data.forEach(function(pac) {
                        select.append(
                            `<option value="${pac.ID_Paciente}">${pac.Nombre} ${pac.ApePaterno} ${pac.ApeMaterno}</option>`
                        );
                    });
                });

                $('#modalAsignarPaciente').modal('show');
            });
            $('#formAsignarPaciente').on('submit', function(e) {
                e.preventDefault();
                let datos = $(this).serialize();
                $.post("{{ route('asignaciones.store') }}", datos, function(response) {
                    Swal.fire('¡Listo!', 'Paciente asignado correctamente.', 'success');
                    $('#modalAsignarPaciente').modal('hide');
                }).fail(function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Ocurrió un error', 'error');
                });
            });
        });
    </script>
@endsection
