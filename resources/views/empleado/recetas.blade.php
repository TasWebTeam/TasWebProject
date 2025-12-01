@extends('layouts.template') 

@section('title', 'Recetas - Empleado')

@section('content')
<div class="container py-5">

    {{-- Título dinámico --}}
    <h1 class="mb-4" style="color:#003865;">
        Recetas pendientes por surtir — {{ $nombreSucursal ?? 'Sucursal' }}
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
                        <option value="en_proceso" 
                            {{ ($estado ?? '') === 'en_proceso' ? 'selected' : '' }}>
                            En proceso
                        </option>
                        <option value="lista_para_recoleccion" 
                            {{ ($estado ?? '') === 'lista_para_recoleccion' ? 'selected' : '' }}>
                            Lista para recoger
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" type="submit">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de recetas --}}
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Listado de recetas</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha registro</th>
                            <th>Fecha recolección</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recetas as $receta)
                            <tr data-receta-id="{{ $receta->getIdReceta() }}">
                                <td>R-{{ str_pad($receta->getIdReceta(), 4, '0', STR_PAD_LEFT) }}</td>

                                <td>{{ $receta->getFechaRegistro()?->format('Y-m-d H:i') }}</td>

                                <td>{{ $receta->getFechaRecoleccion()?->format('Y-m-d H:i') }}</td>

                                <td class="col-estado">
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

                                <td class="col-acciones">
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        Ver detalles
                                    </a>

                                    @if ($receta->getEstadoPedido() === 'en_proceso')
                                        <button type="button"
                                                class="btn btn-sm btn-success ms-1 btn-marcar-lista"
                                                data-id="{{ $receta->getIdReceta() }}">
                                            Marcar como Lista
                                        </button>
                                    @endif

                                    @if ($receta->getEstadoPedido() === 'lista_para_recoleccion')
                                        <button type="button"
                                                class="btn btn-sm btn-warning ms-1 btn-marcar-entregada"
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

{{-- JS para actualizar estados vía AJAX --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token   = '{{ csrf_token() }}';
    const baseUrl = "{{ url('/empleado/recetas') }}";

    function llamarAccion(idReceta, accion) {
        const url = `${baseUrl}/${idReceta}/${accion}`;

        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        }).then(resp => resp.json());
    }

    // Botones "Marcar como Lista"
    document.querySelectorAll('.btn-marcar-lista').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            if (!confirm('¿Marcar esta receta como LISTA PARA RECOLECCIÓN?')) return;

            llamarAccion(id, 'marcar-lista')
                .then(data => {
                    if (data.ok) {
                        // Recargamos solo la página para simplificar
                        window.location.reload();
                    } else {
                        alert(data.message || 'No se pudo actualizar la receta.');
                    }
                })
                .catch(() => alert('Error de comunicación con el servidor.'));
        });
    });

    // Botones "Marcar como Entregada"
    document.querySelectorAll('.btn-marcar-entregada').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            if (!confirm('¿Marcar esta receta como ENTREGADA?')) return;

            llamarAccion(id, 'marcar-entregada')
                .then(data => {
                    if (data.ok) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'No se pudo actualizar la receta.');
                    }
                })
                .catch(() => alert('Error de comunicación con el servidor.'));
        });
    });
});
</script>
@endsection
