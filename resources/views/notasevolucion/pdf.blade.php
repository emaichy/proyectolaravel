<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota de Evolución - {{ $nota->ID_Nota }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 30px;
        }

        .header {
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
            position: relative;
        }
        .header-left {
            font-size: 11px;
            line-height: 1.5;
        }

        .header-logo {
            position: absolute;
            right: 0;
            top: -10px;
        }

        .header-logo img {
            height: 70px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            margin: 10px 0 20px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        td, th {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .section-header {
            background-color: #dcdcdc;
            font-weight: bold;
            text-align: center;
        }

        .signature-block {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .signature-box {
            width: 30%;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            margin-bottom: 5px;
        }

        .signature-image {
            height: 60px;
        }

        .field-block td {
            height: 150px;
            vertical-align: top;
        }

        .date-line {
            text-align: right;
            margin-bottom: 10px;
        }
        .signature-block {
    margin-top: 40px;
    display: table;
    width: 100%;
    table-layout: fixed;
    page-break-inside: avoid;
}

.signature-box {
    display: table-cell;
    vertical-align: bottom;
    text-align: center;
    padding: 0 10px;
}

.signature-line {
    border-top: 1px solid #000;
    margin-top: 10px;
    margin-bottom: 5px;
    width: 90%;
    margin-left: auto;
    margin-right: auto;
}

.signature-image {
    max-height: 60px;
    max-width: 100%;
}

    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <strong>Coordinación de la Licenciatura Cirujano Dentista</strong><br>
            Notas de evolución | Conforme a la NOM-004-SSA3-2012 y a la NOM-013-SSA2-2015<br>
            Fecha de aprobación por la Coordinación junio 2019<br>
            FO-CD-011
        </div>
        <div class="header-logo">
            <img src="{{ public_path('logo-iufim.png') }}" alt="Logo">
        </div>
    </div>

    <div class="title">NOTAS DE EVOLUCIÓN</div>

    <table>
        <tr>
            <th colspan="2">Nombre del Alumno</th>
            <th>Semestre y Grupo</th>
        </tr>
        <tr>
            <td colspan="2">{{ $nota->alumno->ApePaterno ?? '' }} {{ $nota->alumno->ApeMaterno ?? '' }} {{ $nota->alumno->Nombre ?? '' }}</td>
            <td>{{ $nota->semestre->Semestre ?? '' }} {{ $nota->grupo->NombreGrupo ?? '' }}</td>
        </tr>
        <tr>
            <th colspan="2">Nombre del Paciente</th>
            <th>No. de Expediente</th>
        </tr>
        <tr>
            <td colspan="2">{{ $nota->paciente->ApePaterno ?? '' }} {{ $nota->paciente->ApeMaterno ?? '' }} {{ $nota->paciente->Nombre ?? '' }}</td>
            <td>{{ $nota->expediente->ID_Expediente ?? '' }}</td>
        </tr>
    </table>

    <div class="date-line">Fecha: {{ \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y') }}</div>

    <table>
        <tr>
            <th colspan="5" class="section-header">Signos Vitales</th>
        </tr>
        <tr>
            <th>Presión Arterial</th>
            <th>Frecuencia Cardiaca</th>
            <th>Frecuencia Respiratoria</th>
            <th>Temperatura</th>
            <th>Oximetría</th>
        </tr>
        <tr>
            <td>{{ $nota->presion_arterial ?? '' }}</td>
            <td>{{ $nota->frecuencia_cardiaca ?? '' }}</td>
            <td>{{ $nota->frecuencia_respiratoria ?? '' }}</td>
            <td>{{ $nota->temperatura ?? '' }}</td>
            <td>{{ $nota->oximetria ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th class="section-header">Tratamiento Realizado</th>
        </tr>
        <tr class="field-block">
            <td>{{ $nota->tratamiento_realizado ?? 'No especificado.' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th class="section-header">Descripción del Tratamiento</th>
        </tr>
        <tr class="field-block">
            <td>{{ $nota->descripcion_tratamiento ?? 'No especificado.' }}</td>
        </tr>
    </table>

    <div class="signature-block">
        <div class="signature-box">
            @if($nota->firma_catedratico)
                <img src="{{ $nota->firma_catedratico }}" class="signature-image" alt="Firma Catedrático"><br>
            @endif
            <div class="signature-line"></div>
            Fecha, sello y firma del Catedrático<br>
            {{ $nota->catedratico->Nombre ?? '' }} {{ $nota->catedratico->ApePaterno ?? '' }} {{ $nota->catedratico->ApeMaterno ?? '' }}
            {{ \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y') }}
        </div>

        <div class="signature-box">
            @if($nota->firma_alumno)
                <img src="{{ $nota->firma_alumno }}" class="signature-image" alt="Firma Alumno"><br>
            @endif
            <div class="signature-line"></div>
            Nombre y Firma del Alumno<br>
            {{ $nota->alumno->Nombre ?? '' }} {{ $nota->alumno->ApePaterno ?? '' }} {{ $nota->alumno->ApeMaterno ?? '' }}
        </div>

        <div class="signature-box">
            @if($nota->firma_paciente)
                <img src="{{ $nota->firma_paciente }}" class="signature-image" alt="Firma Paciente"><br>
            @endif
            <div class="signature-line"></div>
            Nombre y Firma del paciente<br>
            {{ $nota->paciente->Nombre ?? '' }} {{ $nota->paciente->ApePaterno ?? '' }} {{ $nota->paciente->ApeMaterno ?? '' }}
        </div>
    </div>

</body>
</html>
