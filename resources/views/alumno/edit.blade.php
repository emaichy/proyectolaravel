@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Alumno</h2>
        <form action="{{ route('alumnos.update', $alumno->Matricula) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="Correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="Correo" name="Correo" value="{{ old('Correo', $alumno->usuario->Correo) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="col-md-4">
                    <label for="Matricula" class="form-label">Matrícula</label>
                    <input type="text" class="form-control" id="Matricula" name="Matricula" value="{{ old('Matricula', $alumno->Matricula) }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="Nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="Nombre" name="Nombre" value="{{ old('Nombre', $alumno->Nombre) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="ApePaterno" class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" id="ApePaterno" name="ApePaterno" value="{{ old('ApePaterno', $alumno->ApePaterno) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="ApeMaterno" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="ApeMaterno" name="ApeMaterno" value="{{ old('ApeMaterno', $alumno->ApeMaterno) }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-5">
                    <label for="Direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="Direccion" name="Direccion" value="{{ old('Direccion', $alumno->Direccion) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroExterior" class="form-label">Núm. Exterior</label>
                    <input type="text" class="form-control" id="NumeroExterior" name="NumeroExterior" value="{{ old('NumeroExterior', $alumno->NumeroExterior) }}" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroInterior" class="form-label">Núm. Interior</label>
                    <input type="text" class="form-control" id="NumeroInterior" name="NumeroInterior" value="{{ old('NumeroInterior', $alumno->NumeroInterior) }}">
                </div>
                <div class="col-md-3">
                    <label for="CodigoPostal" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="CodigoPostal" name="CodigoPostal" value="{{ old('CodigoPostal', $alumno->CodigoPostal) }}" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="FechaNac" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="FechaNac" name="FechaNac" value="{{ old('FechaNac', $alumno->FechaNac) }}" required>
            </div>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="Curp" class="form-label">CURP</label>
                    <input type="text" class="form-control" id="Curp" name="Curp" value="{{ old('Curp', $alumno->Curp) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="Sexo" class="form-label">Sexo</label>
                    <select class="form-select" id="Sexo" name="Sexo" required>
                        <option value="">Seleccione</option>
                        <option value="Masculino" {{ old('Sexo', $alumno->Sexo) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('Sexo', $alumno->Sexo) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="Telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="Telefono" name="Telefono" value="{{ old('Telefono', $alumno->Telefono) }}" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="Pais" class="form-label">País</label>
                    <input type="text" class="form-control" id="Pais" name="Pais" value="{{ old('Pais', $alumno->Pais) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="ID_Estado" class="form-label">Estado</label>
                    <select class="form-select" id="ID_Estado" name="ID_Estado" required>
                        <option value="">Seleccione un estado...</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado->ID_Estado }}" {{ old('ID_Estado', $alumno->ID_Estado) == $estado->ID_Estado ? 'selected' : '' }}>
                                {{ $estado->NombreEstado }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="ID_Municipio" class="form-label">Municipio</label>
                    <select class="form-select" id="ID_Municipio" name="ID_Municipio" required {{ old('ID_Estado', $alumno->ID_Estado) ? '' : 'disabled' }}>
                        @if(old('ID_Estado', $alumno->ID_Estado) && isset($municipios))
                            <option value="">Seleccione un municipio...</option>
                            @foreach($municipios as $municipio)
                                <option value="{{ $municipio->ID_Municipio }}" {{ old('ID_Municipio', $alumno->ID_Municipio) == $municipio->ID_Municipio ? 'selected' : '' }}>
                                    {{ $municipio->NombreMunicipio }}
                                </option>
                            @endforeach
                        @else
                            <option value="">Primero seleccione un estado</option>
                        @endif
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Alumno</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Regresar</a>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const estadoSelect = document.getElementById('ID_Estado');
            const municipioSelect = document.getElementById('ID_Municipio');
            const selectedMunicipio = "{{ old('ID_Municipio', $alumno->ID_Municipio) }}";

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
                                    const option = new Option(municipio.NombreMunicipio, municipio.ID_Municipio);
                                    if (municipio.ID_Municipio == selectedMunicipio) {
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
                            municipioSelect.innerHTML =
                                '<option value="">Error al cargar municipios</option>';
                        });
                } else {
                    municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
                    municipioSelect.disabled = true;
                    municipioSelect.required = false;
                }
            });

            // Si ya hay un estado seleccionado al cargar la página, cargar municipios
            @if(old('ID_Estado', $alumno->ID_Estado))
                if (estadoSelect.value) {
                    fetch(`/municipiosEstado/${estadoSelect.value}`)
                        .then(response => response.json())
                        .then(data => {
                            municipioSelect.innerHTML =
                                '<option value="">Seleccione un municipio...</option>';
                            data.forEach(municipio => {
                                const option = new Option(municipio.NombreMunicipio, municipio.ID_Municipio);
                                if (municipio.ID_Municipio == selectedMunicipio) {
                                    option.selected = true;
                                }
                                municipioSelect.add(option);
                            });
                            municipioSelect.disabled = false;
                            municipioSelect.required = true;
                        });
                }
            @endif
        });
    </script>
@endsection