@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Grupos</h2>
        <a href="{{ route('grupos.create') }}" class="btn btn-primary">Agregar Grupo</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grupos as $grupo)
                <tr>
                    <td>{{ $grupo->ID_Grupo }}</td>
                    <td>{{ $grupo->NombreGrupo }}</td>
                    <td>
                        <a href="{{ route('grupos.show', $grupo->ID_Grupo) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('grupos.edit', $grupo->ID_Grupo) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('grupos.destroy', $grupo->ID_Grupo) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este grupo?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay grupos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $grupos->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection