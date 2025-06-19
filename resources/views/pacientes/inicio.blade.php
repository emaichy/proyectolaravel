@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Listado de Pacientes</h1>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary mb-3">Agregar Paciente</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha Nac.</th>
                    <th>Sexo</th>
                    <th>Dirección</th>
                    <th>Tipo Paciente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pacientes as $paciente)
                    <tr>
                        <td>{{ $paciente->Nombre . ' ' . $paciente->ApePaterno . ' ' . $paciente->ApeMaterno }}</td>
                        <td>{{ $paciente->FechaNac }}</td>
                        <td>{{ $paciente->Sexo }}</td>
                        <td>{{ $paciente->Direccion . ' ' . $paciente->NumeroExterior . ', #' . $paciente->NumeroInterior . ' CP.' . $paciente->CodigoPostal . ', ' . $paciente->municipio->NombreMunicipio . ', ' . $paciente->estado->NombreEstado }}</td>
                        <td>{{ $paciente->TipoPaciente }}</td>
                        <td>
                            <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST"
                                style="display:inline;" class="form-eliminar">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17" class="text-center">No hay pacientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $pacientes->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formsEliminar = document.querySelectorAll('.form-eliminar');

            formsEliminar.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminarlo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
