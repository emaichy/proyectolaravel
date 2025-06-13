@extends('layouts.app')

@section('title', 'Editar Maestro')

@section('content')
    <div class="container">
        <h2>Editar Maestro</h2>
        <form action="{{ route('maestros.update', $maestro->ID_Maestro) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="Correo" name="Correo"
                            value="{{ old('Correo', $maestro->usuario->Correo) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Dejar en blanco para no cambiar">
                    </div>

                    <div class="mb-3">
                        <label for="Nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre"
                            value="{{ old('Nombre', $maestro->Nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="ApePaterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="ApePaterno" name="ApePaterno"
                            value="{{ old('ApePaterno', $maestro->ApePaterno) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="ApeMaestro" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="ApeMaestro" name="ApeMaestro"
                            value="{{ old('ApeMaestro', $maestro->ApeMaestro) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="Especialidad" class="form-label">Especialidad</label>
                        <input type="text" class="form-control" id="Especialidad" name="Especialidad"
                            value="{{ old('Especialidad', $maestro->Especialidad) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="FechaNac" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="FechaNac" name="FechaNac"
                            value="{{ old('FechaNac', $maestro->FechaNac) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="Sexo" class="form-label">Sexo</label>
                        <select class="form-control" id="Sexo" name="Sexo" required>
                            <option value="">Seleccione</option>
                            <option value="Masculino" {{ old('Sexo', $maestro->Sexo) == 'Masculino' ? 'selected' : '' }}>
                                Masculino</option>
                            <option value="Femenino" {{ old('Sexo', $maestro->Sexo) == 'Femenino' ? 'selected' : '' }}>
                                Femenino</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="Direccion" name="Direccion"
                            value="{{ old('Direccion', $maestro->Direccion) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="NumeroExterior" class="form-label">Número Exterior</label>
                        <input type="text" class="form-control" id="NumeroExterior" name="NumeroExterior"
                            value="{{ old('NumeroExterior', $maestro->NumeroExterior) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="NumeroInterior" class="form-label">Número Interior</label>
                        <input type="text" class="form-control" id="NumeroInterior" name="NumeroInterior"
                            value="{{ old('NumeroInterior', $maestro->NumeroInterior) }}">
                    </div>
                    <div class="mb-3">
                        <label for="CodigoPostal" class="form-label">Código Postal</label>
                        <input type="text" class="form-control" id="CodigoPostal" name="CodigoPostal"
                            value="{{ old('CodigoPostal', $maestro->CodigoPostal) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="Pais" class="form-label">País</label>
                        <input type="text" class="form-control" id="Pais" name="Pais"
                            value="{{ old('Pais', $maestro->Pais) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="Telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="Telefono" name="Telefono"
                            value="{{ old('Telefono', $maestro->Telefono) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="CedulaProfesional" class="form-label">Cédula Profesional</label>
                        <input type="text" class="form-control" id="CedulaProfesional" name="CedulaProfesional"
                            value="{{ old('CedulaProfesional', $maestro->CedulaProfesional) }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ID_Estado" class="form-label">Estado</label>
                        <select class="form-select" id="ID_Estado" name="ID_Estado" required>
                            <option value="">Seleccione un estado...</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado->ID_Estado }}"
                                    {{ old('ID_Estado', $maestro->ID_Estado) == $estado->ID_Estado ? 'selected' : '' }}>
                                    {{ $estado->NombreEstado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ID_Municipio" class="form-label">Municipio</label>
                        <select class="form-select" id="ID_Municipio" name="ID_Municipio" required
                            {{ !$maestro->ID_Estado ? 'disabled' : '' }}>
                            <option value="">Cargando municipios...</option>
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
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">Actualizar Maestro</button>
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
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al cargar municipios');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.length > 0) {
                                municipioSelect.innerHTML =
                                    '<option value="">Seleccione un municipio...</option>';
                                data.forEach(municipio => {
                                    const option = new Option(municipio.NombreMunicipio,
                                        municipio.ID_Municipio);
                                    municipioSelect.add(option);
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
                        .catch(error => {
                            console.error('Error:', error);
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
