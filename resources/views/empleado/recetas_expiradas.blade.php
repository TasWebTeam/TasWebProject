@extends('layouts.template')

@section('title', 'Recetas expiradas - Empleado')

@section('content')
<div class="container py-5">

    <h1 class="mb-4" style="color:#003865;">
        Recetas expiradas — {{ $nombreCadena ?? 'Cadena' }} - {{ $nombreSucursal ?? 'Sucursal' }}
    </h1>

    <p class="text-muted mb-4">
        Estas recetas excedieron el tiempo límite de recolección (72 horas) 
        <br>
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
                                        <form method="POST"
                                              action="{{ route('empleado_recetas_devolver', ['idReceta' => $receta->getIdReceta()]) }}"
                                              class="d-inline">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('¿Deseas iniciar la DEVOLUCIÓN de esta receta?');">
                                                Iniciar devolución
                                            </button>

                                        </form>
                                    @endif

                                    {{-- BOTÓN: CONFIRMAR NO RECOGIDA --}}
                                    @if ($estado === 'devolviendo')
                                        <form method="POST"
                                              action="{{ route('empleado_recetas_confirmar_no_recogida', ['idReceta' => $receta->getIdReceta()]) }}"
                                              class="d-inline">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    onclick="return confirm('Confirmar que esta receta NO fue recogida?');">
                                                Confirmar no recogida
                                            </button>

                                        </form>
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
@endsection
