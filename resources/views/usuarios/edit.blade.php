@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Editar Usuario</h2>
        <form action="{{ route('usuarios.update', $usuario->ID_Usuario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="Correo" class="form-control" id="email"
                        value="{{ old('Correo', $usuario->Correo) }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" id="password"
                        value="{{ old('password', $usuario->password) }}" required>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select name="Rol" id="rol" class="form-control" required>
                        <option value="Alumno" {{ old('Rol', $usuario->Rol) == 'Alumno' ? 'selected' : '' }}>Alumno</option>
                        <option value="Maestro" {{ old('Rol', $usuario->Rol) == 'Maestro' ? 'selected' : '' }}>Maestro
                        </option>
                        <option value="Administrativo"
                            {{ old('Rol', $usuario->Rol) == 'Administrativo' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>

                <div class="mt-4 d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <div class="mx-2"></div>
                    <a href="{{ route('volver') }}" class="btn btn-secondary">Cancelar</a>
                </div>
        </form>
    </div>
@endsection
