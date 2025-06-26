@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Carta de Consentimiento Informado: {{ $consentimiento->id }}</h3>

    <p><strong>Alumno Tratante:</strong> {{ $consentimiento->alumno->Nombre }} {{ $consentimiento->alumno->ApePaterno }} {{ $consentimiento->alumno->ApeMaterno }}</p>
    <p><strong>Paciente:</strong> {{ $consentimiento->paciente->Nombre }} {{ $consentimiento->paciente->ApePaterno }} {{ $consentimiento->paciente->ApeMaterno }}</p>
    <p><strong>Fecha:</strong> {{ $consentimiento->fecha }}</p>
    <p><strong>Descripción del Tratamiento:</strong><br>{{ $consentimiento->descripcion_tratamiento }}</p>
    <p><strong>Docentes a Cargo:</strong><br>{{ $consentimiento->docentes }}</p>



    <a href="{{ route('consentimiento.index') }}" class="btn btn-secondary">Regresar</a>
</div>
@endsection
