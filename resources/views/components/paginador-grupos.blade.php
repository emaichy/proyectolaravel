@props(['gruposVisibles', 'grupoActual', 'paginaActual', 'totalPaginas'])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 mb-3 justify-content-center">
            @if ($paginaActual > 1)
                <a href="{{ route('grupos.show', ['grupo' => $grupoActual->ID_Grupo, 'pagina_grupo' => $paginaActual - 1]) }}"
                    class="btn btn-outline-secondary">&laquo; Anterior</a>
            @endif

            @foreach ($gruposVisibles as $g)
                <a href="{{ route('grupos.show', ['grupo' => $g->ID_Grupo, 'pagina_grupo' => $paginaActual]) }}"
                    class="btn {{ $grupoActual->ID_Grupo == $g->ID_Grupo ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $g->NombreGrupo }}
                </a>
            @endforeach

            @if ($paginaActual < $totalPaginas)
                <a href="{{ route('grupos.show', ['grupo' => $grupoActual->ID_Grupo, 'pagina_grupo' => $paginaActual + 1]) }}"
                    class="btn btn-outline-secondary">Siguiente &raquo;</a>
            @endif
        </div>
    </div>
</div>
