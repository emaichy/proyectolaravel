@extends($layout)
@php
    $rol = Auth::user()->Rol ?? null;
@endphp
@section('title', 'Detalle del Paciente')
@section('content')
    <div class="container">
        <h2 class="mb-4">Fotografías del paciente</h2>
        <button class="btn btn-secondary mb-4" onclick="history.back()">← Regresar</button>
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3085d6'
                    });
                });
            </script>
        @endif
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#d33'
                    });
                });
            </script>
        @endif
        @php
            $tipos = ['Centro', 'Perfil Izquierdo', 'Perfil Derecho', 'Otro'];
            $pacienteId = request('paciente');
        @endphp
        <div class="document-grid">
            @foreach ($tipos as $tipo)
                @php
                    $foto = $fotografias->firstWhere('Tipo', $tipo);
                @endphp

                <div class="document-card">
                    <div class="card-header text-center fw-bold">
                        {{ $tipo }}
                    </div>
                    <div class="card-body text-center">
                        @if ($foto)
                            <img src="{{ asset($foto->RutaArchivo) }}" alt="{{ $tipo }}"
                                style="max-width: 100%; max-height: 200px; border-radius: 5px; border: 1px solid #ccc;">
                            <div class="mt-3">
                                @if (isset($foto->ID_Fotografia))
                                    <a href="{{ route('fotografias.download', [$foto->paciente->ID_Paciente, $foto->ID_Fotografia]) }}"
                                        class="btn btn-primary btn-sm">Descargar</a>
                                @else
                                    <p class="text-danger">Fotografía no disponible</p>
                                @endif
                                @if (in_array($rol, ['Administrativo', 'Alumno']))
                                    <button class="btn btn-warning btn-sm btn-edit mt-1"
                                        data-id="{{ $foto->ID_Fotografia }}" data-tipo="{{ $tipo }}">
                                        Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-delete mt-1"
                                        data-id="{{ $foto->ID_Fotografia }}">
                                        Eliminar
                                    </button>
                                @endif
                            </div>
                        @else
                            @if (in_array($rol, ['Administrativo', 'Alumno']))
                                <form class="upload-form mt-2" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="ID_Paciente" value="{{ $pacienteId }}">
                                    <input type="hidden" name="Tipo" value="{{ $tipo }}">
                                    <input type="file" name="RutaArchivo" required class="form-control mb-2"
                                        accept="image/*">
                                    <button type="submit" class="btn btn-success btn-sm">Subir
                                        {{ $tipo }}</button>
                                </form>
                            @else
                                <div class="alert alert-info mt-3 text-center mb-0" style="font-size: 14px;">
                                    No hay archivos subidos.
                                </div>
                            @endif
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rol = @json($rol);
            if (!['Administrativo', 'Alumno'].includes(rol)) {
                document.querySelectorAll('.btn-edit, .btn-delete, .upload-form').forEach(el => {
                    if (el.tagName === 'FORM') {
                        el.remove();
                    } else {
                        el.remove();
                    }
                });
                document.querySelectorAll('.document-card').forEach(card => {
                    if (!card.querySelector('iframe')) {
                        if (!card.querySelector('.alert-info')) {
                            const noDocsDiv = document.createElement('div');
                            noDocsDiv.className = 'alert alert-info mt-3 text-center mb-0';
                            noDocsDiv.style.fontSize = '14px';
                            noDocsDiv.innerText = 'No hay archivos subidos.';
                            card.querySelector('.card-body').appendChild(noDocsDiv);
                        }
                    }
                });
            }
            document.querySelectorAll('.upload-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    try {
                        const response = await fetch("{{ route('fotografias.store') }}", {
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
                                text: 'Fotografía subida correctamente.',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Error al subir fotografía',
                            });
                        }

                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error inesperado',
                            text: 'Ocurrió un error al subir la fotografía',
                        });
                    }
                });
            });
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
                                `/fotografias/delete/${id}`, {
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
                                    text: 'Fotografía eliminada correctamente.',
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
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;

                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/*';

                    input.onchange = async () => {
                        const file = input.files[0];
                        if (!file) return;

                        const uploadData = new FormData();
                        uploadData.append('RutaArchivo', file);

                        try {
                            const response = await fetch(`/fotografias/update/${id}`, {
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
                                    text: 'Fotografía actualizada correctamente.',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: result.message ||
                                        'Error al actualizar la fotografía',
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error inesperado',
                                text: 'Ocurrió un error al actualizar la fotografía',
                            });
                        }
                    };
                    input.click();
                });
            });
        });
    </script>
@endsection
