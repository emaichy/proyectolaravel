<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Historial Clínico IUFIM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        .image-section {
            flex: 1.3;
            background: url('https://www.iufim.com.mx/wp-content/uploads/2023/12/IMG_6488-scaled.jpg') no-repeat center center;
            background-size: cover;
            animation: slideInLeft 1s ease;
        }

        .form-section {
            flex: .7;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 40px;
            animation: slideInRight 1s ease;
        }

        .form-box {
            width: 100%;
            max-width: 400px;
        }

        .form-box h3 {
            font-weight: bold;
        }

        .logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 150px;
        }

        .btn-primary {
            background-color: #5b85aa;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3c6a90;
        }

        a {
            text-decoration: none;
        }

        .form-check-label {
            font-size: 0.95rem;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 768px) {
    .login-container {
        flex-direction: column;
    }

    .image-section {
        height: 400px;
        position: cover;
    }

    .form-section {
        padding: 30px 20px;
        border-top-left-radius: 40% 10%;
        border-top-right-radius: 40% 10%;
        box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
        margin-top: -10vh;
        z-index: 2;
        position: relative;
    }

    .form-box {
        margin-top: 20px;
    }
}


        .form-floating-modern {
            position: relative;
            margin-bottom: 2rem;
        }

        .form-floating-modern input {
            width: 100%;
            padding: 1.5rem 1rem 0.5rem 3rem;
            border: 0px solid #ccc;
            transition: border-color 0.2s;
            border: 1px solid #ccc;
        }

        .form-floating-modern input:focus {
            outline: none;
            border-color: #5b85aa;
        }

        .form-floating-modern label {
            position: absolute;
            top: 1.1rem;
            left: 3rem;
            color: #999;
            font-size: 1rem;
            pointer-events: none;
            transition: 0.2s ease all;
            padding: 0 0.25rem;
        }

        .form-floating-modern input:focus+label,
        .form-floating-modern input:not(:placeholder-shown)+label {
            top: 0.1rem;
            left: 2.8rem;
            font-size: 0.8rem;
            color: #5b85aa;
        }

        .form-floating-modern .input-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="image-section"></div>
        <div class="form-section">
            <div class="form-box">
                <img src="logo-iufim.png" alt="Logo IUFIM" class="logo">
                <h3 class="text-center mb-3">Inicia sesión en tu cuenta</h3>
                <form id="loginForm" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="form-floating-modern">
                        <span class="input-icon"><i class="fas fa-envelope me-2"></i></span>
                        <input type="email" name="Correo" id="Correo"
                            class="form-control @error('Correo') is-invalid @enderror" placeholder=" "
                            value="{{ old('Correo') }}" required>
                        <label for="Correo"></i>Correo Electrónico</label>
                        @error('Correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating-modern">
                        <span class="input-icon"><i class="fas fa-lock me-2"></i></span>
                        <input type="password" name="Contrasena" id="Contrasena"
                            class="form-control @error('Contrasena') is-invalid @enderror" placeholder=" " required>
                        <label for="Contrasena">Contraseña</label>
                        @error('Contrasena')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>

                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt me-2"></i>Iniciar
                            sesión</button>
                    </div>

                    <div class="form-footer mt-3">
                        <div><a href="#">¿Olvidaste tu contraseña?</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Errores de validación',
                    html: `@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach`
                });
            @endif

            $('#loginForm').on('submit', function() {
                console.log('Formulario enviado');
            });
        });
    </script>
</body>

</html>
