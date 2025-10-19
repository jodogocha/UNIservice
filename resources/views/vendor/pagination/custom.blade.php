@if ($paginator->hasPages())
    <nav aria-label="Navegación de páginas">
        <ul class="pagination justify-content-center mb-0">
            {{-- Botón Anterior --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                </li>
            @endif

            {{-- Elementos de paginación --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Botón Siguiente --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    {{-- Información de registros --}}
    <div class="text-center mt-3">
        <p class="text-muted small mb-0">
            <i class="fas fa-info-circle"></i>
            Mostrando 
            <strong>{{ $paginator->firstItem() }}</strong>
            a
            <strong>{{ $paginator->lastItem() }}</strong>
            de
            <strong>{{ $paginator->total() }}</strong>
            resultados
            @if($paginator->lastPage() > 1)
                (Página <strong>{{ $paginator->currentPage() }}</strong> de <strong>{{ $paginator->lastPage() }}</strong>)
            @endif
        </p>
    </div>
@endif