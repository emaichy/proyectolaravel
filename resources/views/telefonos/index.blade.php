@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Teléfonos de Pacientes</h2>
    <form method="GET" action="{{ route('telefonos.index') }}" class="mb-4">
        <div class="form-group">
            <label for="paciente_id">Filtrar por Paciente:</label>
            <select name="paciente_id" id="paciente_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Todos los Pacientes --</option>
                @foreach($pacientes as $paciente)
                    <option value="{{ $paciente->ID_Paciente }}" {{ request('paciente_id') == $paciente->ID_Paciente ? 'selected' : '' }}>
                        {{ $paciente->Nombre.' '.$paciente->ApePaterno.' '.$paciente->ApeMaterno }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Teléfono</th>
                <th>Tipo</th>
                <th>Nombre Paciente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($telefonos as $telefono)
                <tr>
                    <td>{{ $telefono->Telefono }}</td>
                    <td>{{ $telefono->Tipo }}</td>
                    <td>{{ $telefono->paciente->Nombre.' '.$telefono->paciente->ApePaterno.' '.$telefono->paciente->ApeMaterno ?? 'Desconocido' }}</td>
                    <td>
                        <a href="{{ route('telefonos.edit', $telefono->ID_TelefonoPaciente)}}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{route('telefonos.destroy', $telefono)}}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este teléfono?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay teléfonos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection