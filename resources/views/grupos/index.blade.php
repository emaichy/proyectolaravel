@extends('layouts.admin')

@section('content')
    <style>
        .grupo-card {
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s, background 0.2s;
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.07);
            border: 2px solid transparent;
            border-radius: 20px;
            background: linear-gradient(135deg, #f8fafc 60%, #e0e7ef 100%);
            min-height: 240px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding: 14px 10px 16px 10px;
            margin-bottom: 18px;
        }

        .grupo-card:hover {
            border-color: #2563eb;
            background: linear-gradient(135deg, #e0e7ef 60%, #dae7ff 100%);
            box-shadow: 0 8px 28px rgba(40, 124, 255, 0.14);
        }

        .grupo-card-img {
            width: 88%;
            height: 100px;
            object-fit: cover;
            background: linear-gradient(135deg, #c4c9d0 60%, #e5e9f2 100%);
            border-radius: 15px;
            margin: 8px auto 8px auto;
            box-shadow: 0 0 6px #e5e9f2;
        }

        .grupo-card-title {
            text-align: center;
            font-weight: 600;
            font-size: 1.08rem;
            color: #2d3748;
            margin: 6px 0 8px 0;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px #f5f8ff;
        }

        .grupo-card-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 6px;
            opacity: 1;
            pointer-events: all;
            transition: none;
        }

        .grupo-card-actions .btn {
            border-radius: 50px;
            min-width: 40px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 2px 6px rgba(40, 124, 255, 0.07);
            transition: background 0.15s, color 0.15s, box-shadow 0.15s;
            padding: 8px 13px;
        }

        .grupo-card-actions .btn-info {
            background: linear-gradient(90deg, #60a5fa 60%, #2563eb 100%);
            color: #fff;
            border: none;
        }

        .grupo-card-actions .btn-danger {
            background: linear-gradient(90deg, #f87171 60%, #e11d48 100%);
            color: #fff;
            border: none;
        }

        .grupo-card-actions .btn-info:hover,
        .grupo-card-actions .btn-danger:hover {
            box-shadow: 0 4px 16px rgba(40, 124, 255, 0.16);
            opacity: 0.92;
            color: #fff;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px 18px;
        }

        .cards-grid.few-cards {
            justify-content: center;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        @media (max-width: 991.98px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 575.98px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Grupos</h2>
            <a href="{{ route('grupos.create') }}" class="btn btn-primary">Agregar Grupo</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success rounded-pill text-center fw-bold">{{ session('success') }}</div>
        @endif

        @if ($grupos->isEmpty())
            <div class="text-center my-5">
                <p class="fs-4 text-muted">No hay grupos registrados.</p>
            </div>
        @else
            @php
                $fewGroups = $grupos->count() < 4;
            @endphp
            <div class="cards-grid{{ $fewGroups ? ' few-cards' : '' }}">
                @foreach ($grupos->take(12) as $grupo)
                    <div class="grupo-card w-100" data-group-id="{{ $grupo->ID_Grupo }}">
                        <img src="{{ asset('aula.png') }}" alt="Grupo" class="grupo-card-img">
                        <div class="grupo-card-title">
                            {{ $grupo->NombreGrupo }}
                        </div>
                        <div class="grupo-card-actions">
                            <a href="{{ route('grupos.show', $grupo->ID_Grupo) }}" class="btn btn-info" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form class="delete-group-form" data-id="{{ $grupo->ID_Grupo }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $grupos->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script>
        $(document).on('submit', '.delete-group-form', function(e) {
            e.preventDefault();
            var form = $(this);
            var groupId = form.data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#e11d48',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('grupos/delete') }}/" + groupId,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            form.closest('.grupo-card').fadeOut(300, function() {
                                $(this).remove();
                            });
                            Swal.fire(
                                '¡Eliminado!',
                                'El grupo ha sido eliminado.',
                                'success'
                            );
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error',
                                'No se pudo eliminar el grupo.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
