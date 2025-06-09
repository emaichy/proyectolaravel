@extends('layouts.app')

@section('title', 'Detalle del Paciente')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h2 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Detalle del Paciente
                </h2>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('images/pacientes/' . $paciente->Foto_Paciente) }}" alt="Foto del paciente"
                            class="img-fluid rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ $paciente->Nombre }} {{ $paciente->ApePaterno }} {{ $paciente->ApeMaterno }}
                        </h3>
                        <p class="mb-1"><strong>Fecha de nacimiento:</strong>
                            {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Género:</strong> {{ $paciente->Sexo }}</p>
                        <p class="mb-1"><strong>Dirección:</strong>
                            {{ $paciente->Direccion . ' ' . $paciente->NumeroExterior . ', ' . $paciente->NumeroInterior . ', ' . $paciente->municipio->NombreMunicipio . ', ' . $paciente->estado->NombreEstado }}
                        </p>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <div>
                        <a href="#" class="btn btn-info me-2">
                            <i class="bi bi-pencil-square me-1"></i> Ver Historial Clínico
                        </a>
                        <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('¿Estás seguro de eliminar este paciente?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
