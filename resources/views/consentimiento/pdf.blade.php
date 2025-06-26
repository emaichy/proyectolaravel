<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consentimiento Informado</title>
    <style>
        /* Reglas generales para la página y el cuerpo */
        html, body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px; /* Tamaño de fuente general ajustado a 10px para mayor legibilidad */
            line-height: 1.5; /* Interlineado un poco más amplio para mejor lectura */
            background-color: white;
            page-break-inside: avoid; /* Asegurarse de que el contenido no se divida en páginas */
        }

        /* Contenedor principal */
        .container {
            /* AUMENTADO: Ancho considerablemente para que ocupe más de la hoja */
            max-width: 640px; /* Ancho típico para documentos PDF tamaño carta con márgenes estándar */
            margin: 0 auto;
            padding: 25px; /* AUMENTADO: Mayor padding para un mejor aspecto */
            border: 0px solid #000; /* Borde más fino para ahorrar espacio */
            border-radius: 8px;
            box-sizing: border-box; /* Incluir padding y borde en el ancho/alto total */
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
        width: 60%;
         padding-top: 20px;
    }

    .header-logo {
        position: absolute;
        top: -10;
        right: 0;
        width: 80px;
    }

    .header-logo img {
        height: 80px;
        object-fit: contain;
    }
        /* Título principal */
        h2 {
            text-align: center;
           /* margin-top: 10px; /* Ajuste de margen superior */
            margin-bottom: 15px; /* Ajuste de margen inferior */
            font-size: 14px; /* Tamaño de fuente para el título principal ajustado */
            padding-top: -20px;
        }

        /* Secciones de contenido */
        .seccion {
            margin-bottom: 5px; /* Margen inferior ajustado para las secciones */
        }
        .seccion p {
            margin-bottom: 8px; /* Espacio entre párrafos ajustado */
            text-align: justify; /* Justificar el texto para mejor lectura */
        }

        /* Bloque de firmas - AHORA USANDO TABLA */
        .firma-table {
            width: 100%; /* La tabla ocupará todo el ancho */
            border-collapse: collapse; /* Elimina el espacio entre celdas */
            margin-top: 30px; /* Margen superior para separar de las secciones */
            table-layout: fixed; /* Ayuda a que las columnas tengan anchos iguales */
        }

        .firma-table td {
            width: 25%; /* Cada celda ocupará el 25% del ancho para 4 firmas */
            padding: 0 10px; /* Padding horizontal un poco mayor para las celdas de firma */
            vertical-align: top; /* Alinea el contenido de la celda arriba */
        }

        .firma-box {
            border: 1px solid black; /* Cajita para cada firma */
            padding: 2px; /* Espacio dentro de la cajita, un poco mayor */
            text-align: center;
            font-size: 9px; /* Tamaño de fuente para el texto de la firma ajustado */
            box-sizing: border-box; /* Asegura que padding y borde se incluyan en el ancho */
            height: 90px; /* Altura fija para la caja de firma, ajustada */
            display: flex; /* Usamos flexbox también dentro de cada caja para centrar contenido */
            flex-direction: column; /* Contenido en columna dentro de la caja */
            justify-content: space-between; /* Distribuye el espacio entre los elementos internos */
            align-items: center; /* Centra horizontalmente el contenido interno */
        }

        .firma-box img {
            max-height: 45px; /* Altura máxima de la imagen de firma ajustada */
            display: block;
            margin: 0 auto; /* Centra la imagen */
            width: auto;
            max-width: 95%; /* Asegura que la imagen no desborde la caja */
        }
        .firma-box p {
            margin: 0;
            line-height: 1; /* Interlineado ajustado */
            word-break: break-word; /* Rompe palabras largas si es necesario para que quepan */
            padding: 0 4px; /* Padding horizontal ajustado para el texto */
    }

        /* Asegurarse de que los elementos dentro del contenedor no se rompan entre páginas */
        .container > div {
            page-break-inside: avoid;
        }

        /* Estilo para el nombre de la persona en la firma */
        .firma-box p:first-child {
            min-height: 1.2em; /* Asegura un espacio mínimo para el nombre aunque esté vacío */
            font-weight: bold; /* El nombre de la persona puede ir en negritas */
        }
    </style>
</head>
<body>

    <div class="container">

       {{-- Encabezado Membretado --}}
    <div class="header">
    <div class="header-info">
        <strong>Coordinación de la Licenciatura Cirujano Dentista</strong><br>
        <small>
            Carta de consentimiento Informado | Conforme a la NOM-004-SSA3-2012 y a la NOM-013-SSA2-2015<br>
            Fecha de aprobación por la Coordinación octubre 2019<br>
            FO-CD-004.2
        </small>
    </div>
    <div class="header-logo">
        <img src="{{ public_path('logo-iufim.png') }}" alt="Logo">
    </div>
</div>

        <h2><b>CARTA DE CONSENTIMIENTO INFORMADO</b></h2>

        <div class="seccion" style="text-align: right;">
            <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($consentimiento->fecha)->format('d/m/Y') }}
        </div>

        <div class="seccion">
            <p><b>Declaración del paciente:</b></p>
            <p>{{ $consentimiento->declaracion }}</p>
            <p>Declaro que he sido informado/a satisfactoriamente de la naturaleza y propósito del procedimiento clínico bucal citado.</p>
            <p>Declaro que me han sido explicados verbalmente los posibles riesgos y complicaciones de dicho procedimiento clínico bucal, así como la existencia de otras alternativas de tratamiento. Además, he sido informado del tipo de anestesia y de los riesgos comúnmente conocidos que conlleva.</p>
            <p>El estomatólogo me ha explicado otros problemas y complicaciones poco frecuentes, derivadas del tratamiento bucal que consiste en:</p>
            <p>{{ $consentimiento->descripcion_tratamiento }}</p>
            <p><b>Aceptación del paciente:</b></p>
            <p>Acepto y me comprometo a seguir responsablemente las recomendaciones recibidas, antes y después de la intervención, así como acudir a las citas para las revisiones postoperatorias durante el tiempo indicado.</p>
            <p>Acepto y reconozco que no se me pueden dar garantías o seguridad absoluta respecto a que el resultado del procedimiento clínico-bucal sea el más satisfactorio, por lo que acepto la posibilidad de necesitar cualquier posterior intervención para mejorar el resultado final.</p>
            <p>Acepto firmar este consentimiento informado y manifiesto que el alumno tratante:</p>
            <p>{{ $consentimiento->alumno_tratante }}</p>
            <p>y los docentes a cargo:</p>
            <p>{{ $consentimiento->docentes }}</p>
            <p>Me han informado del procedimiento clínico al que deseo ser sometida/o.</p>
            <p>Finalmente, acepto que el personal de salud reciba información sobre mi estado de salud.</p>
        </div>

        {{-- BLOQUE DE FIRMAS AHORA ES UNA TABLA --}}
        <table class="firma-table">
            <tr>
                @foreach(['paciente', 'alumno', 'docentes', 'testigo'] as $persona)
                    @php
                        $nombreCampo = 'nombre_' . $persona;
                        $firmaCampo = 'firma_' . $persona;
                    @endphp
                    <td>
                        <div class="firma-box">
                            <p>{{ $consentimiento->$nombreCampo ?? ucfirst($persona) }}</p>
                            @if(Str::startsWith($consentimiento->$firmaCampo, 'data:image'))
                                <img src="{{ $consentimiento->$firmaCampo }}" alt="Firma {{ $persona }}">
                            @else
                                <p><em>Firma no disponible</em></p>
                            @endif
                            <p><strong>Firma de {{ ucfirst($persona) }}</strong></p>
                        </div>
                    </td>
                @endforeach
            </tr>
        </table>

    </div>
</body>
</html>