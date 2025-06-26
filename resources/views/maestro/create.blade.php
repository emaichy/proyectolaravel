@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Crear Maestro</h2>
        <form action="{{ route('maestros.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="Correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="Correo" required>
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label for="Nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="Nombre" required>
                </div>
                <div class="col-md-4">
                    <label for="ApePaterno" class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" name="ApePaterno" required>
                </div>
                <div class="col-md-4">
                    <label for="ApeMaestro" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" name="ApeMaestro" required>
                </div>
                <div class="col-md-4">
                    <label for="Especialidad" class="form-label">Especialidad</label>
                    <input type="text" class="form-control" name="Especialidad" required>
                </div>
                <div class="col-md-4">
                    <label for="CedulaProfesional" class="form-label">Cédula Profesional</label>
                    <input type="text" class="form-control" name="CedulaProfesional" required>
                </div>
                <div class="col-md-4">
                    <label for="FechaNac" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="FechaNac" required>
                </div>
                <div class="col-md-4">
                    <label for="Sexo" class="form-label">Sexo</label>
                    <select class="form-select" name="Sexo" required>
                        <option value="">Seleccione</option>
                        <option>Masculino</option>
                        <option>Femenino</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="Telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="Telefono" required>
                </div>
                <div class="col-md-6">
                    <label for="Direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" name="Direccion" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroExterior" class="form-label">Número Exterior</label>
                    <input type="text" class="form-control" name="NumeroExterior" required>
                </div>
                <div class="col-md-2">
                    <label for="NumeroInterior" class="form-label">Número Interior</label>
                    <input type="text" class="form-control" name="NumeroInterior">
                </div>
                <div class="col-md-2">
                    <label for="CodigoPostal" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" name="CodigoPostal" required>
                </div>
                <div class="col-md-4">
                    <label for="Pais" class="form-label">País</label>
                    <input type="text" class="form-control" name="Pais" required>
                </div>

                <div class="col-md-4">
                    <label for="ID_Estado" class="form-label">Estado</label>
                    <select class="form-select" name="ID_Estado" id="ID_Estado" required>
                        <option value="">Seleccione un estado...</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado->ID_Estado }}">{{ $estado->NombreEstado }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="ID_Municipio" class="form-label">Municipio</label>
                    <select class="form-select" name="ID_Municipio" id="ID_Municipio" required disabled>
                        <option value="">Primero seleccione un estado</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Crear Maestro</button>
                <a href="{{ route('volver') }}" class="btn btn-secondary">Regresar</a>
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
        });
    </script>
@endsection
