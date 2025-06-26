@extends('layouts.admin')

@section('title', 'Gestionar Grupos del Maestro')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">
                <i class="bi bi-people-fill"></i> Gestionar Grupos: {{ $maestro->Nombre }} {{ $maestro->ApePaterno }}
            </h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Grupos Asignados</h4>
                    @if($gruposAsignados->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Semestre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gruposAsignados as $grupo)
                                        <tr>
                                            <td>{{ $grupo->NombreGrupo }}</td>
                                            <td>{{ $grupo->semestre->Semestre }}</td>
                                            <td>
                                                <form action="{{ route('maestros.desasignar-grupo', ['maestro' => $maestro->ID_Maestro, 'grupo' => $grupo->ID_Grupo]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> Desasignar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No hay grupos asignados</div>
                    @endif
                </div>

                <div class="col-md-6">
                    <h4>Asignar Nuevo Grupo</h4>
                    <form action="{{ route('maestros.asignar-grupo', $maestro->ID_Maestro) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="ID_Grupo" class="form-label">Seleccionar Grupo</label>
                            <select class="form-select" id="ID_Grupo" name="ID_Grupo" required>
                                <option value="">Seleccione un grupo</option>
                                @foreach($gruposDisponibles as $grupo)
                                    <option value="{{ $grupo->ID_Grupo }}">
                                        {{ $grupo->NombreGrupo }} - {{ $grupo->semestre->Semestre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Asignar Grupo
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('volver') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection