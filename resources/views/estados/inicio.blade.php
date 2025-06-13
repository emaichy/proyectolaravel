@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Estados</h1>
    <a href="{{ route('estados.create') }}" class="btn btn-primary mb-3">Agregar Estado</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($estados->isEmpty())
        <div class="alert alert-info">No hay estados registrados.</div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estados as $estado)
            <tr>
                <td>{{ $estado->ID_Estado }}</td>
                <td>{{ $estado->NombreEstado }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $estados->links('pagination::bootstrap-4') }}
    @endif
</div>
@endsection