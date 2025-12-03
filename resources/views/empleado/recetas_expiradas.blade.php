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

                            <tr data-receta-id="{{ $receta->getIdReceta() }}">
                                <td>R-{{ str_pad($receta->getIdReceta(), 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $fechaReg?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ $fechaRec?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ $diff !== null ? $diff . ' días' : '-' }}</td>

                                <td data-estado>
                                    @if ($estado === 'lista_para_recoleccion')
                                        <span class="badge bg-danger">Expirada</span>
                                    @elseif ($estado === 'devolviendo')
                                        <span class="badge bg-warning text-dark">Devolviendo</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $estado }}</span>
                                    @endif
                                </td>

                                <td data-acciones>

                                    {{-- Botón Ver detalles --}}
                                    <a href="{{ route('empleado_detalles.receta', ['id' => $receta->getIdReceta()]) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Ver detalles
                                    </a>

                                    {{-- Botón Iniciar devolución --}}
                                    @if ($estado === 'lista_para_recoleccion')
                                        <button class="btn btn-sm btn-outline-danger ms-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDevolucion"
                                            data-id="{{ $receta->getIdReceta() }}">
                                            Iniciar devolución
                                        </button>
                                    @endif

                                    {{-- Botón Confirmar no recogida --}}
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



{{-- MODAL: INICIAR DEVOLUCIÓN --}}
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



{{-- MODAL: CONFIRMAR NO RECOGIDA --}}
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



<script>
document.addEventListener('DOMContentLoaded', () => {

    let idSeleccionado = null;
    const token = '{{ csrf_token() }}';

    const devolverUrlTemplate = "{{ route('empleado_recetas_devolver', ['idReceta' => '__ID__']) }}";
    const noRecogidaUrlTemplate = "{{ route('empleado_recetas_confirmar_no_recogida', ['idReceta' => '__ID__']) }}";

    // =============================
    // MODAL: INICIAR DEVOLUCIÓN
    // =============================
    const modalDevolucion = document.getElementById('modalDevolucion');

    modalDevolucion.addEventListener('show.bs.modal', event => {
        idSeleccionado = event.relatedTarget.getAttribute('data-id');
        console.log('[DEVOLUCIÓN] Abrir modal para receta', idSeleccionado);
    });

    document.getElementById('btn-confirm-devolucion').addEventListener('click', () => {

        const url = devolverUrlTemplate.replace('__ID__', idSeleccionado);
        console.log('[DEVOLUCIÓN] Enviando POST a:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(async r => {
            const status = r.status;
            let data = null;

            try {
                data = await r.json();
            } catch (e) {
                console.error('[DEVOLUCIÓN] Error parseando JSON:', e);
            }

            console.log('[DEVOLUCIÓN] Respuesta HTTP', status, 'JSON:', data);

            if (!data || !data.ok) {
                console.warn('[DEVOLUCIÓN] No se pudo iniciar la devolución. Mensaje:', data ? data.message : 'sin datos');
                return;
            }

            // Si todo OK:
            const modal = bootstrap.Modal.getInstance(modalDevolucion);
            modal.hide();

            const row = document.querySelector(`tr[data-receta-id="${idSeleccionado}"]`);
            if (row) {
                const tdEstado = row.querySelector('td[data-estado]');
                const tdAcciones = row.querySelector('td[data-acciones]');

                if (tdEstado) {
                    tdEstado.innerHTML =
                        `<span class="badge bg-warning text-dark">Devolviendo</span>`;
                }

                if (tdAcciones) {
                    tdAcciones.innerHTML = `
                        <button class="btn btn-sm btn-outline-secondary ms-1"
                            data-bs-toggle="modal"
                            data-bs-target="#modalNoRecogida"
                            data-id="${idSeleccionado}">
                            Confirmar no recogida
                        </button>
                    `;
                }
            }

        })
        .catch(err => {
            console.error('[DEVOLUCIÓN] Error de red o fetch:', err);
        });
    });




    // =============================
    // MODAL: CONFIRMAR NO RECOGIDA
    // =============================
    const modalNoRecogida = document.getElementById('modalNoRecogida');

    modalNoRecogida.addEventListener('show.bs.modal', event => {
        idSeleccionado = event.relatedTarget.getAttribute('data-id');
        console.log('[NO RECOGIDA] Abrir modal para receta', idSeleccionado);
    });

    document.getElementById('btn-confirm-no-recogida').addEventListener('click', () => {

        const url = noRecogidaUrlTemplate.replace('__ID__', idSeleccionado);
        console.log('[NO RECOGIDA] Enviando POST a:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(async r => {
            const status = r.status;
            let data = null;

            try {
                data = await r.json();
            } catch (e) {
                console.error('[NO RECOGIDA] Error parseando JSON:', e);
            }

            console.log('[NO RECOGIDA] Respuesta HTTP', status, 'JSON:', data);

            if (!data || !data.ok) {
                console.warn('[NO RECOGIDA] No se pudo marcar como no recogida. Mensaje:', data ? data.message : 'sin datos');
                return;
            }

            const modal = bootstrap.Modal.getInstance(modalNoRecogida);
            modal.hide();

            const row = document.querySelector(`tr[data-receta-id="${idSeleccionado}"]`);
            if (row) row.remove();

        })
        .catch(err => {
            console.error('[NO RECOGIDA] Error de red o fetch:', err);
        });
    });

});
</script>

@endsection
