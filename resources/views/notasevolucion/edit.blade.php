@extends('layouts.app')

@section('content')
<div style="text-align: left; margin-bottom: 20px;">
    <a href="{{ route('notasevolucion.index', ['paciente_id' => request()->query('paciente_id')]) 
}}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Regresar a Notas
    </a>
</div>

<div class="container" style="max-width: 1000px;">
    <form method="POST" action="{{ auth()->check() && auth()->user()->Rol === 'Maestro'
        ? route('maestro.notasevolucion.update', ['notasevolucion' => $nota->ID_Nota])
        : route('notasevolucion.update', ['notasevolucion' => $nota->ID_Nota]) }}">
        @csrf
        @method('PUT')

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

            <h3 class="text-center">EDITAR NOTA DE EVOLUCIÓN</h3>

            {{-- Alumno --}}
            <div class="mb-3">
                <label>Alumno</label>
                <select class="form-control" disabled>
                    @foreach($alumnos as $alumno)
                        <option value="{{ $alumno->Matricula }}" {{ $nota->ID_Alumno == $alumno->Matricula ? 'selected' : '' }}>
                            {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }} {{ $alumno->Nombre }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="ID_Alumno" value="{{ $nota->ID_Alumno }}">
            </div>

            {{-- Paciente --}}
            <div class="mb-3">
                <label>Paciente</label>
                <select class="form-control" disabled>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->ID_Paciente }}" {{ $nota->ID_Paciente == $paciente->ID_Paciente ? 'selected' : '' }}>
                            {{ $paciente->ApePaterno }} {{ $paciente->ApeMaterno }} {{ $paciente->Nombre }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="ID_Paciente" value="{{ $nota->ID_Paciente }}">
            </div>

            {{-- Expediente --}}
            <div class="mb-3">
                <label>Expediente</label>
                <select class="form-control" disabled>
                    @foreach($expedientes as $expediente)
                        <option value="{{ $expediente->ID_Expediente }}" {{ $nota->ID_Expediente == $expediente->ID_Expediente ? 'selected' : '' }}>
                            {{ $expediente->ID_Expediente }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="ID_Expediente" value="{{ $nota->ID_Expediente }}">
            </div>

            {{-- Semestre y Grupo --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Semestre</label>
                    <select class="form-control" disabled>
                        @foreach($semestres as $semestre)
                            <option value="{{ $semestre->ID_Semestre }}" {{ $nota->ID_Semestre == $semestre->ID_Semestre ? 'selected' : '' }}>
                                {{ $semestre->Semestre }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="ID_Semestre" value="{{ $nota->ID_Semestre }}">
                </div>
                <div class="col-md-6">
                    <label>Grupo</label>
                    <select class="form-control" disabled>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->ID_Grupo }}" {{ $nota->ID_Grupo == $grupo->ID_Grupo ? 'selected' : '' }}>
                                {{ $grupo->NombreGrupo }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="ID_Grupo" value="{{ $nota->ID_Grupo }}">
                </div>
            </div>

            {{-- Fecha --}}
            <div class="mb-3">
                <label>Fecha</label>
                <input type="date" name="fecha" class="form-control" value="{{ $nota->fecha }}" readonly>
            </div>

            {{-- Signos Vitales --}}
            <div class="row mb-3">
                <div class="col-md-2"><input type="text" name="presion_arterial" placeholder="Presión Arterial" class="form-control" value="{{ $nota->presion_arterial }}" readonly></div>
                <div class="col-md-2"><input type="text" name="frecuencia_cardiaca" placeholder="Frec. Cardíaca" class="form-control" value="{{ $nota->frecuencia_cardiaca }}" readonly></div>
                <div class="col-md-2"><input type="text" name="frecuencia_respiratoria" placeholder="Frec. Respiratoria" class="form-control" value="{{ $nota->frecuencia_respiratoria }}" readonly></div>
                <div class="col-md-2"><input type="text" name="temperatura" placeholder="Temperatura" class="form-control" value="{{ $nota->temperatura }}" readonly></div>
                <div class="col-md-2"><input type="text" name="oximetria" placeholder="Oximetría" class="form-control" value="{{ $nota->oximetria }}" readonly></div>
            </div>

            {{-- Tratamientos --}}
            <div class="mb-3">
                <label>Tratamiento Realizado</label>
                <textarea name="tratamiento_realizado" class="form-control" rows="2" readonly>{{ $nota->tratamiento_realizado }}</textarea>
            </div>

            <div class="mb-3">
                <label>Descripción del Tratamiento</label>
                <textarea name="descripcion_tratamiento" class="form-control" rows="5" readonly>{{ $nota->descripcion_tratamiento }}</textarea>
            </div>

            {{-- Firmas --}}
            <div class="row mb-4">
                @if(auth()->check() && auth()->user()->Rol === 'Alumno')
                    <div class="col-md-6 text-center">
                        <label><strong>Firma Alumno</strong></label><br>
                        <canvas id="firma_alumno_canvas" width="300" height="100" style="border:1px solid #000;"></canvas>
                        <input type="hidden" name="firma_alumno" id="firma_alumno_input" value="{{ $nota->firma_alumno }}">
                        <input type="hidden" id="firma_alumno_previa" value="{{ $nota->firma_alumno }}">
                        <button type="button" onclick="limpiarCanvas('firma_alumno')" class="btn btn-sm btn-secondary mt-1">Limpiar</button>
                    </div>
                    <div class="col-md-6 text-center">
                        <label><strong>Firma Paciente</strong></label><br>
                        <canvas id="firma_paciente_canvas" width="300" height="100" style="border:1px solid #000;"></canvas>
                        <input type="hidden" name="firma_paciente" id="firma_paciente_input" value="{{ $nota->firma_paciente }}">
                        <input type="hidden" id="firma_paciente_previa" value="{{ $nota->firma_paciente }}">
                        <button type="button" onclick="limpiarCanvas('firma_paciente')" class="btn btn-sm btn-secondary mt-1">Limpiar</button>
                    </div>
                @endif

                @if(auth()->check() && auth()->user()->Rol === 'Maestro')
                    <div class="col-md-6 text-center">
                        <label><strong>Firma Catedrático</strong></label><br>
                        <canvas id="firma_catedratico_canvas" width="300" height="100" style="border:1px solid #000;"></canvas>
                        <input type="hidden" name="firma_catedratico" id="firma_catedratico_input" value="{{ $nota->firma_catedratico }}">
                        <input type="hidden" id="firma_catedratico_previa" value="{{ $nota->firma_catedratico }}">
                        <button type="button" onclick="limpiarCanvas('firma_catedratico')" class="btn btn-sm btn-secondary mt-1">Limpiar</button>
                    </div>
                @endif
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Actualizar Nota</button>
            </div>
        </div>
    </form>
</div>

{{-- Script firmas --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let pads = {};
    let firmasLimpiadas = {};

    function limpiarCanvas(id) {
        if (pads[id]) {
            pads[id].clear();
            firmasLimpiadas[id] = true;
        }
    }

    function cargarFirmasDesdeBD() {
        ['firma_catedratico', 'firma_alumno', 'firma_paciente'].forEach(id => {
            const canvas = document.getElementById(id + '_canvas');
            if (!canvas) return;

            const pad = new SignaturePad(canvas);
            pads[id] = pad;

            const dataURL = document.getElementById(id + '_previa').value;
            if (dataURL && !firmasLimpiadas[id]) {
                const img = new Image();
                img.onload = () => {
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                };
                img.src = dataURL;
            }
        });
    }

    function capturarFirmas() {
        ['firma_catedratico', 'firma_alumno', 'firma_paciente'].forEach(id => {
            const input = document.getElementById(id + '_input');
            const previa = document.getElementById(id + '_previa');
            if (!input || !previa || !pads[id]) return;

            if (pads[id].isEmpty()) {
                input.value = firmasLimpiadas[id] ? '' : previa.value;
            } else {
                input.value = pads[id].toDataURL();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        cargarFirmasDesdeBD();
        document.querySelector('form').addEventListener('submit', capturarFirmas);
    });
</script>
@endsection
