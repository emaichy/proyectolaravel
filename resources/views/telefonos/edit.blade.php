@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Teléfono</h2>

        <form action="{{ route('telefonos.update', $telefono->ID_TelefonoPaciente) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" name="ID_Paciente" value="{{ $telefono->ID_Paciente }}">

            <div class="mb-3">
                <label for="Telefono" class="form-label">Número de Teléfono</label>
                <input type="tel" name="Telefono" id="Telefono" class="form-control" pattern="[0-9]{10}"
                    title="Ingrese un número de teléfono válido (10 dígitos)" required 
                    value="{{ old('Telefono', $telefono->Telefono) }}" placeholder="Ej: 22112233">
                <div class="invalid-feedback">
                    Por favor ingrese un número de teléfono válido (10 dígitos).
                </div>
                <small class="form-text text-muted">
                    Solo números, sin guiones ni espacios.
                </small>
            </div>

            <div class="mb-3">
                <label for="Tipo" class="form-label">Tipo de Teléfono</label>
                <select name="Tipo" id="Tipo" class="form-select" required>
                    <option value="" disabled>Seleccione un tipo</option>
                    <option value="Celular" {{ old('Tipo', $telefono->Tipo) == 'Celular' ? 'selected' : '' }}>Celular</option>
                    <option value="Casa" {{ old('Tipo', $telefono->Tipo) == 'Casa' ? 'selected' : '' }}>Casa</option>
                    <option value="Trabajo" {{ old('Tipo', $telefono->Tipo) == 'Trabajo' ? 'selected' : '' }}>Trabajo</option>
                </select>
                <div class="invalid-feedback">
                    Por favor seleccione un tipo de teléfono.
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('telefonos.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Actualizar Teléfono
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });

            const telefonoInput = document.getElementById('Telefono');
            if (telefonoInput) {
                telefonoInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .container {
            max-width: 800px;
            margin-top: 30px;
        }

        h2 {
            margin-bottom: 25px;
            color: #2c3e50;
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 10px;
        }

        .form-label {
            font-weight: 500;
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
        }

        .was-validated .form-control:invalid~.invalid-feedback,
        .was-validated .form-select:invalid~.invalid-feedback {
            display: block;
        }
    </style>
@endsection