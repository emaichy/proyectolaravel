<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota de Evolución - {{ $nota->ID_Nota }}</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 30px;
    }

    .titulo {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
        font-size: 14px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    th, td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }

    .row-header {
        background-color: #f2f2f2;
        font-weight: bold;
        vertical-align: middle;
    }

    .table-header-row {
        background-color: #f9f9f9;
        font-weight: bold;
    }

    .data-cell-left {
        text-align: left;
    }

    .data-cell-right {
        text-align: right;
    }

    .section-title {
        background-color: #d9d9d9;
        font-weight: bold;
        text-align: center;
    }

    .date-line {
        text-align: right;
        margin-bottom: 10px;
    }

    .treatment-description-table td {
        vertical-align: top;
    }

    #description-table td {
        height: 200px;
    }

    #signatures-table {
        margin-top: 30px;
        table-layout: fixed;
    }

    .firma-content {
        width: 33%;
        text-align: center;
        vertical-align: bottom;
        padding-top: 40px;
    }
.firma-content .nombre-firma {
    display: block;
    margin-top: 5px;
    font-weight: bold;
    font-size: 12px;
}

    .signature-line {
        border-top: 1px solid #000;
        margin: 5px auto 10px;
        width: 90%;
    }

    .signature-image-container {
        height: 60px;
        margin-bottom: 5px;
    }

    .signature-image {
        max-height: 100%;
        max-width: 100%;
    }

    #alumno-paciente-table th,
    #alumno-paciente-table td {
        font-size: 11px;
    }

    /* Encabezado Membretado */
    .header {
        position: relative;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        margin-bottom: 20px;
        min-height: 90px;
    }

    .header-info {
        font-size: 11px;
        line-height: 1.3;
        width: 70%;
    }

    .header-logo {
        position: absolute;
        top: -30;
        right: 0;
        width: 80px;
    }

    .header-logo img {
        height: 80px;
        object-fit: contain;
    }

   /* Ajustes para tabla de alumno/paciente */
    #alumno-paciente-table {
        margin-top: 10px; /* Espacio después de la información del encabezado */
        table-layout: auto; /* Permite que el ancho de la columna se ajuste al contenido */
        width: 100%;
        font-size: 11px;
    }

    #alumno-paciente-table th,
    #alumno-paciente-table td {
        text-align: left; /* Alineado a la izquierda para datos */
        padding: 4px 8px; /* Padding ajustado */
    }

    /* Reglas específicas para el layout de la tabla alumno-paciente */
    #alumno-paciente-table .row-header {
        width: 25%; /* Ajusta el ancho de las celdas de encabezado de fila */
        text-align: left;
    }
    #alumno-paciente-table .data-cell-name {
        width: 25%; /* Para las celdas de nombre/apellido */
    }
    #alumno-paciente-table .data-cell-semestre-grupo {
        width: 25%;
        text-align: left;
    }
    #alumno-paciente-table .data-cell-expediente {
        width: 25%;
        text-align: right; /* Derecha para el número de expediente */
    }

    </style>
</head>
<body>

    {{-- Encabezado Membretado --}}
    <div class="header">
    <div class="header-info">
        <strong>Coordinación de la Licenciatura Cirujano Dentista</strong><br>
        <small>
            Notas de evolución | NOM-004-SSA3-2012 y NOM-013-SSA2-2015<br>
            Fecha de aprobación: junio 2019<br>
            FO-CD-011
        </small>  <table id="alumno-paciente-table">
        <thead>
            <tr>
                <th class="row-header" colspan="2">Nombre del Alumno</th>
                <th class="row-header">Semestre y Grupo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="data-cell-left" colspan="2">
                    {{ $nota->alumno->ApePaterno ?? '' }} {{ $nota->alumno->ApeMaterno ?? '' }} {{ $nota->alumno->Nombre ?? '' }}
                </td>
                <td class="data-cell-semestre-grupo">
                    {{ $nota->semestre->Semestre ?? '' }} {{ $nota->grupo->NombreGrupo ?? '' }}
                </td>
            </tr>
            <tr>
                <th class="row-header" colspan="2">Nombre del Paciente</th>
                <th class="row-header">No. de Expediente</th>
            </tr>
            <tr>
                <td class="data-cell-left" colspan="2">
                    {{ $nota->paciente->ApePaterno ?? '' }} {{ $nota->paciente->ApeMaterno ?? '' }} {{ $nota->paciente->Nombre ?? '' }}
                </td>
                <td class="data-cell-expediente">
                    {{ $nota->expediente->ID_Expediente ?? '' }}
                </td>
            </tr>
        </tbody>
    </table>
    </div>
    <div class="header-logo">
        <img src="{{ public_path('logo-iufim.png') }}" alt="Logo">
    </div>
</div>


    <div class="titulo">NOTAS DE EVOLUCIÓN</div>

    {{-- Tabla de Alumno y Paciente --}}
 

    <div class="date-line">Fecha: {{ \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y') }}</div>

    {{-- Tabla de Signos Vitales --}}
    <table id="vital-signs-table">
        <thead>
            <tr>
                <th colspan="5" class="section-title">Signos Vitales</th>
            </tr>
            <tr>
                <th>Presión<br>Arterial</th>
                <th>Frecuencia<br>Cardiaca</th>
                <th>Frecuencia<br>Respiratoria</th>
                <th>Temperatura</th>
                <th>Oximetría</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $nota->presion_arterial ?? '' }}</td>
                <td>{{ $nota->frecuencia_cardiaca ?? '' }}</td>
                <td>{{ $nota->frecuencia_respiratoria ?? '' }}</td>
                <td>{{ $nota->temperatura ?? '' }}</td>
                <td>{{ $nota->oximetria ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Tratamiento Realizado --}}
    <table class="treatment-description-table">
        <thead>
            <tr>
                <th class="section-title" style="text-align: left;">Tratamiento Realizado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="data-cell-left" style="height: 40px;">{{ $nota->tratamiento_realizado ?? 'No especificado.' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Descripción del Tratamiento --}}
    <table class="treatment-description-table" id="description-table">
        <thead>
            <tr>
                <th class="section-title" style="text-align: left;">Descripción del Tratamiento</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="data-cell-left">{{ $nota->descripcion_tratamiento ?? 'No especificado.' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Firmas --}}
    <table id="signatures-table">
    <tr>
        <td class="firma-content">
            <div class="signature-image-container">
                @if($nota->firma_catedratico)
                    <img src="{{ $nota->firma_catedratico }}" class="signature-image" alt="Firma Catedrático">
                @endif
            </div>
            <div class="signature-line"></div>
            Fecha, sello y firma del Catedrático<br>
            {{ $nota->catedratico->Nombre ?? '' }} {{ $nota->catedratico->ApePaterno ?? '' }} {{ $nota->catedratico->ApeMaterno ?? '' }}
        </td>
        <td class="firma-content">
            <div class="signature-image-container">
                @if($nota->firma_alumno)
                    <img src="{{ $nota->firma_alumno }}" class="signature-image" alt="Firma Alumno">
                @endif
            </div>
            <div class="signature-line"></div>
            Nombre y Firma del Alumno<br>
            {{ $nota->alumno->Nombre ?? '' }} {{ $nota->alumno->ApePaterno ?? '' }} {{ $nota->alumno->ApeMaterno ?? '' }}
        </td>
        <td class="firma-content">
            <div class="signature-image-container">
                @if($nota->firma_paciente)
                    <img src="{{ $nota->firma_paciente }}" class="signature-image" alt="Firma Paciente">
                @endif
            </div>
            <div class="signature-line"></div>
            Nombre y Firma del paciente<br>
            {{ $nota->paciente->Nombre ?? '' }} {{ $nota->paciente->ApePaterno ?? '' }} {{ $nota->paciente->ApeMaterno ?? '' }}
        </td>
    </tr>
</table>


</body>
</html>