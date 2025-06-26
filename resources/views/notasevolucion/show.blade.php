@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Nota de Evolución ID: {{ $nota->ID_Nota }}</h3>

    <p><strong>Alumno:</strong> {{ $nota->alumno->Nombre }} {{ $nota->alumno->ApePaterno }} {{ $nota->alumno->ApeMaterno }}</p>
    <p><strong>Paciente:</strong> {{ $nota->paciente->Nombre }} {{ $nota->paciente->ApePaterno }} {{ $nota->paciente->ApeMaterno }}</p>
    <p><strong>Expediente:</strong> {{ $nota->expediente->ID_Expediente ?? 'N/A' }}</p>
    <p><strong>Semestre:</strong> {{ $nota->semestre->Semestre ?? 'N/A' }}</p>
    <p><strong>Grupo:</strong> {{ $nota->grupo->NombreGrupo ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y') }}</p>

    <h5>Signos Vitales</h5>
    <ul>
       <li><strong>Presión Arterial:</strong> {{ $nota['presion_arterial'] ?? 'No registrado' }}</li>
<li><strong>Frecuencia Cardiaca:</strong> {{ $nota['frecuencia_cardiaca'] ?? 'No registrado' }}</li>
<li><strong>Frecuencia Respiratoria:</strong> {{ $nota['frecuencia_respiratoria'] ?? 'No registrado' }}</li>
<li><strong>Temperatura:</strong> {{ $nota['temperatura'] ?? 'No registrado' }}</li>
<li><strong>Oximetría:</strong> {{ $nota['oximetria'] ?? 'No registrado' }}</li>

    </ul>

    <h5>Tratamiento Realizado</h5>
   <p>{{ $nota['tratamiento_realizado'] ?? 'No registrado' }}</p>


    <h5>Descripción del Tratamiento</h5>
<p>{{ $nota['descripcion_tratamiento'] ?? 'No registrado' }}</p>


    <h5>Firmas</h5>
    <div style="display: flex; gap: 20px;">
        @foreach(['catedratico', 'alumno', 'paciente'] as $firma)
            @if(!empty($nota->{'firma_'.$firma}))
                <div>
                    <strong>{{ ucfirst($firma) }}</strong><br>
                    <img src="{{ $nota->{'firma_'.$firma} }}" alt="Firma {{ $firma }}" style="border: 1px solid #000; max-width: 300px; height: auto;">
                </div>
            @endif
        @endforeach
    </div>

    <hr>

   @if($nota->pdf_document)
    <a href="{{ asset('storage/' . $nota->pdf_document) }}" download class="btn btn-primary">
        Descargar Documento PDF
    </a>
@endif


    <a href="{{ route('notasevolucion.index') }}" class="btn btn-secondary">Regresar</a>
</div>
@endsection
