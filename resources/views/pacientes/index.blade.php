@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="mb-4">Listado de Pacientes</h1>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary mb-3">Agregar Paciente</a>
        <table class="table table-bordered" id="tabla-pacientes">
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
                    <tr id="paciente-row-{{ $paciente->ID_Paciente }}">
                        <td>{{ $paciente->Nombre . ' ' . $paciente->ApePaterno . ' ' . $paciente->ApeMaterno }}</td>
                        <td>{{ $paciente->FechaNac }}</td>
                        <td>{{ $paciente->Sexo }}</td>
                        <td>{{ $paciente->Direccion . ' ' . $paciente->NumeroExterior . ', #' . $paciente->NumeroInterior . ' CP.' . $paciente->CodigoPostal . ', ' . $paciente->municipio->NombreMunicipio . ', ' . $paciente->estado->NombreEstado }}
                        </td>
                        <td>{{ $paciente->TipoPaciente }}</td>
                        <td>
                            <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning btn-sm">Editar</a>
                            <button type="button"
                                class="btn btn-danger btn-sm btn-eliminar-paciente"
                                data-id="{{ $paciente->ID_Paciente }}"
                                data-url="{{ route('pacientes.destroy', $paciente->ID_Paciente) }}">
                                Eliminar
                            </button>
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
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
            const tabla = document.getElementById('tabla-pacientes');
            tabla.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('btn-eliminar-paciente')) {
                    e.preventDefault();
                    const button = e.target;
                    const pacienteId = button.getAttribute('data-id');
                    const url = button.getAttribute('data-url');
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
                            fetch(url, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        const row = document.getElementById('paciente-row-' + pacienteId);
                                        if (row) row.remove();
                                        Swal.fire(
                                            '¡Eliminado!',
                                            data.message ?? 'El paciente ha sido eliminado.',
                                            'success'
                                        );
                                    } else {
                                        Swal.fire(
                                            'Error',
                                            data.message ?? 'No se pudo eliminar el paciente.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    Swal.fire(
                                        'Error',
                                        'Ocurrió un error en el servidor.',
                                        'error'
                                    );
                                });
                        }
                    });
                }
            });
        });
    </script>
@endsection