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
                                        <span class="badge bg-danger">
                                            Expirada
                                        </span>
                                    @elseif ($estado === 'devolviendo')
                                        <span class="badge bg-warning text-dark">
                                            Devolviendo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $estado }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{-- Iniciar devolución: solo si estaba lista y ya se venció --}}
                                    @if ($estado === 'lista_para_recoleccion')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-devolver-receta"
                                            data-id="{{ $receta->getIdReceta() }}"
                                            data-url="{{ route('empleado_recetas_devolver', ['idReceta' => $receta->getIdReceta()]) }}">
                                            Iniciar devolución
                                        </button>
                                    @endif

                                    {{-- Confirmar no recogida: solo si ya está devolviendo --}}
                                    @if ($estado === 'devolviendo')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary btn-confirmar-no-recogida"
                                            data-id="{{ $receta->getIdReceta() }}"
                                            data-url="{{ route('empleado_recetas_confirmar_no_recogida', ['idReceta' => $receta->getIdReceta()]) }}">
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

{{-- 🔹 JS para devolución y confirmar no recogida --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    if (!csrfToken) {
        console.error('No se encontró <meta name="csrf-token"> en el layout.');
    }

    // 🟥 Iniciar devolución (estado pasa a "devolviendo")
    document.querySelectorAll('.btn-devolver-receta').forEach(btn => {
        btn.addEventListener('click', async (event) => {
            const button   = event.currentTarget;
            const idReceta = button.dataset.id;
            const url      = button.dataset.url;

            if (!confirm('¿Deseas iniciar la devolución de esta receta?')) {
                return;
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const raw = await response.text();
                let data;
                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    console.error('Respuesta NO JSON al devolver receta:', raw);
                    alert('El servidor regresó una respuesta no válida (no es JSON).');
                    return;
                }

                if (response.ok && data.ok) {
                    // ✅ Recargamos la página para refrescar la lista
                    alert(data.message || 'Devolución iniciada correctamente.');
                    window.location.reload();
                } else {
                    alert(data.message || 'No se pudo iniciar la devolución.');
                }

            } catch (error) {
                console.error(error);
                alert('Ocurrió un error al procesar la devolución.');
            }
        });
    });

    // 🟦 Confirmar "no recogida" (estado pasa a "no_recogida" y desaparece de la tabla)
    document.querySelectorAll('.btn-confirmar-no-recogida').forEach(btn => {
        btn.addEventListener('click', async (event) => {
            const button   = event.currentTarget;
            const idReceta = button.dataset.id;
            const url      = button.dataset.url;

            if (!confirm('¿Confirmar que esta receta NO fue recogida?')) {
                return;
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const raw = await response.text();
                let data;
                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    console.error('Respuesta NO JSON al confirmar no recogida:', raw);
                    alert('El servidor regresó una respuesta no válida (no es JSON).');
                    return;
                }

                if (response.ok && data.ok) {
                    // ✅ Quitamos la fila sin recargar toda la página
                    const row = button.closest('tr');
                    if (row) row.remove();

                    alert(data.message || 'Receta marcada como no recogida.');
                } else {
                    alert(data.message || 'No se pudo marcar la receta como no recogida.');
                }

            } catch (error) {
                console.error(error);
                alert('Ocurrió un error al confirmar la receta como no recogida.');
            }
        });
    });
});
</script>
@endsection
