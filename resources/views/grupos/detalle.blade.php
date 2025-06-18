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
            <form method="POST" action="{{ route('grupos.desasignar-alumnos', $grupo->ID_Grupo) }}">
                @csrf
                @method('DELETE')
                <ul class="list-group">
                    @foreach ($grupo->alumnos as $alumno)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" name="alumnos[]" value="{{ $alumno->Matricula }}"
                                    id="alumno_{{ $alumno->Matricula }}">
                                <label for="alumno_{{ $alumno->Matricula }}">
                                    {{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}
                                </label>
                            </div>
                            <div>
                                <a href="{{ route('alumnos.show', $alumno->Matricula) }}"
                                    class="btn btn-sm btn-outline-primary me-2">Ver</a>

                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="quitarAlumno('{{ $grupo->ID_Grupo }}', '{{ $alumno->Matricula }}', this)">
                                    Quitar
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <button type="submit" class="btn btn-danger mt-3" id="quitar-seleccionados">
                    Quitar alumnos
                </button>
            </form>
        @else
            <p class="text-muted">Este grupo no tiene alumnos asignados.</p>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function quitarAlumno(grupoId, alumnoId, btn) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Este alumno será desasignado del grupo.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/grupos/${grupoId}/desasignar-alumno/${alumnoId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('No se pudo desasignar');
                        return response.json();
                    })
                    .then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Desasignado',
                            text: 'El alumno fue desasignado correctamente.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Remover visualmente el alumno de la lista
                        const li = btn.closest('li');
                        li.remove();
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al desasignar el alumno.'
                        });
                    });
            }
        });
    }
    document.addEventListener('DOMContentLoaded', () => {
        const quitarSeleccionadosBtn = document.getElementById('quitar-seleccionados');
        const form = quitarSeleccionadosBtn.closest('form');

        quitarSeleccionadosBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const checkboxes = form.querySelectorAll('input[name="alumnos[]"]:checked');
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin selección',
                    text: 'Por favor selecciona al menos un alumno.',
                });
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Los alumnos seleccionados serán desasignados del grupo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'DELETE');

                    checkboxes.forEach(checkbox => {
                        formData.append('alumnos[]', checkbox.value);
                    });

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Error al desasignar');
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Desasignados',
                                text: 'Los alumnos fueron desasignados correctamente.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            checkboxes.forEach(checkbox => {
                                const li = checkbox.closest('li');
                                if (li) li.remove();
                            });
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al desasignar los alumnos.'
                            });
                        });
                }
            });
        });
    });
</script>
