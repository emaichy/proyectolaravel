@extends('layouts.alumno')

@section('content')
    <style>
        .nota-card {
            background: linear-gradient(135deg, #f8fafc 60%, #e2e8f0 100%);
            border: 2px solid transparent;
            border-radius: 20px;
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.07);
            padding: 18px 14px;
            transition: 0.2s ease-in-out;
        }

        .nota-card:hover {
            border-color: #2563eb;
            background: linear-gradient(135deg, #e0e7ef 60%, #dbeafe 100%);
            box-shadow: 0 8px 28px rgba(40, 124, 255, 0.14);
            transform: scale(1.02);
        }

        .nota-info {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .nota-actions .btn {
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 6px 12px;
            margin-right: 6px;
        }

        .nota-actions .btn-primary {
            background: linear-gradient(90deg, #60a5fa 60%, #2563eb 100%);
            color: #fff;
        }

        .nota-actions .btn-warning {
            background: linear-gradient(90deg, #facc15 60%, #eab308 100%);
            color: #fff;
        }

        .nota-actions .btn-danger {
            background: linear-gradient(90deg, #f87171 60%, #e11d48 100%);
            color: #fff;
        }

        .notas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 22px;
        }

        .nota-header {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 6px;
        }
    </style>
<a href="{{ route('alumno.documentoss.index', ['paciente_id' => request()->query('paciente_id')]) 
}}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Regresar a Documentos
    </a>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold text-primary">FOCD011 Notas de Evolución</h2>
            @auth
                @if(auth()->user()->Rol === 'Alumno')
                    <a href="{{ route('notasevolucion.create', ['paciente_id' => request()->query('paciente_id')]) }}"
                        class="btn btn-success rounded-pill px-4">
                        <i class="fa fa-plus me-1"></i> Nueva Nota
                    </a>
                @endif
            @endauth
        </div>

        @if(session('success'))
            <div class="alert alert-success text-center fw-semibold rounded-pill">{{ session('success') }}</div>
        @endif

        @if($notas->isEmpty())
            <div class="text-center mt-5">
                <i class="fa fa-clipboard fa-2x text-muted mb-3"></i>
                <p class="fs-5 text-muted">No hay notas de evolución registradas.</p>
            </div>
        @else
            <div class="notas-grid">
                @foreach($notas as $nota)
                    <div class="nota-card">
                        <div class="nota-header">Nota #{{ $nota->ID_Nota }}</div>
                        <div class="nota-info">Alumno: {{ $nota->alumno->Nombre }} {{ $nota->alumno->ApePaterno }} {{ $nota->alumno->ApeMaterno }}</div>
                        <div class="nota-info">Paciente: {{ $nota->paciente->Nombre }} {{ $nota->paciente->ApePaterno }} {{ $nota->paciente->ApeMaterno }}</div>
                        <div class="nota-info mb-2">Fecha: {{ $nota->fecha }}</div>

                        <div class="nota-actions d-flex flex-wrap align-items-center">
                            @if($nota->pdf_document)
                                <a href="{{ asset('storage/' . $nota->pdf_document) }}" target="_blank" class="btn btn-primary mb-2">
                                    <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            @else
                                <span class="text-muted small">PDF no disponible</span>
                            @endif

                            @if(auth()->user()->Rol === 'Maestro')
                                <a href="{{ route('maestro.notasevolucion.edit', $nota->ID_Nota) }}" class="btn btn-warning mb-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            @else
                                <a href="{{ route('notasevolucion.edit', ['notasevolucion' => $nota->ID_Nota, 'paciente_id' => request('paciente_id')]) }}" class="btn btn-sm btn-primary">
    Editar
</a>

                                <form action="{{ route('notasevolucion.destroy', $nota->ID_Nota) }}" method="POST" class="d-inline-block mb-2"
                                    onsubmit="return confirm('¿Eliminar esta nota?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endsection
