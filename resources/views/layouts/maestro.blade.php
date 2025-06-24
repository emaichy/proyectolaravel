<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Maestros | IUFIM')</title>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#maestrosNavbar"
                aria-controls="maestrosNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse admin-navbar-collapse" id="maestrosNavbar">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('maestro') ? 'active' : '' }}"
                            href="{{ url('/maestro') }}">
                            <i class="fa-solid fa-house"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('maestros/alumnos*') ? 'active' : '' }}"
                            href="{{ url('/maestros/alumnos') }}">
                            <i class="fa-solid fa-user-graduate"></i> Alumnos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('grupos*') ? 'active' : '' }}"
                            href="{{ url('grupos/' . $maestro->ID_Maestro) }}">
                            <i class="fa-solid fa-layer-group"></i> Grupos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is($maestro->ID_Maestro . '/clases*') ? 'active' : '' }}"
                            href="{{ url('/' . $maestro->ID_Maestro . '/clases') }}">
                            <i class="fa-solid fa-chalkboard"></i> Clases
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('maestros/tareas*') ? 'active' : '' }}"
                            href="{{ url('/maestros/tareas') }}">
                            <i class="fa-solid fa-tasks"></i> Tareas
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="otrasOpcionesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-h"></i> Más
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="otrasOpcionesDropdown">
                            <li><a class="dropdown-item"
                                    href="{{ url('/perfil/' . auth()->user()->maestro->ID_Maestro) }}"><i
                                        class="fa-solid fa-user"></i> Mi Perfil</a></li>
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
            <p class="text-center mb-0">&copy; {{ date('Y') }} Panel de Maestros IUFIM</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    @yield('scripts')
</body>

</html>
