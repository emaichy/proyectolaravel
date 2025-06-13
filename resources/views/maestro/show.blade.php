@extends('layouts.app')

@section('title', 'Detalle del Maestro')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h2 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Detalle del Maestro
                </h2>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        @php
                            $foto =
                                $maestro->Foto_Maestro && file_exists(public_path($maestro->Foto_Maestro))
                                    ? asset($maestro->Foto_Maestro)
                                    : asset('avatar.png');
                        @endphp

                        <img src="{{ $foto }}" alt="Foto del maestro" class="img-fluid rounded-circle shadow"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ $maestro->Nombre }} {{ $maestro->ApePaterno }} {{ $maestro->ApeMaterno }}
                        </h3>
                        <p class="mb-1"><strong>Email:</strong> {{ $maestro->usuario->Correo }}</p>
                        <p class="mb-1"><strong>Teléfono:</strong> {{ $maestro->Telefono }}</p>
                        <p class="mb-1"><strong>Dirección:</strong>
                            {{ $maestro->Direccion . ' ' . $maestro->NumeroInterior . ', ' . $maestro->NumeroExterior . ', ' . $maestro->CodigoPostal . ', ' . $maestro->municipio->NombreMunicipio . ', ' . $maestro->estado->NombreEstado . ', ' . $maestro->Pais }}
                        </p>
                        <p class="mb-1"><strong>Especialidad:</strong> {{ $maestro->Especialidad ?? 'No especificada' }}
                        </p>
                    </div>
                </div>
                <hr>
                <ul class="nav nav-tabs" id="maestroTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="grupos-tab" data-bs-toggle="tab" data-bs-target="#grupos"
                            type="button" role="tab">Grupos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="alumnos-tab" data-bs-toggle="tab" data-bs-target="#alumnos"
                            type="button" role="tab">Alumnos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="calificaciones-tab" data-bs-toggle="tab"
                            data-bs-target="#calificaciones" type="button" role="tab">Calificaciones</button>
                    </li>
                </ul>
                {{--<div class="tab-content p-3 border border-top-0 rounded-bottom" id="maestroTabsContent">
                    <div class="tab-pane fade show active" id="grupos" role="tabpanel">
                        <h4 class="mb-3">Grupos asignados</h4>
                        @if ($maestro->grupos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nombre del Grupo</th>
                                            <th>Materia</th>
                                            <th>Horario</th>
                                            <th>Periodo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($maestro->grupos as $grupo)
                                            <tr>
                                                <td>{{ $grupo->NombreGrupo }}</td>
                                                <td>{{ $grupo->materia->NombreMateria }}</td>
                                                <td>{{ $grupo->Horario }}</td>
                                                <td>{{ $grupo->periodo->NombrePeriodo }}</td>
                                                <td>
                                                    <a href="{{ route('grupos.show', $grupo->ID_Grupo) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i> Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">Este maestro no tiene grupos asignados.</div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="alumnos" role="tabpanel">
                        <h4 class="mb-3">Alumnos bajo su tutoría</h4>
                        @if ($maestro->alumnos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Matrícula</th>
                                            <th>Grupo</th>
                                            <th>Promedio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($maestro->alumnos as $alumno)
                                            <tr>
                                                <td>{{ $alumno->Nombre }} {{ $alumno->ApePaterno }}
                                                    {{ $alumno->ApeMaterno }}</td>
                                                <td>{{ $alumno->Matricula }}</td>
                                                <td>{{ $alumno->grupo->NombreGrupo ?? 'Sin grupo' }}</td>
                                                <td>{{ $alumno->promedio ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('alumnos.show', $alumno->ID_Alumno) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i> Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">Este maestro no tiene alumnos asignados.</div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="calificaciones" role="tabpanel">
                        <h4 class="mb-3">Asignar Calificaciones</h4>
                        <div class="alert alert-warning">
                            Seleccione un grupo para asignar calificaciones a los alumnos.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="selectGrupo" class="form-label">Grupo</label>
                                    <select class="form-select" id="selectGrupo">
                                        <option selected disabled>Seleccione un grupo</option>
                                        @foreach ($maestro->grupos as $grupo)
                                            <option value="{{ $grupo->ID_Grupo }}">{{ $grupo->NombreGrupo }} -
                                                {{ $grupo->materia->NombreMateria }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="selectPeriodo" class="form-label">Periodo de evaluación</label>
                                    <select class="form-select" id="selectPeriodo">
                                        <option value="1">Primer Parcial</option>
                                        <option value="2">Segundo Parcial</option>
                                        <option value="3">Tercer Parcial</option>
                                        <option value="4">Examen Final</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="calificacionesContainer" class="mt-4" style="display: none;">
                            <h5>Lista de Alumnos</h5>
                            <form id="formCalificaciones">
                                @csrf
                                <input type="hidden" name="ID_Grupo" id="hiddenGrupo">
                                <input type="hidden" name="Periodo" id="hiddenPeriodo">

                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Alumno</th>
                                            <th>Matrícula</th>
                                            <th>Calificación</th>
                                            <th>Comentarios</th>
                                        </tr>
                                    </thead>
                                    <tbody id="alumnosCalificaciones">
                                        Aquí se cargarán los alumnos dinámicamente
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary mt-3">
                                    <i class="bi bi-save"></i> Guardar Calificaciones
                                </button>
                            </form>
                        </div>
                    </div>
                </div>--}}

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('maestros.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <div>
                        <a href="{{--{{ route('maestros.grupos', $maestro->ID_Maestro) }}--}}" class="btn btn-info me-2">
                            <i class="bi bi-people-fill"></i> Administrar Grupos
                        </a>
                        <a href="{{ route('maestros.edit', $maestro->ID_Maestro) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <form action="{{ route('maestros.destroy', $maestro->ID_Maestro) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('¿Estás seguro de eliminar este maestro?')">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#selectGrupo').change(function() {
                const grupoId = $(this).val();
                const periodo = $('#selectPeriodo').val();

                if (grupoId) {
                    $('#hiddenGrupo').val(grupoId);
                    $('#hiddenPeriodo').val(periodo);

                    $.get(`/maestros/${grupoId}/alumnos`, function(data) {
                        $('#alumnosCalificaciones').empty();

                        if (data.length > 0) {
                            data.forEach(alumno => {
                                $('#alumnosCalificaciones').append(`
                                    <tr>
                                        <td>${alumno.Nombre} ${alumno.ApePaterno} ${alumno.ApeMaterno}</td>
                                        <td>${alumno.Matricula}</td>
                                        <td>
                                            <input type="number" name="calificaciones[${alumno.ID_Alumno}]" 
                                                class="form-control" min="0" max="10" step="0.1" 
                                                value="${alumno.calificacion?.Calificacion ?? ''}">
                                        </td>
                                        <td>
                                            <input type="text" name="comentarios[${alumno.ID_Alumno}]" 
                                                class="form-control" 
                                                value="${alumno.calificacion?.Comentarios ?? ''}">
                                        </td>
                                    </tr>
                                `);
                            });

                            $('#calificacionesContainer').show();
                        } else {
                            $('#alumnosCalificaciones').append(`
                                <tr>
                                    <td colspan="4" class="text-center">No hay alumnos en este grupo</td>
                                </tr>
                            `);
                            $('#calificacionesContainer').show();
                        }
                    });
                } else {
                    $('#calificacionesContainer').hide();
                }
            });
            $('#selectPeriodo').change(function() {
                if ($('#selectGrupo').val()) {
                    $('#selectGrupo').trigger('change');
                }
            });
            $('#formCalificaciones').submit(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Guardar calificaciones?',
                    text: "¿Estás seguro de guardar estas calificaciones?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/maestros/guardar-calificaciones',
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Calificaciones guardadas!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudieron guardar las calificaciones'
                                });
                            }
                        });
                    }
                });
            });
            $('form[action*="destroy"]').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar maestro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection
