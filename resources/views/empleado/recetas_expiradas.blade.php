@extends('layouts.template')

@section('title', 'Recetas expiradas - Empleado')

@section('content')
<div class="container py-5">

    <h1 class="mb-4" style="color:#003865;">Recetas expiradas</h1>

    <p class="text-muted mb-4">
        Estas recetas ya superaron el tiempo permitido (72 horas) para ser recogidas.
    </p>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body">

            <h5 class="card-title mb-3">Listado de recetas expiradas</h5>

            <div class="table-responsive">
                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Sucursal destino</th>
                            <th>Fecha registro</th>
                            <th>Fecha recolección</th>
                            <th>Días de atraso</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        @if (empty($recetas))
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay recetas expiradas.
                                </td>
                            </tr>
                        @else
                            @foreach ($recetas as $receta)

                                @php
                                    $s = $receta->getSucursal();
                                    $diasAtraso = 0;

                                    if ($receta->getFechaRecoleccion()) {
                                        $diasAtraso = $receta->getFechaRecoleccion()->diff(now())->days;
                                    }
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

                                    <td>{{ $diasAtraso }} días</td>

                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $receta->getEstadoPedido() }}
                                        </span>
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
