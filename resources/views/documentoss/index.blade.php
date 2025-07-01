@extends('layouts.alumno')

@section('title', 'Gestión de Documentos')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2>Documentos Clínicos de {{ $alumno->Nombre }} {{ $alumno->ApePaterno }}  {{ $alumno->ApeMaterno }}</h2>
        <p class="text-muted">Selecciona el tipo de documento para consultar o crear.</p>
    </div>
    <div class="alert alert-info">
    <strong>Matrícula del alumno actual:</strong> {{ $alumno->Matricula }}
</div>

    {{-- FOCD004.2 - CARTA SSA DE CONSENTIMIENTO --}}
    <div class="row justify-content-center text-center">
@auth
    <div class="col-md-4 mb-4">
        <a href="{{ 
    auth()->user()->Rol === 'Maestro' 
        ? route('maestro.consentimiento.alumno', ['matricula' => $alumno->Matricula]) 
        : route('consentimiento.index', ['paciente_id' => request()->query('paciente_id')]) 
}}" class="text-decoration-none text-dark">
            <div class="card shadow h-100">
                <div class="card-body">
                    <i class="fas fa-file-signature fa-2x text-primary mb-3"></i>
                    <h5>Carta SSA Consentimiento Informado</h5>
                    <p class="text-muted">Formato FOCD004.2 de consentimiento SSA.</p>
                </div>
            </div>
        </a>
    </div>
@endauth


@auth
    <div class="col-md-4 mb-4">
 <a href="{{ 
    auth()->user()->Rol === 'Maestro' 
        ? route('maestro.notasevolucion.alumno', ['matricula' => $alumno->Matricula]) 
        : route('notasevolucion.index', ['paciente_id' => request()->query('paciente_id')]) 
}}" class="text-decoration-none text-dark">
            <div class="card shadow h-100">
                <div class="card-body">
                    <i class="fas fa-stethoscope fa-2x text-success mb-3"></i>
                    <h5>FOCD011 Notas De Evolución</h5>
                    <p class="text-muted">
                        Formato FOCD011 para Notas de Evolución.
                    </p>
                </div>
            </div>
        </a>
    </div>
@endauth

        <div class="col-md-4 mb-4">
            <a class="text-decoration-none text-dark">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <i class="fas fa-notes-medical fa-2x text-danger mb-3"></i>
                        <h5>FOCD023 Anexo Cirugia Bucal O Exodoncia</h5>
                        <p class="text-muted">Formato  de anexo quirúrgico.</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- HISTORIA CLÍNICA GENERAL --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-notes-medical fa-2x text-primary mb-3"></i>
                <h5>Historia Clínica General</h5>
                <p class="text-muted">Registro completo de historia clínica.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD027 - ANEXO PRÓTESIS PARCIAL REMOVIBLE --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-teeth-open fa-2x text-primary mb-3"></i>
                <h5>Anexo Prótesis Parcial</h5>
                <p class="text-muted">Formato FOCD027 para prótesis parcial removible.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD028 - ANEXO PRÓTESIS TOTAL --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-tooth fa-2x text-primary mb-3"></i>
                <h5>Anexo Prótesis Total</h5>
                <p class="text-muted">Formato FOCD028 para prótesis total.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD001.1 - SOLICITUD ATENCIÓN ADULTO --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-user-md fa-2x text-primary mb-3"></i>
                <h5>Solicitud Atención Adulto</h5>
                <p class="text-muted">Formato FOCD001.1 para atención en adultos.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD002.1 - ODONTOGRAMA --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-sitemap fa-2x text-primary mb-3"></i>
                <h5>Odontograma</h5>
                <p class="text-muted">Registro gráfico dental del paciente.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD004.1 - CONSENTIMIENTO INFORMADO ADULTO --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-signature fa-2x text-primary mb-3"></i>
                <h5>Consentimiento Adulto</h5>
                <p class="text-muted">Formato FOCD004.1 de consentimiento para adultos.</p>
            </div>
        </div>
    </a>
</div>


{{-- FOCD012 - TRATAMIENTO OPERATORIA --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-stethoscope fa-2x text-primary mb-3"></i>
                <h5>Tratamientos Operatoria</h5>
                <p class="text-muted">Formato FOCD012 - Registro individual de tratamientos.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD014 - REGISTRO DE TRATAMIENTOS --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-clipboard-list fa-2x text-primary mb-3"></i>
                <h5>Registro de Tratamientos</h5>
                <p class="text-muted">Formato FOCD014.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD018 - CONTROL DE BIOPELÍCULA --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-bacterium fa-2x text-primary mb-3"></i>
                <h5>Control de Biopelícula</h5>
                <p class="text-muted">Formato FOCD018 para control de placa bacteriana.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD022 - OCLUSIÓN --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-joint fa-2x text-primary mb-3"></i>
                <h5>Anexo Oclusión</h5>
                <p class="text-muted">Formato FOCD022.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD023 - CIRUGÍA BUCAL --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-user-injured fa-2x text-primary mb-3"></i>
                <h5>Anexo Cirugía Bucal o Exodoncia</h5>
                <p class="text-muted">Formato FOCD023 para exodoncia o cirugía bucal.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD024 - ENDODONCIA --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-syringe fa-2x text-primary mb-3"></i>
                <h5>Anexo Endodoncia</h5>
                <p class="text-muted">Formato FOCD024.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD025 - PRÓTESIS FIJA --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-tools fa-2x text-primary mb-3"></i>
                <h5>Anexo Prótesis Fija</h5>
                <p class="text-muted">Formato FOCD025.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD026 - ODONTOLOGÍA ESTÉTICA --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-smile-beam fa-2x text-primary mb-3"></i>
                <h5>Anexo Estética</h5>
                <p class="text-muted">Formato FOCD026 de odontología estética.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD029 - FORMATO DE BIOPSIAS --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-microscope fa-2x text-primary mb-3"></i>
                <h5>Formato de Biopsias</h5>
                <p class="text-muted">Formato FOCD029.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD031.1 - CARTA DE CONFORMIDAD --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-check-double fa-2x text-primary mb-3"></i>
                <h5>Carta de Conformidad</h5>
                <p class="text-muted">Formato FOCD031.1.</p>
            </div>
        </div>
    </a>
</div>

{{-- FOCD032 - REGISTRO DE EVALUACIÓN PARCIAL --}}
<div class="col-md-4 mb-4">
    <a href="#" class="text-decoration-none text-dark">
        <div class="card shadow h-100">
            <div class="card-body">
                <i class="fas fa-tasks fa-2x text-primary mb-3"></i>
                <h5>Evaluación Parcial</h5>
                <p class="text-muted">Formato FOCD032 - Total de tratamientos.</p>
            </div>
        </div>
    </a>
</div>


    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
