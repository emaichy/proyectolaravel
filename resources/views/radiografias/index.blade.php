@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Radiografías de Pacientes</h2>

        <form method="GET" class="row g-3 mb-4" action="{{ route('radiografias.index') }}">
            <div class="col-md-12">
                <label for="paciente_id" class="form-label">Paciente</label>
                <select name="paciente_id" id="paciente_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Todos los pacientes --</option>
                    @foreach ($pacientes as $paciente)
                        <option value="{{ $paciente->ID_Paciente }}"
                            {{ request('paciente_id') == $paciente->ID_Paciente ? 'selected' : '' }}>
                            {{ $paciente->Nombre . ' ' . $paciente->ApePaterno . ' ' . $paciente->ApeMaterno }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        @if ($radiografias->isEmpty())
            <div class="alert alert-warning">No se encontraron radiografías para el paciente seleccionado.</div>
        @else
            <div class="document-grid">
                @foreach ($radiografias as $radio)
                    <div class="document-card">
                        <div class="text-center fw-bold mb-2">{{ $radio->TipoRadiografia ?? 'Radiografía' }}</div>
                        <iframe src="{{ asset('docs/radiografias/' . $radio->RutaArchivo) }}" width="100%" height="150px"></iframe>

                        <p class="mt-2 mb-1 text-center">
                            <strong>Paciente:</strong>
                            {{ $radio->paciente->Nombre ?? '' }} {{ $radio->paciente->ApePaterno ?? '' }} {{ $radio->paciente->ApeMaterno ?? '' }}
                        </p>

                        <div class="text-center">
                            <a href="{{ route('radiografias.download', $radio->ID_Radiografia) }}" class="btn btn-sm btn-primary">Descargar</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $radiografias->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <style>
        .document-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 20px;
        }

        @media (min-width: 576px) {
            .document-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 768px) {
            .document-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 992px) {
            .document-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .document-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            background-color: #f8f9fa;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }

        iframe {
            border-radius: 5px;
            border: 1px solid #aaa;
        }
    </style>
@endsection
