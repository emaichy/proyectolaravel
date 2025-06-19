@extends('layouts.app')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
    <div class="container py-4">
        <div class="mb-4">
            <div class="card-body d-flex justify-content-center gap-3 flex-wrap">
                <button type="button" class="btn btn-primary" id="abrir-modal-alumno">
                    <i class="bi bi-person-plus"></i> Agregar alumnos
                </button>
                <button type="button" class="btn btn-success" id="abrir-modal-maestro">
                    <i class="bi bi-person-badge"></i> Agregar maestros
                </button>
            </div>
        </div>
        <div id="grupo-detalle">
            @include('grupos.detalle', [
                'grupo' => $grupo,
                'alumnosDisponibles' => $alumnosDisponibles,
            ])
        </div>
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 mb-4">
            <button id="btn-anterior" class="btn btn-outline-secondary btn-sm">Anterior</button>
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center" id="grupo-nav"
                style="transition: transform 0.4s ease-in-out;"></div>
            <button id="btn-siguiente" class="btn btn-outline-secondary btn-sm">Siguiente</button>
        </div>
    </div>
    <div class="modal fade" id="modalAgregarAlumno" tabindex="-1" aria-labelledby="modalAgregarAlumnoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-agregar-alumnos" method="POST"
                    action="{{ route('grupos.asignar-alumnos', $grupo->ID_Grupo) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarAlumnoLabel">Agregar alumnos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <select class="form-select" id="alumno-select">
                                <option value="" disabled selected>-- Selecciona un alumno --</option>
                                @foreach ($alumnosDisponibles as $alumno)
                                    @if ($alumno->Matricula)
                                        <option value="{{ $alumno->Matricula }}">
                                            {{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="btn-agregar-alumno">Agregar</button>
                        </div>
                        <h6>Lista preliminar de alumnos:</h6>
                        <ul class="list-group mb-3" id="lista-alumnos"></ul>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btn-submit-alumnos" style="display:none;">Agregar
                            alumnos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAgregarMaestro" tabindex="-1" aria-labelledby="modalAgregarMaestroLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-agregar-maestros" method="POST"
                    action="{{ route('grupos.asignar-maestros', $grupo->ID_Grupo) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarMaestroLabel">Agregar maestros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <select class="form-select" id="maestro-select">
                                <option value="" disabled selected>-- Selecciona un maestro --</option>
                                @foreach ($maestrosDisponibles as $maestro)
                                    <option value="{{ $maestro->ID_Maestro }}">
                                        {{ $maestro->Nombre }} {{ $maestro->ApePaterno }} {{ $maestro->ApeMaestro }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="btn-agregar-maestro">Agregar</button>
                        </div>
                        <h6>Lista preliminar de maestros:</h6>
                        <ul class="list-group mb-3" id="lista-maestros"></ul>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btn-submit-maestros"
                            style="display:none;">Agregar maestros</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function initGrupoDetalleCheckboxes() {
            document.querySelectorAll('#grupo-detalle .list-group-item').forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (!checkbox) return;
                item.classList.toggle('active', checkbox.checked);
                const newCheckbox = checkbox.cloneNode(true);
                checkbox.parentNode.replaceChild(newCheckbox, checkbox);
                newCheckbox.addEventListener('change', () => {
                    item.classList.toggle('active', newCheckbox.checked);
                    updateMasivoButtons();
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
                    newCheckbox.checked = !newCheckbox.checked;
                    newCheckbox.dispatchEvent(new Event('change'));
                });
            });
            updateMasivoButtons();
        }

        function updateMasivoButtons() {
            const maestroChecks = document.querySelectorAll('#grupo-detalle .chk-maestro');
            const maestroBtn = document.getElementById('quitar-maestros-seleccionados');
            if (maestroBtn) {
                const anyChecked = Array.from(maestroChecks).some(cb => cb.checked);
                maestroBtn.style.display = anyChecked ? '' : 'none';
            }
            const alumnoChecks = document.querySelectorAll('#grupo-detalle .chk-alumno');
            const alumnoBtn = document.getElementById('quitar-seleccionados');
            if (alumnoBtn) {
                const anyChecked = Array.from(alumnoChecks).some(cb => cb.checked);
                alumnoBtn.style.display = anyChecked ? '' : 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('abrir-modal-alumno').addEventListener('click', function() {
                var modalAlumno = new bootstrap.Modal(document.getElementById('modalAgregarAlumno'));
                modalAlumno.show();
            });
            document.getElementById('abrir-modal-maestro').addEventListener('click', function() {
                var modalMaestro = new bootstrap.Modal(document.getElementById('modalAgregarMaestro'));
                modalMaestro.show();
            });
            const selectAlumno = document.getElementById('alumno-select');
            const btnAgregar = document.getElementById('btn-agregar-alumno');
            const lista = document.getElementById('lista-alumnos');
            const btnSubmitAlumnos = document.getElementById('btn-submit-alumnos');
            let alumnosSeleccionados = [];

            function renderLista() {
                lista.innerHTML = '';
                alumnosSeleccionados.forEach((alumno, index) => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';
                    const label = document.createElement('span');
                    label.textContent = alumno.nombre;
                    const btnQuitar = document.createElement('button');
                    btnQuitar.type = 'button';
                    btnQuitar.className = 'btn btn-sm btn-outline-danger';
                    btnQuitar.textContent = 'Quitar';
                    btnQuitar.onclick = () => {
                        alumnosSeleccionados.splice(index, 1);
                        renderLista();
                    };
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'alumnos[]';
                    hiddenInput.value = alumno.id;
                    item.appendChild(label);
                    item.appendChild(btnQuitar);
                    item.appendChild(hiddenInput);
                    lista.appendChild(item);
                });
                if (alumnosSeleccionados.length > 0) {
                    const btnLimpiar = document.createElement('button');
                    btnLimpiar.type = 'button';
                    btnLimpiar.className = 'btn btn-warning mt-3 w-100';
                    btnLimpiar.textContent = 'Limpiar lista';
                    btnLimpiar.onclick = () => {
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: 'Esto eliminará todos los alumnos seleccionados de la lista.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Sí, limpiar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                alumnosSeleccionados = [];
                                renderLista();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Lista limpia',
                                    text: 'Todos los alumnos fueron eliminados de la lista.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    };
                    lista.appendChild(btnLimpiar);
                    btnSubmitAlumnos.style.display = '';
                } else {
                    btnSubmitAlumnos.style.display = 'none';
                }
            }
            btnAgregar.addEventListener('click', () => {
                const selectedOption = selectAlumno.options[selectAlumno.selectedIndex];
                if (!selectedOption || selectedOption.value === "") {
                    Swal.fire('Atención', 'Debes seleccionar un alumno válido.', 'warning');
                    return;
                }
                const id = parseInt(selectedOption.value);
                const nombre = selectedOption.text;
                if (isNaN(id)) {
                    Swal.fire('Error', 'ID del alumno no válido.', 'error');
                    return;
                }
                if (alumnosSeleccionados.some(a => a.id === id)) {
                    Swal.fire('Atención', 'Este alumno ya fue agregado.', 'info');
                    return;
                }
                alumnosSeleccionados.push({
                    id,
                    nombre
                });
                renderLista();
            });
            const selectMaestro = document.getElementById('maestro-select');
            const btnAgregarMaestro = document.getElementById('btn-agregar-maestro');
            const listaMaestros = document.getElementById('lista-maestros');
            const btnSubmitMaestros = document.getElementById('btn-submit-maestros');
            let maestrosSeleccionados = [];

            function renderListaMaestros() {
                listaMaestros.innerHTML = '';
                maestrosSeleccionados.forEach((maestro, index) => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';
                    const label = document.createElement('span');
                    label.textContent = maestro.nombre;
                    const btnQuitar = document.createElement('button');
                    btnQuitar.type = 'button';
                    btnQuitar.className = 'btn btn-sm btn-outline-danger';
                    btnQuitar.textContent = 'Quitar';
                    btnQuitar.onclick = () => {
                        maestrosSeleccionados.splice(index, 1);
                        renderListaMaestros();
                    };
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'maestros[]';
                    hiddenInput.value = maestro.id;
                    item.appendChild(label);
                    item.appendChild(btnQuitar);
                    item.appendChild(hiddenInput);
                    listaMaestros.appendChild(item);
                });
                if (maestrosSeleccionados.length > 0) {
                    const btnLimpiar = document.createElement('button');
                    btnLimpiar.type = 'button';
                    btnLimpiar.className = 'btn btn-warning mt-3 w-100';
                    btnLimpiar.textContent = 'Limpiar lista';
                    btnLimpiar.onclick = () => {
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: 'Esto eliminará todos los maestros seleccionados de la lista.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Sí, limpiar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                maestrosSeleccionados = [];
                                renderListaMaestros();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Lista limpia',
                                    text: 'Todos los maestros fueron eliminados de la lista.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    };
                    listaMaestros.appendChild(btnLimpiar);
                    btnSubmitMaestros.style.display = '';
                } else {
                    btnSubmitMaestros.style.display = 'none';
                }
            }
            btnAgregarMaestro.addEventListener('click', () => {
                const selectedOption = selectMaestro.options[selectMaestro.selectedIndex];
                if (!selectedOption || selectedOption.value === "") {
                    Swal.fire('Atención', 'Debes seleccionar un maestro válido.', 'warning');
                    return;
                }
                const id = parseInt(selectedOption.value);
                const nombre = selectedOption.text;
                if (isNaN(id)) {
                    Swal.fire('Error', 'ID del maestro no válido.', 'error');
                    return;
                }
                if (maestrosSeleccionados.some(m => m.id === id)) {
                    Swal.fire('Atención', 'Este maestro ya fue agregado.', 'info');
                    return;
                }
                maestrosSeleccionados.push({
                    id,
                    nombre
                });
                renderListaMaestros();
            });
            renderLista();
            renderListaMaestros();
            const todosLosGrupos = @json($todosLosGrupos);
            let grupoActualId = {{ $grupo->ID_Grupo }};
            const navContainer = document.getElementById('grupo-nav');
            const btnAnterior = document.getElementById('btn-anterior');
            const btnSiguiente = document.getElementById('btn-siguiente');

            function renderGrupos() {
                navContainer.innerHTML = '';
                const index = todosLosGrupos.findIndex(g => g.ID_Grupo == grupoActualId);
                const total = todosLosGrupos.length;
                btnAnterior.disabled = index === 0;
                btnSiguiente.disabled = index === total - 1;

                function createBtn(grupo, active = false) {
                    const btn = document.createElement('button');
                    btn.className = 'btn btn-sm ' + (active ? 'btn-primary' : 'btn-outline-primary');
                    btn.textContent = grupo.NombreGrupo;
                    btn.onclick = () => {
                        grupoActualId = grupo.ID_Grupo;
                        cargarGrupo(grupoActualId);
                    };
                    navContainer.appendChild(btn);
                }

                function createDots() {
                    const btn = document.createElement('button');
                    btn.textContent = '[.....]';
                    btn.className = 'btn btn-outline-secondary btn-sm btn-dots';
                    navContainer.appendChild(btn);
                }
                if (index <= 2) {
                    for (let i = 0; i <= 2 && i < total; i++) createBtn(todosLosGrupos[i], i === index);
                    if (total > 6) {
                        createDots();
                        for (let i = total - 3; i < total; i++) createBtn(todosLosGrupos[i], i === index);
                    }
                } else if (index >= total - 3) {
                    for (let i = 0; i < 3; i++) createBtn(todosLosGrupos[i], i === index);
                    createDots();
                    for (let i = total - 3; i < total; i++) createBtn(todosLosGrupos[i], i === index);
                } else {
                    createBtn(todosLosGrupos[0]);
                    createDots();
                    createBtn(todosLosGrupos[index - 1]);
                    createBtn(todosLosGrupos[index], true);
                    createBtn(todosLosGrupos[index + 1]);
                    createDots();
                    createBtn(todosLosGrupos[total - 1]);
                }
            }

            function cargarGrupo(id) {
                fetch(`/grupos/ajax/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('grupo-detalle').innerHTML = data.html;
                        const form = document.getElementById('form-agregar-alumnos');
                        if (form) {
                            form.action = `/grupos/${id}/asignar-alumnos`;
                        }
                        const formMaestros = document.getElementById('form-agregar-maestros');
                        if (formMaestros) {
                            formMaestros.action = `/grupos/${id}/asignar-maestros`;
                        }
                        history.pushState(null, '', `/grupos/${id}`);
                        initGrupoDetalleCheckboxes();
                        renderGrupos();
                    });
            }
            btnSiguiente.addEventListener('click', () => {
                const idx = todosLosGrupos.findIndex(g => g.ID_Grupo == grupoActualId);
                if (idx < todosLosGrupos.length - 1) {
                    grupoActualId = todosLosGrupos[idx + 1].ID_Grupo;
                    cargarGrupo(grupoActualId);
                }
            });
            renderGrupos();
        });
    </script>
@endsection
