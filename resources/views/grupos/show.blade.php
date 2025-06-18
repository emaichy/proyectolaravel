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
        <div class="card shadow-sm mb-5 border-0">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Agregar alumnos</h5>
            </div>
            <div class="card-body">
                <label for="alumno-select" class="form-label">Selecciona alumno:</label>
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
                    <button type="button" class="btn btn-primary" id="btn-agregar-alumno">Seleccionar</button>
                </div>
                <div id="lista-contenedor" style="display: none;">
                    <h6>Lista preliminar de alumnos:</h6>
                    <form id="form-agregar-alumnos" method="POST"
                        action="{{ route('grupos.asignar-alumnos', $grupo->ID_Grupo) }}">
                        @csrf
                        <ul class="list-group mb-3" id="lista-alumnos">
                        </ul>
                        <button type="submit" class="btn btn-success">Agregar alumnos seleccionados</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-5 border-0">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Agregar maestros</h5>
            </div>
            <div class="card-body">
                <label for="maestro-select" class="form-label">Selecciona maestro:</label>
                <div class="input-group mb-3">
                    <select class="form-select" id="maestro-select">
                        <option value="" disabled selected>-- Selecciona un maestro --</option>
                        @foreach ($maestrosDisponibles as $maestro)
                            <option value="{{ $maestro->ID_Maestro }}">
                                {{ $maestro->Nombre }} {{ $maestro->ApePaterno }} {{ $maestro->ApeMaestro }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-primary" id="btn-agregar-maestro">Seleccionar</button>
                </div>
                <div id="lista-contenedor-maestros" style="display: none;">
                    <h6>Lista preliminar de maestros:</h6>
                    <form id="form-agregar-maestros" method="POST"
                        action="{{ route('grupos.asignar-maestros', $grupo->ID_Grupo) }}">
                        @csrf
                        <ul class="list-group mb-3" id="lista-maestros"></ul>
                        <button type="submit" class="btn btn-success">Agregar maestros</button>
                    </form>
                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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

        const selectAlumno = document.getElementById('alumno-select');
        const btnAgregar = document.getElementById('btn-agregar-alumno');
        const lista = document.getElementById('lista-alumnos');
        const form = document.getElementById('form-agregar-alumnos');
        let alumnosSeleccionados = [];

        function renderLista() {
            const contenedorLista = document.getElementById('lista-contenedor');
            lista.innerHTML = '';

            if (alumnosSeleccionados.length > 0) {
                contenedorLista.style.display = 'block';
            } else {
                contenedorLista.style.display = 'none';
            }

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

                const leftDiv = document.createElement('div');
                leftDiv.className = 'd-flex align-items-center gap-2';
                leftDiv.appendChild(label);

                item.appendChild(leftDiv);
                item.appendChild(btnQuitar);
                item.appendChild(hiddenInput);
                lista.appendChild(item);
            });

            if (alumnosSeleccionados.length > 0) {
                const btnLimpiar = document.createElement('button');
                btnLimpiar.type = 'button';
                btnLimpiar.className = 'btn btn-warning mt-3';
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
            }
        }
        btnAgregar.addEventListener('click', () => {
            const selectedOption = selectAlumno.options[selectAlumno.selectedIndex];
            console.log("Selected option:", selectedOption);
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
        let maestrosSeleccionados = [];

        function renderListaMaestros() {
            const contenedorListaMaestros = document.getElementById('lista-contenedor-maestros');
            listaMaestros.innerHTML = '';

            if (maestrosSeleccionados.length > 0) {
                contenedorListaMaestros.style.display = 'block';
            } else {
                contenedorListaMaestros.style.display = 'none';
            }

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

                const leftDiv = document.createElement('div');
                leftDiv.className = 'd-flex align-items-center gap-2';
                leftDiv.appendChild(label);

                item.appendChild(leftDiv);
                item.appendChild(btnQuitar);
                item.appendChild(hiddenInput);
                listaMaestros.appendChild(item);
            });

            if (maestrosSeleccionados.length > 0) {
                const btnLimpiar = document.createElement('button');
                btnLimpiar.type = 'button';
                btnLimpiar.className = 'btn btn-warning mt-3';
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
    </script>

    <style>
        .btn-dots {
            pointer-events: none;
            cursor: default;
            opacity: 0.6;
        }

        #grupo-nav {
            transition: transform 0.4s ease-in-out;
        }

        .btn-dots {
            pointer-events: none;
            cursor: default;
            opacity: 0.6;
        }

        #grupo-nav::-webkit-scrollbar {
            display: none;
        }
    </style>
@endsection
