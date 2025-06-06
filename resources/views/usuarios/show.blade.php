@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle del Usuario</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $usuario->name }}</h5>
            <p class="card-text"><strong>Correo:</strong> {{ $usuario->Correo }}</p>
            <p class="card-text"><strong>Contraseña</strong>{{$usuario->password }}</p>
            <p class="card-text"><strong>Estado:</strong> {{ $usuario->Status ? '1' : '0' }}</p>
            <p class="card-text"><strong>Rol:</strong> {{ $usuario->Rol ?? 'No asignado' }}</p>
        </div>
    </div>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">Volver</a>
    <a href="{{ route('usuarios.edit', $usuario->ID_Usuario) }}" class="btn btn-primary mt-3">Editar</a>
</div>
@endsection