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
                            {{ \Carbon\Carbon::parse($paciente->FechaNac)->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Género:</strong> {{ $paciente->Sexo }}</p>
                        <p class="mb-1"><strong>Dirección:</strong>
                            {{ $paciente->Direccion . ' ' . $paciente->NumeroExterior . ', ' . $paciente->NumeroInterior . ', ' . $paciente->municipio->NombreMunicipio . ', ' . $paciente->estado->NombreEstado }}
                        </p>
                        <p class="mb-1"><strong>Teléfono Celular:</strong>
                            <span id="telefonoCelular">Cargando...</span>
                            <span id="celularActions"></span>
                        </p>
                        <p class="mb-1"><strong>Teléfono Casa:</strong>
                            <span id="telefonoCasa">Cargando...</span>
                            <span id="casaActions"></span>
                        </p>
                        <p class="mb-1"><strong>Teléfono Trabajo:</strong>
                            <span id="telefonoTrabajo">Cargando...</span>
                            <span id="trabajoActions"></span>
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
                        <a href="{{ route('documentos.byPaciente', $paciente->ID_Paciente) }}" class="btn btn-success me-2">
                            <i class="bi bi-plus-square"></i> Ver Documentos Adjuntos
                        </a>
                        <a href="{{ route('radiografias.byPaciente', $paciente->ID_Paciente) }}"
                            class="btn btn-success me-2">
                            <i class="bi bi-plus-square"></i> Ver Radiografías
                        </a>
                        <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <button type="button" class="btn btn-danger" id="deletePacienteBtn"
                            data-id="{{ $paciente->ID_Paciente }}" data-url="{{ route('pacientes.destroy', $paciente) }}">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="telefonoModal" tabindex="-1" aria-labelledby="telefonoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="telefonoModalLabel">Agregar Teléfono</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="telefonoForm" method="POST">
                    @csrf
                    <input type="hidden" name="ID_Paciente" value="{{ $paciente->ID_Paciente }}">
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Número de Teléfono</label>
                            <input type="tel" name="telefono" id="telefono" class="form-control" pattern="[0-9]{10}"
                                title="Ingrese un número de teléfono válido (10 dígitos)" required
                                placeholder="Ej: 22112233">
                            <div class="invalid-feedback">
                                Por favor ingrese un número de teléfono válido (10 dígitos).
                            </div>
                        </div>
                        <input type="hidden" name="tipo" id="tipo" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            const pacienteId = {{ $paciente->ID_Paciente }};
            const modal = new bootstrap.Modal(document.getElementById('telefonoModal'));

            function formatTelefonos(telefonos) {
                return telefonos.length ? telefonos.map(t => t.Telefono).join(', ') : 'No registrado';
            }

            function renderActionButtons(tipo, telefonos) {
                const containerId = `${tipo.toLowerCase()}Actions`;
                const container = $(`#${containerId}`);
                container.empty();
                if (telefonos.length > 0) {
                    telefonos.forEach(telefono => {
                        const btnGroup = $(`
                <div class="btn-group ms-2" role="group">
                    <button class="btn btn-sm btn-outline-primary edit-btn" 
                        data-id="${telefono.ID_TelefonoPaciente}" 
                        data-telefono="${telefono.Telefono}" 
                        data-tipo="${telefono.Tipo}">
                        <i class="bi bi-pencil"></i> Editar
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-btn" 
                        data-id="${telefono.ID_TelefonoPaciente}">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            `);
                        container.append(btnGroup);
                    });
                } else {
                    const addBtn = $(`
            <button class="btn btn-sm btn-success ms-2 add-btn" data-tipo="${tipo}">
                <i class="bi bi-plus"></i> Agregar
            </button>
        `);
                    container.append(addBtn);
                }
            }

            function loadTelefonos() {
                $.get(`/telefonos/getAllByPaciente/${pacienteId}`, function(data) {
                    const celularData = data?.Celular || [];
                    const casaData = data?.Casa || [];
                    const trabajoData = data?.Trabajo || [];
                    $('#telefonoCelular').text(formatTelefonos(celularData));
                    $('#telefonoCasa').text(formatTelefonos(casaData));
                    $('#telefonoTrabajo').text(formatTelefonos(trabajoData));
                    renderActionButtons('Celular', celularData);
                    renderActionButtons('Casa', casaData);
                    renderActionButtons('Trabajo', trabajoData);
                    $('.add-btn').off().click(function() {
                        const tipo = $(this).data('tipo');
                        $('#telefonoForm').attr('action', '/telefonos/create');
                        $('#formMethod').val('POST');
                        $('#telefono').val('');
                        $('#tipo').val(tipo);
                        $('#telefonoModalLabel').text(`Agregar Teléfono ${tipo}`);
                        modal.show();
                    });
                    $('.edit-btn').off().click(function() {
                        const id = $(this).data('id');
                        const telefono = $(this).data('telefono');
                        const tipo = $(this).data('tipo');
                        $('#telefonoForm').attr('action', `/telefonos/edit/${id}`);
                        $('#formMethod').val('PUT');
                        $('#telefono').val(telefono);
                        $('#tipo').val(tipo);
                        $('#telefonoModalLabel').text(`Editar Teléfono ${tipo}`);
                        modal.show();
                    });
                    $('.delete-btn').off().click(function() {
                        const id = $(this).data('id');
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: 'Este teléfono se eliminará permanentemente.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `/telefonos/delete/${id}`,
                                    type: 'DELETE',
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Eliminado',
                                            text: 'Teléfono eliminado con éxito.',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                        loadTelefonos();
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'No se pudo eliminar el teléfono. Intenta nuevamente.'
                                        });
                                    }
                                });
                            }
                        });

                    });

                }).fail(function() {
                    $('#telefonoCelular, #telefonoCasa, #telefonoTrabajo').text(
                        'Error al cargar teléfonos');
                });
            }
            $('#telefonoForm').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const method = $('#formMethod').val();

                $.ajax({
                    url: url,
                    method: method === 'POST' ? 'POST' : 'PUT',
                    data: form.serialize(),
                    success: function() {
                        modal.hide();
                        loadTelefonos();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Teléfono guardado!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo guardar el teléfono. Intenta nuevamente.'
                        });
                    }

                });
            });
            loadTelefonos();
            let telefonoDeleteUrl = '';

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                telefonoDeleteUrl = `/telefonos/delete/${id}`;
                deleteModal.show();
            });

            $('#confirmDeleteTelefono').click(function() {
                $.ajax({
                    url: telefonoDeleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        deleteModal.hide();
                        loadTelefonos();
                    },
                    error: function() {
                        alert('Error al eliminar el teléfono');
                    }
                });
            });

        });
    </script>
@endsection
