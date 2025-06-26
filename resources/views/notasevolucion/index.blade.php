@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Notas de Evolución</h2>
  @auth
    @if(auth()->user()->Rol === 'Alumno')
    <a href="{{ route('notasevolucion.create', ['paciente_id' => request()->query('paciente_id')]) }}"
   class="btn btn-primary mb-3">+ Nueva Nota</a>

    @endif
@endauth

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('notasevolucion.index') }}" method="GET" class="mb-4 d-flex" style="max-width: 400px;">
   
</form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Alumno</th>
                <th>Paciente</th>
                <th>Fecha</th>
                <th>PDF</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notas as $nota)
            
                <tr>
                    {{-- Accediendo a la clave primaria 'ID_Nota' --}}
                    <td>{{ $nota->ID_Nota }}</td>
                    {{-- Accediendo a los datos de la relación alumno --}}
                    <td>{{ $nota->alumno->Nombre }} {{ $nota->alumno->ApePaterno }} {{ $nota->alumno->ApeMaterno }}</td>
                    {{-- Accediendo a los datos de la relación paciente --}}
                    <td>{{ $nota->paciente->Nombre }} {{ $nota->paciente->ApePaterno }} {{ $nota->paciente->ApeMaterno }}</td>
                    <td>{{ $nota->fecha }}</td>
                    <td>
                     
                        @if($nota->pdf_document)
                            <a href="{{ asset('storage/' . $nota->pdf_document) }}" target="_blank">Ver PDF</a>

                        @else
                            No disponible
                        @endif
                    </td>
                    <td>
                        {{-- Usando $nota->ID_Nota para las rutas --}}

                    @if(auth()->user()->Rol === 'Maestro')
                    <a href="{{ route('maestro.notasevolucion.edit', $nota->ID_Nota) }}" class="btn btn-warning">Editar como catedrático</a>
                    @else
                    <a href="{{ route('notasevolucion.edit', $nota->ID_Nota) }}" class="btn btn-primary">Editar</a>
                    @endif

                        @auth
                        @if(auth()->user()->Rol === 'Alumno' )
                        <form action="{{ route('notasevolucion.destroy', $nota->ID_Nota) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta nota?')">Eliminar</button>
                        </form>
                         @endif
                        @endauth

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection