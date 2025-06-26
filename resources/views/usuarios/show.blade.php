@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Detalle del Usuario</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $usuario->name }}</h5>
                <p class="card-text"><strong>Correo:</strong> {{ $usuario->Correo }}</p>
                <p class="card-text"><strong>Contraseña</strong>{{ $usuario->password }}</p>
                <p class="card-text"><strong>Estado:</strong> {{ $usuario->Status ? '1' : '0' }}</p>
                <p class="card-text"><strong>Rol:</strong> {{ $usuario->Rol ?? 'No asignado' }}</p>
            </div>
        </div>
        <div class="mt-4 d-flex justify-content-center align-items-center">
            <a href="{{ route('usuarios.edit', $usuario->ID_Usuario) }}" class="btn btn-warning">Editar</a>
            <div class="mx-2"></div>
            <a href="{{ route('volver') }}" class="btn btn-secondary">Regresar</a>
        </div>
    </div>
@endsection
