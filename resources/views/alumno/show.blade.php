@extends('layouts.app')

@section('title', 'Detalle del Alumno')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h2 class="mb-0">
                <i class="bi bi-person-badge me-2"></i>
                Detalle del Alumno
            </h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4 text-center">
                    @php
                        $foto =
                            $alumno->Foto_Alumno && file_exists(public_path($alumno->Foto_Alumno))
                                ? asset($alumno->Foto_Alumno)
                                : asset('avatar.png');
                    @endphp

                    <img src="{{ $foto }}" alt="Foto del alumno" class="img-fluid rounded-circle shadow"
                        style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-8">
                    <h3 class="fw-bold">{{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}</h3>
                    <p class="mb-1"><strong>Matrícula:</strong> {{ $alumno->Matricula }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $alumno->usuario->Correo }}</p>
                    <p class="mb-1"><strong>Teléfono:</strong> {{ $alumno->Telefono }}</p>
                    <p class="mb-1"><strong>Dirección:</strong>
                        {{ $alumno->Direccion . ' ' . $alumno->NumeroInterior . ', ' . $alumno->NumeroExterior . ', ' . $alumno->CodigoPostal . ', ' . $alumno->municipio->NombreMunicipio . ', ' . $alumno->estado->NombreEstado . ', ' . $alumno->Pais }}
                    </p>
                    <p class="mb-1"><strong>Promedio:</strong> {{ $alumno->promedio ?? 'N/A' }}</p>
                </div>
            </div>
            <hr>
            <ul class="nav nav-tabs" id="alumnoTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="grupo-tab" data-bs-toggle="tab" data-bs-target="#grupo"
                        type="button" role="tab">Grupo</button>
                </li>
            </ul>
            <div class="tab-content p-3 border border-top-0 rounded-bottom" id="alumnoTabsContent">
                <div class="tab-pane fade show active" id="grupo" role="tabpanel">
                    <h4 class="mb-3">Grupo Asignado</h4>
                    @if ($alumno->grupo)
                        <div class="mb-3">
                            <span class="badge bg-success fs-6">
                                {{ $alumno->grupo->NombreGrupo }}
                            </span>
                            <form action="{{ route('alumnos.desasignar-grupo', $alumno->ID_Alumno) }}" method="POST" class="d-inline ms-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Desasignar este grupo del alumno?')">
                                    <i class="bi bi-x-circle"></i> Desasignar Grupo
                                </button>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('grupos.show', $alumno->grupo->ID_Grupo) }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-people"></i> Ver Grupo
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning">Este alumno no tiene grupo asignado.</div>
                    @endif

                    <hr>
                    <h5>Asignar a un Grupo</h5>
                    <form action="{{ route('alumnos.asignar-grupo', $alumno->Matricula) }}" method="POST" class="row g-2 align-items-end">
                        @csrf
                        <div class="col-md-8">
                            <select name="grupo_id" id="grupo_id" class="form-select" required>
                                <option value="">Seleccione un grupo</option>
                                @foreach($gruposDisponibles as $grupo)
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
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <div>
                    <a href="{{ route('alumnos.edit', $alumno->Matricula) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <form action="{{ route('alumnos.destroy', $alumno->Matricula) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('¿Estás seguro de eliminar este alumno?')">
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
    $(function() {
        $('form[action*="destroy"]').submit(function(e) {
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
        $('form[action*="desasignarGrupo"]').submit(function(e) {
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
    });
</script>
@endsection