@extends('layouts.alumno')

@section('title', 'Mis Pacientes')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-center fw-bold text-primary">
            <i class="fa-solid fa-user-injured me-2"></i>Pacientes Asignados
        </h2>

        <div class="row justify-content-center">
            @forelse ($pacientes as $paciente)
                <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                    <div class="card shadow-lg border-0 w-100 position-relative patient-card">
                        <div class="text-center pt-4">
                            <div class="patient-avatar mx-auto mb-2">
                                <i class="fa-solid fa-user-circle fa-4x text-secondary"></i>
                            </div>
                        </div>
                        <div class="card-body text-center pb-0">
                            <h5 class="card-title mb-1 text-dark fw-bolder">
                                {{ $paciente->Nombre }} {{ $paciente->ApePaterno }} {{ $paciente->ApeMaterno }}
                            </h5>
                            <span class="badge rounded-pill bg-success mb-2">{{ $paciente->TipoPaciente }}</span>
                            <ul class="list-group list-group-flush mb-3">
                                @if (!empty($paciente->Telefono))
                                    <li class="list-group-item">
                                        <i class="fa-solid fa-phone me-2"></i>
                                        <span class="text-muted">{{ $paciente->Telefono }}</span>
                                    </li>
                                @endif
                                @if (!empty($paciente->Correo))
                                    <li class="list-group-item">
                                        <i class="fa-solid fa-envelope me-2"></i>
                                        <span class="text-muted">{{ $paciente->Correo }}</span>
                                    </li>
                                @endif
                            </ul>
                            <a href="{{ route('alumno.documentoss.index', ['paciente_id' => $paciente->ID_Paciente]) }}"
                                class="btn btn-outline-primary w-100 fw-bold mb-3">
                                <i class="fa-solid fa-folder-open me-1"></i>
                                Ver/Crear Documentos de Expediente
                            </a>
                        </div>
                        <div class="card-footer text-center bg-white border-0 pb-3">
                            <small class="text-secondary">
                                ID Paciente: <span class="fw-semibold">{{ $paciente->ID_Paciente }}</span>
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center p-4 shadow-sm">
                        <i class="fa-solid fa-circle-info fa-2x mb-2"></i><br>
                        No tienes pacientes asignados actualmente.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @push('styles')
        <style>
            .patient-card {
                transition: transform 0.12s, box-shadow 0.12s;
            }

            .patient-card:hover {
                transform: translateY(-6px) scale(1.025);
                box-shadow: 0 0.5rem 1.5rem rgba(52, 58, 64, 0.15);
            }

            .patient-avatar {
                width: 90px;
                height: 90px;
                background: linear-gradient(135deg, #e0e7ff 0%, #f0f5ff 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 0.25rem 0.75rem rgba(60, 60, 80, 0.08);
            }
        </style>
    @endpush
@endsection
