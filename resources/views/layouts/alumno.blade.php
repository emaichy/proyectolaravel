<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Alumnos | IUFIM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
    <style>
        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link:focus {
            font-weight: bold;
            color: #0d6efd !important;
        }

        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .sidebar-toggle {
            border: none;
            background: none;
            font-size: 1.5rem;
        }

        .navbar-nav {
            margin: 0 auto;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .navbar-nav .nav-item {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }

        @media (max-width: 991.98px) {
            .admin-navbar-collapse {
                background: #f8f9fa;
                position: absolute;
                top: 56px;
                left: 0;
                width: 100%;
                z-index: 1000;
                padding: 1rem 0;
            }

            .navbar-nav {
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .navbar-nav .nav-item {
                margin: 0.5rem 0;
                width: 100%;
            }

            .navbar-nav .nav-link,
            .navbar-nav .dropdown-menu {
                text-align: center;
                width: 100%;
            }

            .dropdown-menu {
                left: 50% !important;
                transform: translateX(-50%) !important;
                min-width: 200px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#alumnosNavbar"
                aria-controls="alumnosNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse admin-navbar-collapse" id="alumnosNavbar">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('alumno') ? 'active' : '' }}" href="{{ url('/alumno') }}">
                            <i class="fa-solid fa-house"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('alumno/pacientes*') ? 'active' : '' }}"
                            href="{{ url('/alumno/pacientes/' . $alumnoSesion->Matricula) }}">
                            <i class="fa-solid fa-user-injured"></i> Pacientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('citas*') ? 'active' : '' }}"
                            href="{{ url('/alumno/citas') }}">
                            <i class="fa-solid fa-calendar-check"></i> Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('calificaciones*') ? 'active' : '' }}"
                            href="{{ url('/alumno/calificaciones') }}">
                            <i class="fa-solid fa-graduation-cap"></i> Calificaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('notificaciones*') ? 'active' : '' }}"
                            href="{{ url('/alumno/notificaciones') }}">
                            <i class="fa-solid fa-bell"></i> Notificaciones
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="otrasOpcionesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-h"></i> Más
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="otrasOpcionesDropdown">
                            @php
                                $alumno = auth()->user()->alumno ?? null;
                            @endphp

                            @if ($alumno)
                                <a class="dropdown-item" href="{{ route('alumno.perfil', $alumno->Matricula) }}">
                                    <i class="fa-solid fa-user"></i> Mi Perfil
                                </a>
                            @else
                                <a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">
                                    <i class="fa-solid fa-user"></i> Mi Perfil (no disponible)
                                </a>
                            @endif
                            @if (!auth()->user()->alumno->Firma)
                                <li>
                                    <button type="button" class="dropdown-item" id="btn-agregar-firma">
                                        <i class="fa-solid fa-pen-nib"></i> Agregar Firma
                                    </button>
                                </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        @yield('content')
    </div>

    <footer class="py-3 bg-light mt-4">
        <div class="container">
            <p class="text-center mb-0">&copy; {{ date('Y') }} Panel de Alumnos IUFIM</p>
        </div>
    </footer>
    @if (!auth()->user()->alumno->Firma)
        <div class="modal fade" id="modalFirma" tabindex="-1" aria-labelledby="modalFirmaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="formFirma" autocomplete="off">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalFirmaLabel"><i class="fa-solid fa-pen-nib"></i> Agregar
                                Firma Digital</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <p class="text-center text-secondary mt-3">
                            <i class="fa-solid fa-info-circle"></i> Por favor, firme en el área designada. Después de
                            esto no podrá cambiarla.
                        </p>
                        <div class="modal-body">
                            <div class="mb-3 text-center">
                                <canvas id="firmaCanvas" width="320" height="120"
                                    style="border: 2px solid #6366f1; border-radius: 8px; background: #f8fafc;"></canvas>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-sm" id="btnLimpiarFirma"><i
                                        class="fa-solid fa-eraser"></i> Limpiar</button>
                                <button type="submit" class="btn btn-primary btn-sm"><i
                                        class="fa-solid fa-floppy-disk"></i> Guardar Firma</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    @yield('scripts')
    @if (!auth()->user()->alumno->Firma)
        <script>
            $('#btn-agregar-firma').on('click', function() {
                $('#modalFirma').modal('show');
            });
            const canvas = document.getElementById('firmaCanvas');
            const ctx = canvas.getContext('2d');
            let drawing = false;

            function getPos(e) {
                let rect = canvas.getBoundingClientRect();
                if (e.touches) {
                    return {
                        x: e.touches[0].clientX - rect.left,
                        y: e.touches[0].clientY - rect.top
                    };
                }
                return {
                    x: e.clientX - rect.left,
                    y: e.clientY - rect.top
                };
            }
            canvas.addEventListener('mousedown', e => {
                drawing = true;
                ctx.beginPath();
            });
            canvas.addEventListener('mouseup', e => {
                drawing = false;
            });
            canvas.addEventListener('mouseout', e => {
                drawing = false;
            });
            canvas.addEventListener('mousemove', function(e) {
                if (!drawing) return;
                let pos = getPos(e);
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#22223b';
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            });
            canvas.addEventListener('touchstart', function(e) {
                drawing = true;
                ctx.beginPath();
            });
            canvas.addEventListener('touchend', function(e) {
                drawing = false;
            });
            canvas.addEventListener('touchcancel', function(e) {
                drawing = false;
            });
            canvas.addEventListener('touchmove', function(e) {
                if (!drawing) return;
                let pos = getPos(e);
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#22223b';
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                e.preventDefault();
            });
            $('#btnLimpiarFirma').on('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });
            $('#formFirma').on('submit', function(e) {
                e.preventDefault();
                let dataURL = canvas.toDataURL('image/png');
                $.ajax({
                    url: "{{ route('alumno.guardarFirma', auth()->user()->alumno->Matricula) }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        firma: dataURL
                    },
                    success: function(resp) {
                        $('#modalFirma').modal('hide');
                        Swal.fire('¡Éxito!', 'Firma guardada correctamente.', 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.error || 'No se pudo guardar la firma',
                            'error');
                    }
                });
            });
        </script>
    @endif
</body>

</html>
