@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Editar Paciente</h2>
        <form action="{{ route('pacientes.update', $paciente->ID_Paciente) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="Nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="Nombre" name="Nombre"
                        value="{{ old('Nombre', $paciente->Nombre) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="ApePaterno" class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" id="ApePaterno" name="ApePaterno"
                        value="{{ old('ApePaterno', $paciente->ApePaterno) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="ApeMaterno" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="ApeMaterno" name="ApeMaterno"
                        value="{{ old('ApeMaterno', $paciente->ApeMaterno) }}" required>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label for="FechaNac" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="FechaNac" name="FechaNac"
                        value="{{ old('FechaNac', $paciente->FechaNac) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="Sexo" class="form-label">Sexo</label>
                    <select class="form-select" id="Sexo" name="Sexo" required>
                        <option value="">Seleccione...</option>
                        <option value="Masculino" {{ old('Sexo', $paciente->Sexo) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('Sexo', $paciente->Sexo) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="TipoPaciente" class="form-label">Tipo de Paciente</label>
                    <select class="form-select" id="TipoPaciente" name="TipoPaciente" required>
                        <option value="">Seleccione</option>
                        <option value="Adulto" {{ old('TipoPaciente', $paciente->TipoPaciente) == 'Adulto' ? 'selected' : '' }}>Adulto</option>
                        <option value="Pediátrico" {{ old('TipoPaciente', $paciente->TipoPaciente) == 'Pediátrico' ? 'selected' : '' }}>Pediátrico</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-5">
                    <label for="Direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="Direccion" name="Direccion"
                        value="{{ old('Direccion', $paciente->Direccion) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroExterior" class="form-label">Número Exterior</label>
                    <input type="text" class="form-control" id="NumeroExterior" name="NumeroExterior"
                        value="{{ old('NumeroExterior', $paciente->NumeroExterior) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroInterior" class="form-label">Número Interior</label>
                    <input type="text" class="form-control" id="NumeroInterior" name="NumeroInterior"
                        value="{{ old('NumeroInterior', $paciente->NumeroInterior) }}">
                </div>
                <div class="col-md-3">
                    <label for="CodigoPostal" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="CodigoPostal" name="CodigoPostal"
                        value="{{ old('CodigoPostal', $paciente->CodigoPostal) }}" required>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label for="Pais" class="form-label">País</label>
                    <input type="text" class="form-control" id="Pais" name="Pais"
                        value="{{ old('Pais', $paciente->Pais) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="ID_Estado" class="form-label">Estado</label>
                    <select class="form-select" id="ID_Estado" name="ID_Estado" required>
                        <option value="">Seleccione un estado</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado->ID_Estado }}"
                                {{ old('ID_Estado', $paciente->ID_Estado) == $estado->ID_Estado ? 'selected' : '' }}>
                                {{ $estado->NombreEstado }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="ID_Municipio" class="form-label">Municipio</label>
                    <select class="form-select" id="ID_Municipio" name="ID_Municipio" required>
                        <option value="">Seleccione un municipio</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label for="Foto_Paciente" class="form-label">Foto del Paciente</label>
                    @if ($paciente->Foto_Paciente)
                        <div class="mb-2">
                            <img src="{{ asset($paciente->Foto_Paciente) }}" alt="Foto del paciente" width="100">
                        </div>
                    @endif
                    <input type="file" class="form-control mt-2" id="Foto_Paciente" name="Foto_Paciente" accept="image/*">
                    <small class="form-text text-muted">Puedes subir una nueva imagen y se eliminará la anterior automáticamente.</small>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center align-items-center">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <div class="mx-2"></div>
                <a href="{{ route('volver') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const estadoSelect = document.getElementById('ID_Estado');
            const municipioSelect = document.getElementById('ID_Municipio');
            const municipioActual = "{{ old('ID_Municipio', $paciente->ID_Municipio) }}";

            function cargarMunicipios(estadoId, municipioSeleccionado = null) {
                municipioSelect.innerHTML = '<option value="">Cargando municipios...</option>';
                municipioSelect.disabled = true;
                municipioSelect.required = false;

                if (estadoId) {
                    fetch(`/municipiosEstado/${estadoId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Error al cargar municipios');
                            return response.json();
                        })
                        .then(data => {
                            if (data.length > 0) {
                                municipioSelect.innerHTML =
                                    '<option value="">Seleccione un municipio...</option>';
                                data.forEach(municipio => {
                                    const option = new Option(municipio.NombreMunicipio, municipio.ID_Municipio);
                                    if (municipioSeleccionado && municipio.ID_Municipio == municipioSeleccionado) {
                                        option.selected = true;
                                    }
                                    municipioSelect.add(option);
                                });
                                municipioSelect.disabled = false;
                                municipioSelect.required = true;
                            } else {
                                municipioSelect.innerHTML =
                                    '<option value="">No hay municipios para este estado</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            municipioSelect.innerHTML = '<option value="">Error al cargar municipios</option>';
                        });
                } else {
                    municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
                    municipioSelect.disabled = true;
                    municipioSelect.required = false;
                }
            }

            estadoSelect.addEventListener('change', function() {
                cargarMunicipios(this.value);
            });
            const estadoActual = estadoSelect.value;
            if (estadoActual) {
                cargarMunicipios(estadoActual, municipioActual);
            }
        });
    </script>
@endsection