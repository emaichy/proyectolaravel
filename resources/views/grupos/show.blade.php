@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-5 border-0">
            <div class="d-none">
                <form id="form-agregar-alumnos" method="POST"
                    action="{{ route('grupos.asignar-alumnos', $grupo->ID_Grupo) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="alumnos" class="form-label">Selecciona alumnos para agregar:</label>
                        <select multiple class="form-control" id="alumnos" name="alumnos[]">
                            @foreach ($alumnosDisponibles as $alumno)
                                <option value="{{ $alumno->ID_Alumno }}">
                                    {{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="alumnos-seleccionados" class="mb-3">
                        <!-- Alumnos seleccionados aparecen aquí -->
                    </div>
                    <button type="submit" class="btn btn-success">Agregar alumnos seleccionados</button>
                </form>
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
        const selectAlumnos = document.getElementById('alumnos');
        const alumnosSeleccionadosDiv = document.getElementById('alumnos-seleccionados');

        function actualizarSeleccionados() {
            alumnosSeleccionadosDiv.innerHTML = '';
            Array.from(selectAlumnos.selectedOptions).forEach(option => {
                const span = document.createElement('span');
                span.className = 'badge bg-info text-dark me-2 p-2';
                span.textContent = option.text + ' ';
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = 'Quitar';
                btn.className = 'btn btn-sm btn-outline-danger ms-2';
                btn.onclick = () => {
                    option.selected = false;
                    actualizarSeleccionados();
                };
                span.appendChild(btn);
                alumnosSeleccionadosDiv.appendChild(span);
                alumnosSeleccionadosDiv.appendChild(document.createElement('br'));
            });
        }
        selectAlumnos.addEventListener('change', actualizarSeleccionados);
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
                        renderGrupos();
                    });
            }
            btnAnterior.addEventListener('click', () => {
                const idx = todosLosGrupos.findIndex(g => g.ID_Grupo == grupoActualId);
                if (idx > 0) {
                    grupoActualId = todosLosGrupos[idx - 1].ID_Grupo;
                    cargarGrupo(grupoActualId);
                }
            });
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
