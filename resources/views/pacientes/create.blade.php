@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Crear Paciente</h2>
        <form action="{{ route('pacientes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="Nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="Nombre" name="Nombre" required>
                </div>
                <div class="col-md-4">
                    <label for="ApePaterno" class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" id="ApePaterno" name="ApePaterno" required>
                </div>
                <div class="col-md-4">
                    <label for="ApeMaterno" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="ApeMaterno" name="ApeMaterno" required>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label for="FechaNac" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="FechaNac" name="FechaNac" required>
                </div>
                <div class="col-md-4">
                    <label for="Sexo" class="form-label">Sexo</label>
                    <select class="form-select" id="Sexo" name="Sexo" required>
                        <option value="">Seleccione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="TipoPaciente" class="form-label">Tipo de Paciente</label>
                    <select class="form-select" id="TipoPaciente" name="TipoPaciente" required>
                        <option value="">Seleccione</option>
                        <option value="Adulto">Adulto</option>
                        <option value="Pediátrico">Pediátrico</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-5">
                    <label for="Direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="Direccion" name="Direccion" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroExterior" class="form-label">Número Exterior</label>
                    <input type="text" class="form-control" id="NumeroExterior" name="NumeroExterior" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroInterior" class="form-label">Número Interior</label>
                    <input type="text" class="form-control" id="NumeroInterior" name="NumeroInterior">
                </div>
                <div class="col-md-3">
                    <label for="CodigoPostal" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="CodigoPostal" name="CodigoPostal" required>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label for="Pais" class="form-label">País</label>
                    <input type="text" class="form-control" id="Pais" name="Pais" required>
                </div>
                <div class="col-md-4">
                    <label for="ID_Estado" class="form-label">Estado</label>
                    <select class="form-select" id="ID_Estado" name="ID_Estado" required>
                        <option value="">Seleccione un estado...</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado->ID_Estado }}">{{ $estado->NombreEstado }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="ID_Municipio" class="form-label">Municipio</label>
                    <select class="form-select" id="ID_Municipio" name="ID_Municipio" required disabled>
                        <option value="">Primero seleccione un estado</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label for="Foto_Paciente" class="form-label">Foto del Paciente</label>
                    <input type="file" class="form-control" id="Foto_Paciente" name="Foto_Paciente"
                        accept="image/*">
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
        });
    </script>
@endsection
