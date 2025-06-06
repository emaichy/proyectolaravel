@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Usuarios Registrados</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Correo</th>
                <th>Contraseña</th>
                <th>Rol</th>
                <th>Fecha de Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->ID_Usuario }}</td>
                    <td>{{ $usuario->Correo }}</td>
                    <td>{{ $usuario->password }}</td>
                    <td>{{ $usuario->Rol ?? 'No asignado' }}</td>
                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('usuarios.show', $usuario->ID_Usuario) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('usuarios.edit', $usuario->ID_Usuario) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('usuarios.destroy', $usuario->ID_Usuario) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay usuarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Agregar Usuario</a>
</div>
@endsection