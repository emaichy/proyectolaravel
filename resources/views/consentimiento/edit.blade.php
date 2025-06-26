@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: 20px;">

    <div style="text-align: left; margin-bottom: 20px;">
        <a href="{{ route('consentimiento.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
    </div>

    <div style="border: 2px solid #000; padding: 30px; background-color: white; border-radius: 8px;">
        <form method="POST" action="{{ route('consentimiento.update', $consentimiento->id) }}" id="consentimientoForm">
            @csrf
            @method('PUT')

            {{-- Selecciones --}}
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
                <div style="flex: 1 1 45%;">
                    <label><b>Alumno</b></label>
                    <select name="ID_Alumno" class="form-control" required>
                        @foreach($alumnos as $alumno)
                            <option value="{{ $alumno->Matricula }}" {{ $alumno->Matricula == $consentimiento->ID_Alumno ? 'selected' : '' }}>
                                {{ $alumno->Nombre }} {{ $alumno->ApePaterno }} {{ $alumno->ApeMaterno }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1 1 45%;">
                    <label><b>Paciente</b></label>
                    <select name="ID_Paciente" class="form-control" required>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->ID_Paciente }}" {{ $paciente->ID_Paciente == $consentimiento->ID_Paciente ? 'selected' : '' }}>
                                {{ $paciente->Nombre }} {{ $paciente->ApePaterno }} {{ $paciente->ApeMaterno }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label><b>Expediente</b></label>
                <select name="ID_Expediente" class="form-control" required>
                    @foreach($expedientes as $expediente)
                        <option value="{{ $expediente->ID_Expediente }}" {{ $expediente->ID_Expediente == $consentimiento->ID_Expediente ? 'selected' : '' }}>
                            {{ $expediente->ID_Expediente }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="text-align: right; margin-bottom: 20px;">
                <label><strong>Fecha:</strong></label>
                <input type="date" name="fecha" value="{{ old('fecha', $consentimiento->fecha) }}" required>
            </div>

            <h3 style="text-align:center; margin-bottom: 20px;"><b>CARTA DE CONSENTIMIENTO INFORMADO</b></h3>

            <p><b>Declaración del paciente:</b></p>
            <textarea name="declaracion" rows="1" class="form-control" required>{{ old('declaracion', $consentimiento->declaracion) }}</textarea> <br>

            <p>Declaro que he sido informado/a satisfactoriamente...</p>
            <p>... consiste en:</p>
            <textarea name="descripcion_tratamiento" rows="2" class="form-control" required>{{ old('descripcion_tratamiento', $consentimiento->descripcion_tratamiento) }}</textarea>

            <p><b>Aceptación del paciente</b></p>
            <p>... manifiesto que el alumno tratante:</p>
            <input name="alumno_tratante" class="form-control" value="{{ old('alumno_tratante', $consentimiento->alumno_tratante) }}" required>

            <p>Y los Docentes a cargo:</p>
            <textarea name="docentes" rows="2" class="form-control" required>{{ old('docentes', $consentimiento->docentes) }}</textarea>

            <p><b>Firmas:</b></p>

            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                @foreach(['paciente', 'alumno', 'docentes', 'testigo'] as $firma)
                <div style="flex: 1 1 45%; text-align: center;">
                    <label>Nombre {{ ucfirst($firma) }}</label>
                    <input type="text" name="nombre_{{ $firma }}" class="form-control"
                        value="{{ old('nombre_'.$firma, $consentimiento->{'nombre_'.$firma}) }}">

                    <label>Firma {{ ucfirst($firma) }}</label><br>
                    <canvas class="signature" style="border:1px solid #000; width: 100%; height: 100px;"></canvas>
                    <input type="hidden" name="firma_{{ $firma }}" value="{{ old('firma_'.$firma, $consentimiento->{'firma_'.$firma}) }}">
                    <button type="button" class="clear-btn btn btn-sm btn-danger mt-2">Limpiar</button>
                </div>
                @endforeach
            </div>

            <br>
            <div style="text-align: center;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 30px;">Actualizar</button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    const signaturePads = [];

    function resizeCanvas(canvas) {
        const ratio = window.devicePixelRatio || 1;
        const width = canvas.offsetWidth;
        const height = canvas.offsetHeight;
        canvas.width = width * ratio;
        canvas.height = height * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    document.querySelectorAll('canvas.signature').forEach((canvas, index) => {
        resizeCanvas(canvas);
        const signaturePad = new SignaturePad(canvas);
        const input = canvas.nextElementSibling;

        // Cargar firma si existe
        const existingDataUrl = input.value;
        if (existingDataUrl) {
            const img = new Image();
            img.onload = () => {
                signaturePad.clear();
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, canvas.width / (window.devicePixelRatio || 1), canvas.height / (window.devicePixelRatio || 1));
            };
            img.src = existingDataUrl;
        }

        signaturePads.push({ signaturePad, input });

        canvas.closest('form').addEventListener('submit', () => {
            input.value = signaturePad.isEmpty() ? '' : signaturePad.toDataURL();
        });
    });

    window.addEventListener('resize', () => {
        document.querySelectorAll('canvas.signature').forEach((canvas, index) => {
            const prevData = signaturePads[index].signaturePad.toData();
            resizeCanvas(canvas);
            signaturePads[index].signaturePad.clear();
            signaturePads[index].signaturePad.fromData(prevData);
        });
    });

    document.querySelectorAll('.clear-btn').forEach((btn, index) => {
        btn.addEventListener('click', () => {
            signaturePads[index].signaturePad.clear();
            signaturePads[index].input.value = '';
        });
    });
</script>
@endsection
