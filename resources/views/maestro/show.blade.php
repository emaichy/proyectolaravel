@extends('layouts.admin')

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
                        <h3 class="fw-bold">{{ $maestro->Nombre }} {{ $maestro->ApePaterno }} {{ $maestro->ApeMaestro }}
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
                        <button class="nav-link active" id="alumnos-tab" data-bs-toggle="tab" data-bs-target="#alumnos"
                            type="button" role="tab">Alumnos</button>
                    </li>
                </ul>
                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="maestroTabsContent">
                    <div class="tab-pane fade show active mb-2" id="alumnos" role="tabpanel">
                        <h4 class="mb-3">Alumnos bajo su tutoría</h4>
                        @if ($maestro->grupos->isEmpty())
                            <div class="alert alert-info">Este maestro no tiene grupos asignados.</div>
                        @else
                            <div class="accordion" id="alumnosAccordion">
                                @foreach ($maestro->grupos as $grupo)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $grupo->ID_Grupo }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $grupo->ID_Grupo }}"
                                                aria-expanded="false" aria-controls="collapse{{ $grupo->ID_Grupo }}">
                                                {{ $grupo->NombreGrupo }}
                                                <span class="badge bg-primary ms-2">{{ $grupo->alumnos_count }}
                                                    alumnos</span>
                                            </button>
                                        </h2>

                                        <div id="collapse{{ $grupo->ID_Grupo }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ $grupo->ID_Grupo }}"
                                            data-bs-parent="#alumnosAccordion">
                                            <div class="accordion-body">
                                                @if ($grupo->alumnos_count > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nombre</th>
                                                                    <th>Matrícula</th>
                                                                    <th>Promedio</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($grupo->alumnos as $alumno)
                                                                    <tr>
                                                                        <td>{{ $alumno->Nombre }}
                                                                            {{ $alumno->ApePaterno }}
                                                                            {{ $alumno->ApeMaterno }}</td>
                                                                        <td>{{ $alumno->Matricula }}</td>
                                                                        <td>{{ $alumno->promedio ?? 'N/A' }}</td>
                                                                        <td>
                                                                            <a href="{{ route('alumnos.show', $alumno->Matricula) }}"
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
                                                    <div class="alert alert-warning">No hay alumnos en este grupo.</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('volver') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <div>
                            <a href="{{ route('maestros.grupos', $maestro->ID_Maestro) }}" class="btn btn-info me-2">
                                <i class="bi bi-people-fill"></i> Administrar Grupos
                            </a>
                            <a href="{{ route('maestros.edit', $maestro->ID_Maestro) }}" class="btn btn-warning me-2">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
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
