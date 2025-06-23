@extends('layouts.admin')

@section('title', 'Editar Maestro')

@section('content')
    <div class="container">
        <h2>Editar Maestro</h2>
        <form action="{{ route('maestros.update', $maestro->ID_Maestro) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="Correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="Correo"
                        value="{{ old('Correo', $maestro->usuario->Correo) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password"
                        placeholder="Dejar en blanco para no cambiar">
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label for="Nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="Nombre" value="{{ old('Nombre', $maestro->Nombre) }}"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="ApePaterno" class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" name="ApePaterno"
                        value="{{ old('ApePaterno', $maestro->ApePaterno) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="ApeMaestro" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" name="ApeMaestro"
                        value="{{ old('ApeMaestro', $maestro->ApeMaestro) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="Especialidad" class="form-label">Especialidad</label>
                    <input type="text" class="form-control" name="Especialidad"
                        value="{{ old('Especialidad', $maestro->Especialidad) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="CedulaProfesional" class="form-label">Cédula Profesional</label>
                    <input type="text" class="form-control" name="CedulaProfesional"
                        value="{{ old('CedulaProfesional', $maestro->CedulaProfesional) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="FechaNac" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="FechaNac"
                        value="{{ old('FechaNac', $maestro->FechaNac) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="Sexo" class="form-label">Sexo</label>
                    <select class="form-select" name="Sexo" required>
                        <option value="">Seleccione</option>
                        <option value="Masculino" {{ old('Sexo', $maestro->Sexo) == 'Masculino' ? 'selected' : '' }}>
                            Masculino</option>
                        <option value="Femenino" {{ old('Sexo', $maestro->Sexo) == 'Femenino' ? 'selected' : '' }}>Femenino
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="Telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="Telefono"
                        value="{{ old('Telefono', $maestro->Telefono) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="Direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" name="Direccion"
                        value="{{ old('Direccion', $maestro->Direccion) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroExterior" class="form-label">Número Exterior</label>
                    <input type="text" class="form-control" name="NumeroExterior"
                        value="{{ old('NumeroExterior', $maestro->NumeroExterior) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroInterior" class="form-label">Número Interior</label>
                    <input type="text" class="form-control" name="NumeroInterior"
                        value="{{ old('NumeroInterior', $maestro->NumeroInterior) }}">
                </div>
                <div class="col-md-2">
                    <label for="CodigoPostal" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" name="CodigoPostal"
                        value="{{ old('CodigoPostal', $maestro->CodigoPostal) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="Pais" class="form-label">País</label>
                    <input type="text" class="form-control" name="Pais" value="{{ old('Pais', $maestro->Pais) }}"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="ID_Estado" class="form-label">Estado</label>
                    <select class="form-select" name="ID_Estado" id="ID_Estado" required>
                        <option value="">Seleccione un estado...</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado->ID_Estado }}"
                                {{ old('ID_Estado', $maestro->ID_Estado) == $estado->ID_Estado ? 'selected' : '' }}>
                                {{ $estado->NombreEstado }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="ID_Municipio" class="form-label">Municipio</label>
                    <select class="form-select" name="ID_Municipio" id="ID_Municipio" required
                        {{ !$maestro->ID_Estado ? 'disabled' : '' }}>
                        <option value="">
                            {{ $maestro->ID_Estado ? 'Cargando municipios...' : 'Primero seleccione un estado' }}</option>
                        @if ($maestro->ID_Estado)
                            @foreach ($municipios as $municipio)
                                <option value="{{ $municipio->ID_Municipio }}"
                                    {{ old('ID_Municipio', $maestro->ID_Municipio) == $municipio->ID_Municipio ? 'selected' : '' }}>
                                    {{ $municipio->NombreMunicipio }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-between">
                <button class="btn btn-primary">Actualizar Maestro</button>
                <a href="{{ route('maestros.index') }}" class="btn btn-secondary">Regresar</a>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const estadoSelect = document.getElementById('ID_Estado');
            const municipioSelect = document.getElementById('ID_Municipio');
            estadoSelect.addEventListener('change', function() {
                const estadoId = this.value;
                municipioSelect.innerHTML = '<option value="">Cargando municipios...</option>';
                municipioSelect.disabled = true;
                municipioSelect.required = false;

                if (estadoId) {
                    fetch(`/municipiosEstado/${estadoId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                municipioSelect.innerHTML =
                                    '<option value="">Seleccione un municipio...</option>';
                                data.forEach(municipio => {
                                    municipioSelect.add(new Option(municipio.NombreMunicipio,
                                        municipio.ID_Municipio));
                                });
                                municipioSelect.disabled = false;
                                municipioSelect.required = true;
                                const oldMunicipio =
                                    "{{ old('ID_Municipio', $maestro->ID_Municipio) }}";
                                if (oldMunicipio) {
                                    municipioSelect.value = oldMunicipio;
                                }
                            } else {
                                municipioSelect.innerHTML =
                                    '<option value="">No hay municipios para este estado</option>';
                            }
                        })
                        .catch(() => {
                            municipioSelect.innerHTML =
                                '<option value="">Error al cargar municipios</option>';
                        });
                } else {
                    municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
                    municipioSelect.disabled = true;
                    municipioSelect.required = false;
                }
            });
            if (estadoSelect.value) {
                estadoSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
