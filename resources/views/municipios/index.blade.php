@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista de Municipios</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="mb-3">
        <a href="{{ route('municipios.create') }}" class="btn btn-primary">
            Crear Nuevo Municipio
        </a>
    </div>
    
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Municipio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($municipios as $municipio)
                <tr>
                    <td>{{ $municipio->NombreMunicipio }}</td>
                    <td>{{ $municipio->estado->NombreEstado ?? 'No asignado' }}</td>
                    <td>
                        <a href="{{ route('municipios.edit', $municipio->ID_Municipio) }}" 
                           class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('municipios.destroy', $municipio->ID_Municipio) }}" 
                              method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('¿Estás seguro de eliminar este municipio?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No hay municipios registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="d-flex justify-content-center">
        {{ $municipios->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection