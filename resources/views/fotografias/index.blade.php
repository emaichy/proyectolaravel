@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Fotografías de Pacientes</h2>

        <form method="GET" class="row g-3 mb-4" action="{{ route('fotografias.index') }}">
            <div class="col-md-6">
                <label for="tipo" class="form-label">Tipo de Fotografía</label>
                <select name="tipo" id="tipo" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Todos los tipos --</option>
                    <option value="Centro" {{ request('tipo') == 'Centro' ? 'selected' : '' }}>Centro</option>
                    <option value="Perfil Izquierdo" {{ request('tipo') == 'Perfil Izquierdo' ? 'selected' : '' }}>Perfil Izquierdo</option>
                    <option value="Perfil Derecho" {{ request('tipo') == 'Perfil Derecho' ? 'selected' : '' }}>Perfil
                        Derecho</option>
                    <option value="Otro" {{ request('tipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <div class="col-md-6">
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

        @if ($fotografias->isEmpty())
            <div class="alert alert-warning">No se encontraron fotografías con los filtros seleccionados.</div>
        @else
            <div class="document-grid">
                @foreach ($fotografias as $foto)
                    <div class="document-card">
                        <div class="text-center fw-bold mb-2">{{ $foto->tipo_foto ?? 'Fotografía' }}</div>
                        <div class="image-wrapper">
                            <img src="{{ asset('images/' . $foto->RutaArchivo) }}" class="preview-image" alt="Fotografía">
                            <div class="overlay" onclick="showModal('{{ asset('fotos/' . $foto->ruta_archivo) }}')">
                                <i class="bi bi-eye">Ver</i>
                            </div>
                        </div>
                        <p class="mt-2 mb-1 text-center">
                            <strong>Paciente:</strong>
                            {{ $foto->paciente->Nombre . ' ' . $foto->paciente->ApePaterno . ' ' . $foto->paciente->ApeMaterno ?? 'Desconocido' }}
                        </p>
                        <div class="text-center mt-2">
                            <a href="{{ route('fotografias.download', $foto->ID_Fotografia) }}"
                                class="btn btn-sm btn-primary">Descargar</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $fotografias->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vista previa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded"
                        style="max-height: 80vh; object-fit: contain;" alt="Vista previa">
                </div>
                <div class="modal-footer">
                    <a id="downloadButton" href="#" download class="btn btn-primary">Descargar imagen</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Usa el mismo CSS que ya tienes */
        .document-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 20px;
        }

        .image-wrapper {
            position: relative;
            width: 100%;
            height: 150px;
            overflow: hidden;
            border-radius: 5px;
        }

        .preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
            display: block;
        }

        .image-wrapper:hover .preview-image {
            transform: scale(1.03);
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .image-wrapper:hover .overlay {
            opacity: 1;
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

    <script>
        function showModal(imageUrl) {
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('downloadButton').href = imageUrl;
            modal.show();
        }
    </script>
@endsection
