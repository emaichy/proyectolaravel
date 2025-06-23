@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="mb-4">Usuarios Registrados</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-4">Agregar Usuario</a>
        <table class="table table-bordered" id="usuarios-table">
            <thead>
                <tr>
                    <th>Correo</th>
                    <th>Nombre Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr id="usuario-row-{{ $usuario->ID_Usuario }}">
                        <td>{{ $usuario->Correo }}</td>
                        <td>
                            @if ($usuario->Rol == 'Administrativo')
                                @if ($usuario->administradores)
                                    {{ $usuario->administradores->Nombre . ' ' . $usuario->administradores->ApePaterno . ' ' . $usuario->administradores->ApeMaterno }}
                                @else
                                    No asignado
                                @endif
                            @elseif ($usuario->Rol == 'Maestro')
                                @if ($usuario->maestros)
                                    {{ $usuario->maestros->Nombre . ' ' . $usuario->maestros->ApePaterno . ' ' . $usuario->maestros->ApeMaestro }}
                                @else
                                    No asignado
                                @endif
                            @elseif ($usuario->Rol == 'Alumno')
                                @if ($usuario->alumnos)
                                    {{ $usuario->alumnos->Nombre . ' ' . $usuario->alumnos->ApePaterno . ' ' . $usuario->alumnos->ApeMaterno }}
                                @else
                                    No asignado
                                @endif
                            @else
                                No asignado
                            @endif
                        </td>
                        <td>{{ $usuario->Rol ?? 'No asignado' }}</td>
                        <td>
                            <a href="{{ route('usuarios.show', $usuario->ID_Usuario) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('usuarios.edit', $usuario->ID_Usuario) }}"
                                class="btn btn-warning btn-sm">Editar</a>
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar-usuario"
                                data-id="{{ $usuario->ID_Usuario }}"
                                data-url="{{ route('usuarios.destroy', $usuario->ID_Usuario) }}">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $usuarios->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection

@push('scripts')
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

            const table = document.getElementById('usuarios-table');
            table.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('btn-eliminar-usuario')) {
                    e.preventDefault();
                    const button = e.target;
                    const usuarioId = button.getAttribute('data-id');
                    const url = button.getAttribute('data-url');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '¡Sí, eliminar!',
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
                                        // Remueve la fila del usuario
                                        const row = document.getElementById('usuario-row-' +
                                            usuarioId);
                                        if (row) row.remove();
                                        Swal.fire(
                                            '¡Eliminado!',
                                            data.message ?? 'El usuario ha sido eliminado.',
                                            'success'
                                        );
                                    } else {
                                        Swal.fire(
                                            'Error',
                                            data.message ??
                                            'No se pudo eliminar el usuario.',
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
@endpush
