@extends('layouts.app')

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
            <input type="password" class="form-control" id="password" name="Contrasena" required>
        </div>

        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
</div>
@endsection