<div class="card shadow-lg mb-4 border-0">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Grupo: {{ $grupo->NombreGrupo }}</h2>
    </div>
    <div class="card-body">
        <h5 class="fw-bold">Maestros a cargo:</h5>
        @if ($grupo->maestros->count())
            <form method="POST" action="{{ route('grupos.desasignar-maestros', $grupo->ID_Grupo) }}">
                @csrf
                @method('DELETE')
                <ul class="list-group">
                    @foreach ($grupo->maestros as $maestro)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" name="maestros[]" value="{{ $maestro->ID_Maestro }}"
                                    id="maestro_{{ $maestro->ID_Maestro }}">
                                <label for="maestro_{{ $maestro->ID_Maestro }}">
                                    {{ $maestro->Nombre }} {{ $maestro->ApePaterno }} {{ $maestro->ApeMaestro }}
                                </label>
                            </div>
                            <div>
                                <a href="{{ route('maestros.show', $maestro->ID_Maestro) }}"
                                    class="btn btn-sm btn-outline-primary me-2">Ver</a>

                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="quitarMaestro('{{ $grupo->ID_Grupo }}', '{{ $maestro->ID_Maestro }}', this)">
                                    Quitar
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <button type="submit" class="btn btn-danger mt-3" id="quitar-maestros-seleccionados">
                    Quitar maestros
                </button>
            </form>
        @else
            <p class="text-muted">Este grupo no tiene maestros asignados.</p>
        @endif
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
    document.addEventListener('DOMContentLoaded', () => {
        // Activar selección al hacer clic en <li> excepto si es botón, link, input o label
        document.querySelectorAll('.list-group-item').forEach(item => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (checkbox.checked) item.classList.add('active');

            checkbox.addEventListener('change', () => {
                item.classList.toggle('active', checkbox.checked);
            });

            item.addEventListener('click', function(e) {
                if (
                    e.target.tagName === 'BUTTON' ||
                    e.target.tagName === 'A' ||
                    e.target.closest('button') ||
                    e.target.closest('a') ||
                    e.target.tagName === 'INPUT' ||
                    e.target.tagName === 'LABEL'
                ) {
                    return;
                }
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            });
        });

        // Quitar Maestros Seleccionados
        const quitarMaestrosBtn = document.getElementById('quitar-maestros-seleccionados');
        if (quitarMaestrosBtn) {
            const maestroForm = quitarMaestrosBtn.closest('form');
            quitarMaestrosBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const checkboxes = maestroForm.querySelectorAll('input[name="maestros[]"]:checked');
                if (checkboxes.length === 0) {
                    return Swal.fire('Sin selección', 'Selecciona al menos un maestro.', 'warning');
                }

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Los maestros seleccionados serán desasignados del grupo.',
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
                        checkboxes.forEach(cb => formData.append('maestros[]', cb.value));

                        fetch(maestroForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(r => {
                                if (!r.ok) throw new Error();
                                return r.json();
                            })
                            .then(() => {
                                Swal.fire('Desasignados',
                                    'Los maestros fueron desasignados.', 'success');
                                checkboxes.forEach(cb => cb.closest('li').remove());
                            })
                            .catch(() => {
                                Swal.fire('Error', 'Ocurrió un error al desasignar.',
                                    'error');
                            });
                    }
                });
            });
        }

        // Quitar Alumnos Seleccionados
        const quitarAlumnosBtn = document.getElementById('quitar-seleccionados');
        if (quitarAlumnosBtn) {
            const alumnoForm = quitarAlumnosBtn.closest('form');
            quitarAlumnosBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const checkboxes = alumnoForm.querySelectorAll('input[name="alumnos[]"]:checked');
                if (checkboxes.length === 0) {
                    return Swal.fire('Sin selección', 'Selecciona al menos un alumno.', 'warning');
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
                        checkboxes.forEach(cb => formData.append('alumnos[]', cb.value));

                        fetch(alumnoForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(r => {
                                if (!r.ok) throw new Error();
                                return r.json();
                            })
                            .then(() => {
                                Swal.fire('Desasignados',
                                    'Los alumnos fueron desasignados.', 'success');
                                checkboxes.forEach(cb => cb.closest('li').remove());
                            })
                            .catch(() => {
                                Swal.fire('Error', 'Ocurrió un error al desasignar.',
                                    'error');
                            });
                    }
                });
            });
        }
    });

    function quitarMaestro(grupoId, maestroId, btn) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Este maestro será desasignado del grupo.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/maestros/${maestroId}/desasignar-grupo/${grupoId}`, {
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
                        Swal.fire('Desasignado', 'El maestro fue desasignado correctamente.', 'success');
                        btn.closest('li').remove();
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Ocurrió un error al desasignar el maestro.', 'error');
                    });
            }
        });
    }

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
                        Swal.fire('Desasignado', 'El alumno fue desasignado correctamente.', 'success');
                        btn.closest('li').remove();
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Ocurrió un error al desasignar el alumno.', 'error');
                    });
            }
        });
    }
</script>

<style>
    .list-group-item {
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f0f8ff;
    }

    .list-group-item.active {
        background-color: #cce5ff;
        color: #004085;
        font-weight: 600;
    }
</style>
