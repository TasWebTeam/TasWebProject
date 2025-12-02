@extends('layouts.template')

@section('title', 'Recetas - Empleado')

@section('content')
<div class="container py-5">

    {{-- Título dinámico --}}
    <h1 class="mb-4" style="color:#003865;">
        Recetas pendientes por surtir — {{ $nombreCadena ?? 'Cadena' }} - {{ $nombreSucursal ?? 'Sucursal' }}
    </h1>

    <p class="text-muted mb-4">
        Aquí puedes ver las recetas enviadas por los pacientes que están pendientes de surtir.
    </p>

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('empleado_recetas') }}" class="row g-3">

                {{-- Filtro por estado --}}
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="en_proceso" {{ ($estado ?? '') === 'en_proceso' ? 'selected' : '' }}>
                            En proceso
                        </option>
                        <option value="lista_para_recoleccion" {{ ($estado ?? '') === 'lista_para_recoleccion' ? 'selected' : '' }}>
                            Lista para recoger
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" type="submit">Filtrar</button>
                </div>

            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Listado de recetas</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha registro</th>
                            <th>Fecha recolección estimada</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recetas as $receta)
                            <tr>
                                <td>R-{{ str_pad($receta->getIdReceta(), 4, '0', STR_PAD_LEFT) }}</td>

                                <td>{{ $receta->getFechaRegistro()?->format('Y-m-d H:i') }}</td>

                                <td>{{ $receta->getFechaRecoleccion()?->format('Y-m-d H:i') }}</td>

                                <td>
                                    @if ($receta->getEstadoPedido() === 'en_proceso')
                                        <span class="badge bg-info text-dark">En proceso</span>

                                    @elseif ($receta->getEstadoPedido() === 'lista_para_recoleccion')
                                        <span class="badge bg-success">Lista</span>

                                    @elseif ($receta->getEstadoPedido() === 'entregada')
                                        <span class="badge bg-secondary">Entregada</span>

                                    @else
                                        <span class="badge bg-light text-dark">
                                            {{ $receta->getEstadoPedido() }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('empleado_recetas.mapa', ['id' => $receta->getIdReceta()]) }}"
                                        class="btn btn-sm btn-outline-primary">
                                            Ver ruta
                                    </a>
                                    @if ($receta->getEstadoPedido() === 'en_proceso')
                                        <button class="btn btn-sm btn-success ms-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalLista"
                                            data-id="{{ $receta->getIdReceta() }}">
                                            Marcar como Lista
                                        </button>
                                    @endif

                                    @if ($receta->getEstadoPedido() === 'lista_para_recoleccion')
                                        <button class="btn btn-sm btn-warning ms-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEntregada"
                                            data-id="{{ $receta->getIdReceta() }}">
                                            Marcar como Entregada
                                        </button>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No hay recetas pendientes por surtir.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>


{{-- ========================= --}}
{{--     MODAL: MARCAR LISTA   --}}
{{-- ========================= --}}
<div class="modal fade" id="modalLista" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirmar acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Quieres marcar esta receta como <strong>LISTA PARA RECOLECCIÓN</strong>?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" id="btn-confirm-lista">Sí, marcar</button>
            </div>
        </div>
    </div>
</div>


{{-- ========================= --}}
{{--   MODAL: MARCAR ENTREGADA --}}
{{-- ========================= --}}
<div class="modal fade" id="modalEntregada" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Confirmar entrega</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Deseas marcar esta receta como <strong>ENTREGADA</strong> al paciente?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-warning" id="btn-confirm-entregada">Confirmar entrega</button>
            </div>
        </div>
    </div>
</div>



{{-- ========================= --}}
{{--      JS MODALES AJAX      --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    let idSeleccionado = null;
    const token = '{{ csrf_token() }}';
    const baseUrl = "{{ url('/empleado/recetas') }}";

    // Modal LISTA
    const modalLista = document.getElementById('modalLista');
    modalLista.addEventListener('show.bs.modal', function (event) {
        idSeleccionado = event.relatedTarget.getAttribute('data-id');
    });

    document.getElementById('btn-confirm-lista').addEventListener('click', function () {
        fetch(`${baseUrl}/${idSeleccionado}/marcar-lista`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: '{}'
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) location.reload();
        });
    });

    // Modal ENTREGADA
    const modalEntregada = document.getElementById('modalEntregada');
    modalEntregada.addEventListener('show.bs.modal', function (event) {
        idSeleccionado = event.relatedTarget.getAttribute('data-id');
    });

    document.getElementById('btn-confirm-entregada').addEventListener('click', function () {
        fetch(`${baseUrl}/${idSeleccionado}/marcar-entregada`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: '{}'
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) location.reload();
        });
    });

});
</script>

@endsection
