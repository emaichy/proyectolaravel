@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista de Maestros</h1>
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            </script>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <div class="mb-3">
                    <a href="{{ route('maestros.create') }}" class="btn btn-primary">Agregar Maestro</a>
                </div>
                @foreach ($maestros as $maestro)
                    <tr>
                        <td>{{ $maestro->Nombre . ' ' . $maestro->ApePaterno . ' ' . $maestro->ApeMaestro }}</td>
                        <td>{{ $maestro->usuario->Correo }}</td>
                        <td>{{ $maestro->Telefono }}</td>
                        <td>{{ $maestro->Direccion . ' ' . $maestro->NumeroInterior . ', ' . $maestro->NumeroExterior . ', ' . $maestro->CodigoPostal . ', ' . $maestro->municipio->NombreMunicipio . ', ' . $maestro->estado->NombreEstado . ', ' . $maestro->Pais }}
                        </td>
                        <td>
                            <a href="{{ route('maestros.show', $maestro->ID_Maestro) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{route('maestros.edit', $maestro->ID_Maestro)}}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('maestros.destroy', $maestro->ID_Maestro) }}" method="POST"
                                style="display:inline;" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete(event)">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div>
            {{ $maestros->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <script>
        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    </script>
@endsection
