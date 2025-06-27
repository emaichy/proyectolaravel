@extends($layout)

@section('title', 'Historial Clínico')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h2 class="mb-0">
                <i class="bi bi-folder2-open me-2"></i>
                Historial Clínico
            </h2>
        </div>
        <div class="card-body">
            {{-- Datos del paciente --}}
            <h4 class="mb-3">Datos del Paciente</h4>
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    @php
                        $foto = $expediente->paciente->Foto_Paciente && file_exists(public_path('storage/' . $expediente->paciente->Foto_Paciente))
                            ? asset('storage/' . $expediente->paciente->Foto_Paciente)
                            : asset('avatar.png');
                    @endphp
                    <img src="{{ $foto }}" alt="Foto Paciente" class="img-fluid rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <div class="col-md-9">
                    <h5 class="fw-bold">{{ $expediente->paciente->Nombre }} {{ $expediente->paciente->ApePaterno }} {{ $expediente->paciente->ApeMaterno }}</h5>
                    <p class="mb-1"><strong>CURP:</strong> {{ $expediente->paciente->Curp ?? 'No registrado' }}</p>
                    <p class="mb-1"><strong>Sexo:</strong> {{ $expediente->paciente->Sexo }}</p>
                    <p class="mb-1"><strong>Fecha de Nac.:</strong> {{ \Carbon\Carbon::parse($expediente->paciente->FechaNac)->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>Dirección:</strong> {{ $expediente->paciente->Direccion }}</p>
                </div>
            </div>
            <hr>
            {{-- Datos del expediente --}}
            <h4 class="mb-3">Expediente</h4>
            <dl class="row">
                <dt class="col-sm-4">ID Expediente</dt>
                <dd class="col-sm-8">{{ $expediente->ID_Expediente }}</dd>

                <dt class="col-sm-4">Tipo</dt>
                <dd class="col-sm-8">{{ $expediente->TipoExpediente }}</dd>

                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">
                    @if ($expediente->Status)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-danger">Inactivo</span>
                    @endif
                </dd>
            </dl>
            <hr>
            {{-- Alumnos asignados --}}
            <h4 class="mb-3">Alumno(s) Asignado(s)</h4>
            @if($expediente->alumnos->isEmpty())
                <div class="alert alert-warning">No hay alumnos asignados a este expediente.</div>
            @else
                <ul class="list-group mb-4">
                    @foreach($expediente->alumnos as $alumno)
                        <li class="list-group-item">
                            <i class="bi bi-person"></i>
                            {{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}
                            <span class="text-muted">(Matrícula: {{ $alumno->Matricula }})</span>
                        </li>
                    @endforeach
                </ul>
            @endif
            {{-- Anexos --}}
            <h4 class="mb-3">Anexos</h4>
            @if($expediente->anexos->isEmpty())
                <div class="alert alert-info">Sin anexos registrados.</div>
            @else
                <ul class="list-group">
                    @foreach($expediente->anexos as $anexo)
                        <li class="list-group-item">
                            <i class="bi bi-paperclip"></i>
                            <a href="{{ asset('storage/' . $anexo->Archivo) }}" target="_blank">
                                {{ $anexo->NombreArchivo ?? 'Archivo adjunto' }}
                            </a>
                            <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($anexo->created_at)->format('d/m/Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
            <hr>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection