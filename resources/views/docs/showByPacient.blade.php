@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Documentos del paciente</h2>
        <button class="btn btn-secondary mb-4" onclick="history.back()">← Regresar</button>
        @php
            $tipos = ['INE', 'ComprobanteDomicilio', 'CURP', 'Otro'];
            $pacienteId = request('id');
        @endphp
        <div class="document-grid">
            @foreach ($tipos as $tipo)
                @php
                    $documento = $documentos->firstWhere('Tipo', $tipo);
                @endphp

                <div class="document-card">
                    <div class="card-header text-center fw-bold">
                        {{ $tipo }}
                    </div>
                    <div class="card-body">
                        @if ($documento)
                            <iframe src="{{ asset('docs/' . $documento->RutaArchivo) }}" width="100%" height="200px"></iframe>
                            <div class="mt-3 text-center">
                                @if ($documento && isset($documento->ID_DocumentoPaciente))
                                    <a href="{{ route('documentos.download', $documento->ID_DocumentoPaciente) }}"
                                        class="btn btn-primary btn-sm">Descargar</a>
                                @else
                                    <p class="text-danger">Documento no disponible</p>
                                @endif
                                <button class="btn btn-warning btn-sm btn-edit mt-1"
                                    data-id="{{ $documento->ID_DocumentoPaciente }}" data-tipo="{{ $tipo }}">
                                    Editar
                                </button>
                                <button class="btn btn-danger btn-sm btn-delete mt-1"
                                    data-id="{{ $documento->ID_DocumentoPaciente }}">
                                    Eliminar
                                </button>
                            </div>
                        @else
                            <form class="upload-form mt-2" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="ID_Paciente" value="{{ $pacienteId }}">
                                <input type="hidden" name="Tipo" value="{{ $tipo }}">
                                <input type="file" name="RutaArchivo" required class="form-control mb-2">
                                <button type="submit" class="btn btn-success btn-sm">Subir {{ $tipo }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .document-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            justify-content: center;
        }

        .document-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            height: 320px;
            width: 100%;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 768px) {
            .document-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .document-card {
                height: 300px;
            }
        }

        iframe {
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SUBIR NUEVO DOCUMENTO
            document.querySelectorAll('.upload-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    try {
                        const response = await fetch("{{ route('documentos.store') }}", {
                            method: "POST",
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Documento subido correctamente.',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Error al subir documento',
                            });
                        }

                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error inesperado',
                            text: 'Ocurrió un error al subir el documento',
                        });
                    }
                });
            });

            // ELIMINAR DOCUMENTO
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', async function() {
                    const id = this.dataset.id;

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "No podrás revertir esta acción",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            const response = await fetch(
                            `/documentos/delete/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });

                            const resultDelete = await response.json();
                            if (response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: 'Documento eliminado correctamente.',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: resultDelete.message ||
                                        'Error al eliminar',
                                });
                            }
                        }
                    });
                });
            });

            // EDITAR DOCUMENTO
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const tipo = this.dataset.tipo;

                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = '*/*';

                    input.onchange = async () => {
                        const file = input.files[0];
                        if (!file) return;

                        const uploadData = new FormData();
                        uploadData.append('RutaArchivo', file);

                        try {
                            const response = await fetch(`/documentos/update/${id}`, {
                                method: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: uploadData
                            });

                            const result = await response.json();
                            if (response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Actualizado',
                                    text: 'Documento actualizado correctamente.',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: result.message ||
                                        'Error al actualizar el documento',
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error inesperado',
                                text: 'Ocurrió un error al actualizar el documento',
                            });
                        }
                    };
                    input.click();
                });
            });
        });
    </script>
@endsection
