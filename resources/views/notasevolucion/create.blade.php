@extends('layouts.app')

@section('content')
<div style="text-align: left; margin-bottom: 20px;">
    <a href="{{ route('notasevolucion.index', ['paciente_id' => request()->query('paciente_id')]) 
}}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Regresar a Notas
    </a>
</div>

<form method="POST" action="{{ route('notasevolucion.store') }}">
    @csrf

    <div style="border: 2px solid #000; padding: 40px; background-color: white;">
        {{-- Encabezado --}}
        <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
            <div>
                <strong>Coordinación de la Licenciatura Cirujano Dentista</strong><br>
                <small>
                    Notas de evolución | NOM-004-SSA3-2012 y NOM-013-SSA2-2015<br>
                    <span style="color: #0056b3;">Fecha de aprobación: junio 2019</span><br>
                    FO-CD-011
                </small>
            </div>
            <div>
                <img src="{{ asset('logo-iufim.png') }}" alt="Logo" style="height: 100px;">
            </div>
        </div>

        <h3 class="text-center">NOTA DE EVOLUCIÓN</h3>

        {{-- Selección Alumno, Paciente, Semestre y Grupo --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="ID_Alumno">Alumno</label>
                @if(auth()->check() && auth()->user()->alumno)
                    <input type="hidden" name="ID_Alumno" value="{{ auth()->user()->alumno->Matricula }}">
                    <input type="text" name="nombre_alumno" class="form-control" 
                        value="{{ auth()->user()->alumno->Nombre }} {{ auth()->user()->alumno->ApePaterno }} {{ auth()->user()->alumno->ApeMaterno }}" 
                        readonly>
                @else
                    <input type="text" name="nombre_alumno" class="form-control" placeholder="Alumno no encontrado">
                @endif
            </div>
            {{-- Paciente --}}
<div class="col-md-6">
    <label for="ID_Paciente">Paciente</label>
    @if(isset($pacienteSeleccionado))
        <input type="hidden" name="ID_Paciente" value="{{ $pacienteSeleccionado->ID_Paciente }}">
        <input type="text" name="nombre_paciente" class="form-control"
            value="{{ $pacienteSeleccionado->ApePaterno }} {{ $pacienteSeleccionado->ApeMaterno }} {{ $pacienteSeleccionado->Nombre }}"
            readonly>
    @else
        <input type="text" class="form-control" value="Paciente no seleccionado" disabled>
    @endif
</div>

{{-- Expediente --}}
<div class="col-md-6">
    <label for="ID_Expediente">Expediente</label>
    <select name="ID_Expediente" class="form-control">
        <option value="">Selecciona un expediente</option>
        @foreach($expedientes as $expediente)
            <option 
                value="{{ $expediente->ID_Expediente }}" 
                {{ old('ID_Expediente') == $expediente->ID_Expediente ? 'selected' : ( (isset($expedientes[0]) && $expediente->ID_Expediente == $expedientes[0]->ID_Expediente) ? 'selected' : '' ) }}>
                Expediente #{{ $expediente->ID_Expediente }}
            </option>
        @endforeach
    </select>
</div>

            <div class="col-md-3">
                <label for="ID_Semestre">Semestre</label>
                <select name="ID_Semestre" class="form-control">
                    <option value="">Selecciona semestre</option>
                    @foreach($semestres as $semestre)
                        <option value="{{ $semestre->ID_Semestre }}" {{ old('ID_Semestre') == $semestre->ID_Semestre ? 'selected' : '' }}>
                            {{ $semestre->Semestre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="ID_Grupo">Grupo</label>
                <select name="ID_Grupo" class="form-control">
                    <option value="">Selecciona grupo</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->ID_Grupo }}" {{ old('ID_Grupo') == $grupo->ID_Grupo ? 'selected' : '' }}>
                            {{ $grupo->NombreGrupo }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Fecha --}}
        <div class="mb-3">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" class="form-control" required>
        </div>

        {{-- Signos Vitales --}}
        <div class="row mb-3">
            <div class="col-md-2"><input type="text" name="presion_arterial" placeholder="Presión Arterial" class="form-control"></div>
            <div class="col-md-2"><input type="text" name="frecuencia_cardiaca" placeholder="Frec. Cardíaca" class="form-control"></div>
            <div class="col-md-2"><input type="text" name="frecuencia_respiratoria" placeholder="Frec. Respiratoria" class="form-control"></div>
            <div class="col-md-2"><input type="text" name="temperatura" placeholder="Temperatura" class="form-control"></div>
            <div class="col-md-2"><input type="text" name="oximetria" placeholder="Oximetría" class="form-control"></div>
        </div>

        {{-- Tratamientos --}}
        <div class="mb-3">
            <label for="tratamiento_realizado">Tratamiento Realizado</label>
            <textarea name="tratamiento_realizado" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label for="descripcion_tratamiento">Descripción del Tratamiento</label>
            <textarea name="descripcion_tratamiento" class="form-control" rows="5"></textarea>
        </div>

        {{-- Firmas --}}
        <div class="row mb-4">
          @if(auth()->check() && auth()->user()->Rol === 'Alumno')
    <div class="col-md-4 text-center">
        <label><strong>Firma Alumno</strong></label><br>

        @php
            $firmaPath = auth()->user()->alumno->Firma ?? null;
        @endphp

        @if($firmaPath)
            <img src="{{ asset($firmaPath) }}" alt="Firma del alumno" style="max-width: 300px; max-height: 100px; border:1px solid #000;">
            <input type="hidden" name="firma_alumno" value="{{ asset($firmaPath) }}">
        @else
            <p class="text-danger">No hay firma registrada en tu perfil.</p>
        @endif
    </div>
@endif



            <div class="col-md-4 text-center">
                <label><strong>Firma Paciente</strong></label><br>
                <canvas id="firma_paciente_canvas" width="300" height="100" style="border:1px solid #000;"></canvas>
                <input type="hidden" name="firma_paciente" id="firma_paciente_input">
                <button type="button" onclick="limpiarCanvas('firma_paciente')" class="btn btn-sm btn-secondary mt-1">Limpiar</button>
            </div>

            @if(auth()->check() && auth()->user()->Rol === 'Maestro')
                <div class="col-md-4 text-center">
                    <label><strong>Firma Catedrático</strong></label><br>
                    <canvas id="firma_catedratico_canvas" width="300" height="100" style="border:1px solid #000;"></canvas>
                    <input type="hidden" name="firma_catedratico" id="firma_catedratico_input">
                    <button type="button" onclick="limpiarCanvas('firma_catedratico')" class="btn btn-sm btn-secondary mt-1">Limpiar</button>
                </div>
            @endif
        </div>

        <div class="text-center">
    <button type="button" class="btn btn-success" id="btnGuardarNota">
        Guardar Nota
    </button>
</div>

    </div>
</form>
</div>

{{-- Script firmas --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let pads = {};

    function limpiarCanvas(id) {
        if (pads[id]) pads[id].clear();
    }

    function capturarFirmas() {
        ['firma_catedratico', 'firma_alumno', 'firma_paciente'].forEach(id => {
            const canvas = document.getElementById(id + '_canvas');
            const input = document.getElementById(id + '_input');
            if (canvas && input && pads[id]) {
                input.value = pads[id].isEmpty() ? '' : canvas.toDataURL();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        ['firma_catedratico', 'firma_alumno', 'firma_paciente'].forEach(id => {
            const canvas = document.getElementById(id + '_canvas');
            if (canvas) {
                pads[id] = new SignaturePad(canvas);
            }
        });

        document.querySelector('form').addEventListener('submit', capturarFirmas);

        const selectExpediente = document.getElementById('select_expediente');
        const inputPacienteNombre = document.getElementById('input_paciente_nombre');
        const inputPacienteId = document.getElementById('input_paciente_id');

        selectExpediente.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const pacienteNombre = selectedOption.getAttribute('data-paciente');
            const pacienteId = selectedOption.getAttribute('data-paciente-id');

            inputPacienteNombre.value = pacienteNombre ?? '';
            inputPacienteId.value = pacienteId ?? '';
        });
    });

    document.getElementById('btnGuardarNota').addEventListener('click', function () {
    Swal.fire({
        title: '¿Guardar Nota de Evolución?',
        text: 'Verifica que toda la información esté correcta antes de continuar.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            capturarFirmas();
            document.querySelector('form').submit();
        }
    });
});

</script>
@endsection
