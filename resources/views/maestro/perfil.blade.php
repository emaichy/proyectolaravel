@extends('layouts.maestro')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
        }

        .profile-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 36px rgba(80, 80, 180, 0.10), 0 1.5px 10px rgba(80, 80, 200, 0.08);
            overflow: hidden;
            position: relative;
            margin-bottom: 2rem;
            padding: 2rem 2rem 1.5rem 2rem;
        }

        .profile-avatar-wrap {
            position: relative;
            width: 110px;
            height: 110px;
            margin-bottom: 0;
            margin-right: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .profile-avatar {
            border: 4px solid #6366f1;
            padding: 4px;
            background: #fff;
            transition: border .2s;
            animation: glow 2s infinite alternate;
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 2px 10px #6366f136;
            display: block;
        }

        .avatar-change-btn {
            position: absolute;
            left: 50%;
            bottom: -18px;
            transform: translateX(-50%);
            z-index: 2;
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 50%;
            background: #6366f1;
            color: #fff;
            border: none;
            box-shadow: 0 2px 6px #6366f133;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.95;
            transition: opacity .2s, background .2s;
            font-size: 1.1em;
        }

        .avatar-change-btn:hover {
            background: #4f46e5;
            opacity: 1;
        }

        .avatar-change-btn i {
            margin: 0;
            font-size: 1.18em;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 0 0 #6366f1;
            }

            to {
                box-shadow: 0 0 16px 2px #6366f1;
            }
        }

        .edit-btn {
            position: absolute;
            right: 22px;
            top: 22px;
            z-index: 20;
        }

        .badge-special {
            background: #6366f1;
            color: #fff;
            font-size: 0.9em;
        }

        .profile-info h3 {
            margin-bottom: 0.3rem;
            font-weight: 700;
        }

        .profile-stats {
            margin-top: 1.2rem;
            display: flex;
            gap: 1.8rem;
            flex-wrap: wrap;
        }

        .profile-stat {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 1rem 1.3rem;
            text-align: center;
            flex: 1 1 100px;
            min-width: 100px;
        }

        .profile-stat .fa {
            font-size: 1.7em;
            color: #6366f1;
            margin-bottom: .2em;
        }

        .profile-stat strong {
            font-size: 1.1em;
            display: block;
        }

        .carousel-item {
            padding: .3em 0;
        }

        .carousel-inner {
            min-height: 110px;
        }

        @media (max-width: 768px) {
            .profile-card {
                padding: 1.2rem 0.7rem 1.2rem 0.7rem;
            }

            .edit-btn {
                right: 10px;
                top: 10px;
            }

            .profile-avatar-wrap {
                margin-right: 0.7rem;
            }
        }
    </style>
    <div class="container py-3">
        <div class="profile-card position-relative">
            <div class="d-flex align-items-center flex-wrap">
                <div class="profile-avatar-wrap">
                    <img id="foto-perfil"
                        src="{{ $maestro->Foto_Maestro ? asset($maestro->Foto_Maestro) : asset('avatar.png') }}"
                        alt="Foto de perfil" class="profile-avatar">
                    <input type="file" id="input-foto" accept="image/*" style="display:none;">
                    <button class="avatar-change-btn" type="button" id="btn-cambiar-foto" title="Cambiar foto de perfil">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                </div>
                <div class="profile-info flex-grow-1">
                    <h3>
                        {{ $maestro->Nombre }} {{ $maestro->ApePaterno }} {{ $maestro->ApeMaestro }}
                        <span class="badge badge-special ms-2">
                            <i class="fa-solid fa-certificate"></i> {{ $maestro->Especialidad }}
                        </span>
                    </h3>
                    <div class="text-muted mb-1">
                        <i class="fa-solid fa-envelope"></i> {{ $maestro->usuario->Correo }}
                    </div>
                    <div class="mb-1">
                        <span class="text-secondary"><i class="fa-solid fa-calendar"></i> Miembro desde:
                            {{ $maestro->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="profile-stats mt-4">
                <div class="profile-stat">
                    <i class="fa-solid fa-users"></i>
                    <strong>{{ $maestro->grupos_count ?? '0' }}</strong>
                    <span>Grupos</span>
                </div>
                <div class="profile-stat">
                    <i class="fa-solid fa-chalkboard"></i>
                    <strong>{{ $maestro->clases_count ?? '0' }}</strong>
                    <span>Clases</span>
                </div>
                <div class="profile-stat">
                    <i class="fa-solid fa-tasks"></i>
                    <strong>{{ $maestro->tareas_count ?? '0' }}</strong>
                    <span>Tareas</span>
                </div>
                <div class="profile-stat">
                    <i class="fa-solid fa-user-friends"></i>
                    <strong>{{-- {{ $amigos->count() ?? '0' }} --}}</strong>
                    <span>Colegas</span>
                </div>
            </div>
        </div>
        <div class="profile-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0"><i class="fa-solid fa-bullhorn me-1"></i> Publicaciones recientes</h5>
                <a href="#" class="btn btn-link btn-sm"><i class="fa-solid fa-plus"></i> Nueva publicación</a>
            </div>
            @if (isset($publicaciones) && count($publicaciones))
                <div id="publicacionesCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($publicaciones as $i => $publicacion)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <div>
                                    <h6>{{ $publicacion->titulo }}</h6>
                                    <p class="mb-1">{{ Str::limit($publicacion->contenido, 120) }}</p>
                                    <small class="text-muted">Publicado el
                                        {{ $publicacion->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#publicacionesCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#publicacionesCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @else
                <p class="text-muted mb-0">No hay publicaciones recientes.</p>
            @endif
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        $('#btn-cambiar-foto').on('click', function() {
            $('#input-foto').click();
        });

        $('#input-foto').on('change', function() {
            if (this.files && this.files[0]) {
                var formData = new FormData();
                formData.append('foto', this.files[0]);
                formData.append('_token', '{{ csrf_token() }}');
                $('#btn-cambiar-foto').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
                $.ajax({
                    url: "{{ route('maestro.updateFoto', $maestro->ID_Maestro) }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        if (resp.foto_url) {
                            $('#foto-perfil').attr('src', resp.foto_url + '?t=' + new Date().getTime());
                            Swal.fire('¡Éxito!', 'Foto de perfil actualizada.', 'success');
                        }
                        $('#btn-cambiar-foto').prop('disabled', false).html(
                            '<i class="fa-solid fa-pen"></i>');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.error || 'No se pudo cambiar la foto',
                            'error');
                        $('#btn-cambiar-foto').prop('disabled', false).html(
                            '<i class="fa-solid fa-pen"></i>');
                    }
                });
            }
        });
    </script>
@endsection
