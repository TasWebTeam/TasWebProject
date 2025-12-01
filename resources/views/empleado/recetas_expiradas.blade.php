@extends('layouts.template')

@section('title', 'Recetas expiradas - Empleado')

@section('content')
<div class="container py-5">

    <h1 class="mb-4" style="color:#003865;">
        Recetas expiradas — {{ $nombreSucursal ?? 'Sucursal' }}
    </h1>

    <p class="text-muted mb-4">
        Estas recetas excedieron el tiempo límite de recolección (72 horas) y fueron marcadas como expiradas,
        o están en proceso de devolución.
    </p>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Listado de recetas expiradas / en devolución</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha registro</th>
                            <th>Fecha recolección</th>
                            <th>Días de atraso</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recetas as $receta)
                            @php
                                $fechaReg = $receta->getFechaRegistro();
                                $fechaRec = $receta->getFechaRecoleccion();
                                $now      = new \DateTime();
                                $diff     = $fechaRec ? $fechaRec->diff($now)->days : null;
                                $estado   = $receta->getEstadoPedido();
                            @endphp

                            <tr>
                                <td>R-{{ str_pad($receta->getIdReceta(), 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $fechaReg?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ $fechaRec?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ $diff !== null ? $diff . ' días' : '-' }}</td>

                                <td>
                                    @if ($estado === 'lista_para_recoleccion')
                                        <span class="badge bg-danger">Expirada</span>

                                    @elseif ($estado === 'devolviendo')
                                        <span class="badge bg-warning text-dark">Devolviendo</span>

                                    @else
                                        <span class="badge bg-secondary">{{ $estado }}</span>
                                    @endif
                                </td>

                                <td>

                                    {{-- BOTÓN: INICIAR DEVOLUCIÓN --}}
                                    @if ($estado === 'lista_para_recoleccion')
                                        <button class="btn btn-sm btn-outline-danger ms-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDevolucion"
                                            data-id="{{ $receta->getIdReceta() }}">
                                            Iniciar devolución
                                        </button>
                                    @endif

                                    {{-- BOTÓN: CONFIRMAR NO RECOGIDA --}}
                                    @if ($estado === 'devolviendo')
                                        <button class="btn btn-sm btn-outline-secondary ms-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalNoRecogida"
                                            data-id="{{ $receta->getIdReceta() }}">
                                            Confirmar no recogida
                                        </button>
                                    @endif

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay recetas expiradas o en devolución para esta sucursal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>



{{-- ================================ --}}
{{--      MODAL: INICIAR DEVOLUCIÓN   --}}
{{-- ================================ --}}
<div class="modal fade" id="modalDevolucion" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Iniciar devolución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Deseas iniciar la <strong>DEVOLUCIÓN</strong> de esta receta?
                <br>
                <small class="text-muted">
                    El estado cambiará a <strong>“devolviendo”</strong>.
                </small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="btn-confirm-devolucion">Iniciar devolución</button>
            </div>

        </div>
    </div>
</div>



{{-- ===================================== --}}
{{--    MODAL: CONFIRMAR NO RECOGIDA        --}}
{{-- ===================================== --}}
<div class="modal fade" id="modalNoRecogida" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Confirmar no recogida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Confirmas que esta receta <strong>NO fue recogida</strong> por el paciente?
                <br>
                <small class="text-muted">
                    Se registrará como “no recogida” y desaparecerá de esta lista.
                </small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-secondary" id="btn-confirm-no-recogida">Confirmar</button>
            </div>

        </div>
    </div>
</div>



{{-- ============================= --}}
{{--          JS AJAX MODALES      --}}
{{-- ============================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    let idSeleccionado = null;
    const token = '{{ csrf_token() }}';

    // =============================
    // MODAL DEVOLUCIÓN
    // =============================
    const modalDevolucion = document.getElementById('modalDevolucion');

    modalDevolucion.addEventListener('show.bs.modal', event => {
        idSeleccionado = event.relatedTarget.getAttribute('data-id');
    });

    document.getElementById('btn-confirm-devolucion').addEventListener('click', () => {

        fetch(`/empleado/recetas/${idSeleccionado}/devolver`, {
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
            else alert(data.message || 'No se pudo iniciar la devolución.');
        });
    });



    // =============================
    // MODAL NO RECOGIDA
    // =============================
    const modalNoRecogida = document.getElementById('modalNoRecogida');

    modalNoRecogida.addEventListener('show.bs.modal', event => {
        idSeleccionado = event.relatedTarget.getAttribute('data-id');
    });

    document.getElementById('btn-confirm-no-recogida').addEventListener('click', () => {

        fetch(`/empleado/recetas/${idSeleccionado}/no-recogida`, {
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
            else alert(data.message || 'No se pudo marcar como no recogida.');
        });
    });

});
</script>

@endsection
