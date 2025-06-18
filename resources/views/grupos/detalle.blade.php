{{-- grupos/partials/detalle.blade.php --}}
<div class="card shadow-lg mb-4 border-0">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Grupo: {{ $grupo->NombreGrupo }}</h2>
    </div>
    <div class="card-body">
        <h5 class="fw-bold">Maestro a cargo:</h5>
        <p>
            @if ($grupo->maestro)
                {{ $grupo->maestro->Nombre }} {{ $grupo->maestro->ApePaterno }} {{ $grupo->maestro->ApeMaestro }}
            @else
                <em class="text-muted">No asignado</em>
            @endif
        </p>
        <h5 class="fw-bold mt-4">Alumnos del grupo:</h5>
        @if ($grupo->alumnos->count())
            <ul class="list-group">
                @foreach ($grupo->alumnos as $alumno)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}
                        </div>
                        <div>
                            <a href="{{ route('alumnos.show', $alumno->Matricula) }}"
                                class="btn btn-sm btn-outline-primary me-2">Ver</a>
                            <form
                                action="{{ route('grupos.desasignar-alumno', [$grupo->ID_Grupo, $alumno->Matricula]) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Quitar</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Este grupo no tiene alumnos asignados.</p>
        @endif
    </div>
</div>
