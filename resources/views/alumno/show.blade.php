@extends($layout)
@php
    $rol = Auth::user()->Rol ?? null;
@endphp
@section('title', 'Detalle del Alumno')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h2 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Detalle del Alumno
                </h2>
                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: '{{ session('success') }}',
                                confirmButtonColor: '#3085d6'
                            });
                        });
                    </script>
                @endif
                @if (session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!',
                                text: '{{ session('error') }}',
                                confirmButtonColor: '#d33'
                            });
                        });
                    </script>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        @php
                            if ($alumno->Foto_Alumno && file_exists(public_path('storage/' . $alumno->Foto_Alumno))) {
                                $foto = asset('storage/' . $alumno->Foto_Alumno);
                            } elseif ($alumno->Sexo == 'Femenino') {
                                $foto = asset('alumna.png');
                            } elseif ($alumno->Sexo == 'Masculino') {
                                $foto = asset('alumno.png');
                            } else {
                                $foto = asset('avatar.png');
                            }
                        @endphp
                        <img src="{{ $foto }}" alt="Foto del alumno" class="img-fluid rounded-circle shadow"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold">
                            {{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}
                        </h3>
                        <p class="mb-1"><strong>Matrícula:</strong> {{ $alumno->Matricula }}</p>
                        <p class="mb-1"><strong>Email:</strong>
                            {{ $alumno->usuario?->Correo ?? 'No asignado' }}
                        </p>
                        <p class="mb-1"><strong>Teléfono:</strong> {{ $alumno->Telefono }}</p>
                        <p class="mb-1"><strong>Dirección:</strong>
                            {{ $alumno->Direccion . ' ' . $alumno->NumeroInterior . ', ' . $alumno->NumeroExterior . ', ' . $alumno->CodigoPostal . ', ' . ($alumno->municipio?->NombreMunicipio ?? 'Sin municipio') . ', ' . ($alumno->estado?->NombreEstado ?? 'Sin estado') . ', ' . $alumno->Pais }}
                        </p>
                    </div>
                </div>
                <hr>
                <ul class="nav nav-tabs" id="alumnoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="grupo-tab" data-bs-toggle="tab" data-bs-target="#grupo"
                            type="button" role="tab">Grupo</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pacientes-tab" data-bs-toggle="tab" data-bs-target="#pacientes"
                            type="button" role="tab">Pacientes</button>
                    </li>
                    @if ($rol === 'Administrativo')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="acciones-tab" data-bs-toggle="tab" data-bs-target="#acciones"
                                type="button" role="tab">Acciones administrativas</button>
                        </li>
                    @endif
                </ul>
                <div class="tab-content p-3 border border-top-0 rounded-bottom mb-4" id="alumnoTabsContent">
                    <div class="tab-pane fade show active" id="grupo" role="tabpanel">
                        <h4 class="mb-3">Grupo Asignado</h4>
                        @if ($alumno->grupo)
                            <div class="mb-3">
                                <span class="badge bg-success fs-6">
                                    {{ $alumno->grupo?->NombreGrupo ?? 'Sin grupo' }}
                                </span>
                                @if ($rol === 'Administrativo')
                                    <form
                                        action="{{ route('alumnos.desasignar-grupo', ['alumno' => $alumno->Matricula, 'grupo' => $alumno->ID_Grupo]) }}"
                                        method="POST" class="d-inline ms-2 desasignar-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-x-circle"></i> Desasignar Grupo
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('grupos.show', $alumno->ID_Grupo) }}"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-people"></i> Ver Grupo
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">Este alumno no tiene grupo asignado.</div>
                            <hr>
                            @if ($rol === 'Administrativo')
                                <h5>Asignar a un Grupo</h5>
                                <form action="{{ route('alumnos.asignar-grupo', $alumno->Matricula) }}" method="POST"
                                    class="row g-2 align-items-end">
                                    @csrf
                                    <div class="col-md-8">
                                        <select name="ID_Grupo" id="ID_Grupo" class="form-select" required>
                                            <option value="">Seleccione un grupo</option>
                                            @foreach ($gruposDisponibles as $grupo)
                                                <option value="{{ $grupo->ID_Grupo }}">{{ $grupo->NombreGrupo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Asignar Grupo
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @endif
                    </div>
                    <div class="tab-pane fade" id="pacientes" role="tabpanel">
                        <h4>Pacientes Asignados</h4>
                        @if ($alumno->asignaciones->isEmpty())
                            <div class="alert alert-warning">No hay pacientes asignados a este alumno.</div>
                        @else
                            <ul class="list-group mb-3">
                                @foreach ($alumno->asignaciones as $asignacion)
                                    @if ($asignacion->paciente)
                                        <li class="list-group-item" id="asignacion-{{ $asignacion->ID_Asignacion }}">
                                            <strong>{{ $asignacion->paciente->Nombre }}
                                                {{ $asignacion->paciente->ApePaterno }}
                                                {{ $asignacion->paciente->ApeMaterno }}</strong>
                                            <span class="badge bg-info">ID: {{ $asignacion->paciente->ID_Paciente }}</span>
                                            <button class="btn btn-outline-secondary btn-sm float-end"
                                                onclick="location.href='{{ route('pacientes.show', $asignacion->paciente->ID_Paciente) }}'">
                                                <i class="bi bi-eye"></i> Ver Paciente
                                            </button>
                                            @if ($rol === 'Administrativo')
                                                <form
                                                    action="{{ route('asignaciones.destroy', $asignacion->ID_Asignacion) }}"
                                                    method="POST" class="d-inline form-eliminar-asignacion"
                                                    data-id="{{ $asignacion->ID_Asignacion }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-outline-danger btn-sm float-end me-2">
                                                        <i class="bi bi-trash"></i> Eliminar Asignación
                                                    </button>
                                                </form>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @if ($rol === 'Administrativo')
                        <div class="tab-pane fade" id="acciones" role="tabpanel">
                            <h4 class="mb-3">Acciones administrativas</h4>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <form action="#" method="POST" class="reset-password-form d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-repeat"></i> Resetear contraseña
                                        </button>
                                    </form>
                                </li>
                                <li class="list-group-item">
                                    <a href="#" class="btn btn-outline-info">
                                        <i class="bi bi-file-earmark-text"></i> Ver historial académico
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#" class="btn btn-outline-warning">
                                        <i class="bi bi-lock"></i> Bloquear acceso
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <form action="{{ route('alumnos.destroy', $alumno->Matricula) }}" method="POST"
                                        class="eliminar-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Eliminar Alumno
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
@endsection
@if ($rol === 'Administrativo')
    @section('scripts')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(function() {
                $('form[action*="destroy"]:not(.form-eliminar-asignacion)').submit(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar alumno?',
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
                $('.form-eliminar-asignacion').submit(function(e) {
                    e.preventDefault();
                    const form = this;
                    const asignacionId = $(form).data('id');
                    Swal.fire({
                        title: '¿Eliminar asignación?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: $(form).attr('action'),
                                type: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    $('#asignacion-' + asignacionId).fadeOut(300,
                                        function() {
                                            $(this).remove();
                                        });
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Asignación eliminada',
                                        text: 'La asignación fue eliminada correctamente.',
                                        confirmButtonColor: '#198754'
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: xhr.responseJSON?.message ||
                                            'Ocurrió un error al eliminar.',
                                        confirmButtonColor: '#dc3545'
                                    });
                                }
                            });
                        }
                    });
                });
                $('.desasignar-form').submit(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Desasignar grupo?',
                        text: "El alumno quedará sin grupo asignado.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, desasignar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
                $('.reset-password-form').submit(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Resetear contraseña?',
                        text: "Se enviará una nueva contraseña al correo del alumno.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, resetear',
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
@endif
