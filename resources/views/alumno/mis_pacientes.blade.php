@extends('layouts.app')

@section('title', 'Mis Pacientes')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center">Pacientes Asignados</h2>

    <div class="row">
        @forelse ($pacientesAsignados as $paciente)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $paciente->Nombre }} {{ $paciente->ApePaterno }} {{ $paciente->ApeMaterno }}</h5>
                        <p class="card-text">Tipo Paciente: <br> {{ $paciente->TipoPaciente }}</p>
                        <a href="{{ route('alumno.documentoss.index', ['paciente_id' => $paciente->ID_Paciente]) }}" class="btn btn-primary">
                            Ver/Crear Documentos Expediente.
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No tienes pacientes asignados.</p>
        @endforelse
    </div>
</div>
@endsection
