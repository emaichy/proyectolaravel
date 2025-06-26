@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Crear Usuario</h2>
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="Correo" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mt-4 d-flex justify-content-center align-items-center">
                <button type="submit" class="btn btn-primary">Crear</button>
                <div class="mx-2"></div>
                <a href="{{ route('volver') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
