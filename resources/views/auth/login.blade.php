<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Historial Clínico IUFIM</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3><i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión</h3>
                            <p class="text-muted">Ingresa tus credenciales para acceder al sistema</p>
                        </div>

                        <form id="loginForm" action="{{ route('login.post') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="Correo" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" name="Correo" id="Correo" 
                                       class="form-control @error('Correo') is-invalid @enderror" 
                                       placeholder="Ingrese su correo" 
                                       value="{{ old('Correo') }}" 
                                       required>
                                @error('Correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="Contrasena" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <input type="password" name="Contrasena" id="Contrasena" 
                                       class="form-control @error('Contrasena') is-invalid @enderror" 
                                       placeholder="Ingrese su contraseña" 
                                       required>
                                @error('Contrasena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordar sesión</label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery primero, luego Popper.js, luego Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS (cargado al final del body) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            console.log('jQuery está funcionando correctamente');
            
            // Mostrar errores con SweetAlert2 si existen
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#3085d6'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Errores de validación',
                    html: `@foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach`,
                    confirmButtonColor: '#3085d6'
                });
            @endif

            // Depuración del formulario
            $('#loginForm').on('submit', function(e) {
                console.log('Formulario enviado');
                console.log('Datos:', $(this).serialize());
            });
        });
    </script>
</body>
</html>