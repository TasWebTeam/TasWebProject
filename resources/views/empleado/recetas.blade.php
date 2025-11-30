@extends('layouts.template')

@section('title', 'Recetas - Empleado')

@section('content')
<div class="container py-5">

    <h1 class="mb-4" style="color:#003865;">Recetas pendientes por surtir</h1>

    <p class="text-muted mb-4">
        Estas son las recetas pendientes pertenecientes a tu sucursal.
    </p>

    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select" disabled>
                        <option value="">Todos</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="EN_PROCESO">En proceso</option>
                        <option value="LISTA_PARA_RECOLECCION">Lista para recolección</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha registro (desde)</label>
                    <input type="date" class="form-control" disabled>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha registro (hasta)</label>
                    <input type="date" class="form-control" disabled>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" disabled>
                        Filtrar
                    </button>
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
                            <th>Sucursal destino</th>
                            <th>Fecha registro</th>
                            <th>Fecha recolección</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (empty($recetas))
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay recetas registradas para esta sucursal.
                                </td>
                            </tr>
                        @else
                            @foreach ($recetas as $receta)
                                @php
                                    $s = $receta->getSucursal();
                                @endphp

                                <tr>
                                    <td>{{ $receta->getIdReceta() }}</td>

                                    <td>
                                        @if ($s)
                                            {{ $s->getCadena()->getNombre() ?? '' }}
                                            -
                                            {{ $s->getNombre() }}
                                        @else
                                            Sin sucursal
                                        @endif
                                    </td>

                                    <td>{{ $receta->getFechaRegistro()?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td>{{ $receta->getFechaRecoleccion()?->format('Y-m-d H:i') ?? '-' }}</td>

                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $receta->getEstadoPedido() }}
                                        </span>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Ver detalles</button>
                                        <button class="btn btn-sm btn-success ms-1">Actualizar estado</button>
                                    </td>
                                </tr>

                            @endforeach
                        @endif
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection
