@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>FOCD004.2 - CARTA SSA DE CONSENTIMIENTO</h2>

  <a href="{{ route('consentimiento.create', ['paciente_id' => request()->query('paciente_id')]) }}" class="btn btn-primary mb-3">
    Crear Nueva Carta de Consentimiento
</a>


    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Alumno</th>
                <th>Paciente</th>
                <th>Expediente</th>
                <th>Fecha</th>
                <th>PDF</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consentimientos as $consentimiento)
                <tr>
                    <td>{{ $consentimiento->id }}</td>
                    <td>{{ $consentimiento->alumno->Nombre ?? '---' }} {{ $consentimiento->alumno->ApePaterno ?? '' }}</td>
                    <td>{{ $consentimiento->paciente->Nombre ?? '---' }} {{ $consentimiento->paciente->ApePaterno ?? '' }}</td>
                    <td>{{ $consentimiento->expediente->ID_Expediente ?? '---' }}</td>
                    <td>{{ $consentimiento->fecha }}</td>
                    <td>
                        @if($consentimiento->pdf_document)
                            <a href="{{ Storage::url($consentimiento->pdf_document) }}" target="_blank">Ver PDF</a>
                        @else
                            No disponible
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('consentimiento.show', $consentimiento) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('consentimiento.edit', $consentimiento) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('consentimiento.destroy', $consentimiento) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta carta?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay cartas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
