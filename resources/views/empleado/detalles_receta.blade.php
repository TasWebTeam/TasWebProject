@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h3 class="mb-3">Ruta de surtido y detalles de receta</h3>

    <div class="row">
        {{-- Columna izquierda: info + detalles --}}
        <div class="col-md-5 mb-4">

            {{-- Card info de receta --}}
            <div class="card mb-3">
                <div class="card-header">
                    Información de la receta
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Folio:</strong> R-{{ str_pad($receta->getIdReceta(), 4, '0', STR_PAD_LEFT) }}</p>
                    <p class="mb-1"><strong>Cédula profesional:</strong> {{ $receta->getCedulaProfesional() }}</p>
                    <p class="mb-1">
                        <strong>Fecha registro:</strong>
                        {{ $receta->getFechaRegistro()?->format('Y-m-d H:i') }}
                    </p>
                    <p class="mb-1">
                        <strong>Fecha recolección estimada:</strong>
                        {{ $receta->getFechaRecoleccion()?->format('Y-m-d H:i') }}
                    </p>
                    <p class="mb-1">
                        <strong>Estado:</strong> {{ $receta->getEstadoPedido() }}
                    </p>
                </div>
            </div>

            {{-- Tabla detalles + sucursales que surten --}}
            <div class="card">
                <div class="card-header">
                    Detalles de la receta
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Medicamento</th>
                                    <th class="text-center">Cant. total</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detallesResumen as $det)
                                    <tr>
                                        <td>
                                            <strong>{{ $det['medicamento'] }}</strong>
                                            <br>
                                            <small class="text-muted">Distribución por sucursal:</small>
                                            <ul class="mb-0 ps-3">
                                                @forelse ($det['surtidos'] as $s)
                                                    <li>
                                                        <span class="text-muted">
                                                            {{ $s['cadena'] }} — {{ $s['sucursal'] }}:
                                                        </span>
                                                        <strong>{{ $s['cantidad'] }} pzs</strong>
                                                    </li>
                                                @empty
                                                    <li><em class="text-muted">Sin líneas de surtido registradas</em></li>
                                                @endforelse
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            {{ $det['cantidadTotal'] }}
                                        </td>
                                        <td class="text-end">
                                            ${{ number_format($det['subtotal'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            No hay detalles de receta registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                   {{-- Totales --}}
                <div class="card-footer text-end">
                    <p class="mb-1">
                        <strong>Total general:</strong>
                        ${{ number_format($totalGeneral, 2) }}
                    </p>
                    <p class="mb-1">
                        <strong>Comisión (15%):</strong>
                        ${{ number_format($comision, 2) }}
                    </p>
                    <p class="mb-0">
                        <strong>Total con comisión:</strong>
                        ${{ number_format($totalConComision, 2) }}
                    </p>
                </div>
            </div>

        </div>

        {{-- Columna derecha: mapa --}}
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-header">
                    Mapa de sucursales involucradas
                </div>
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pasar segmentos al JS --}}
<script>
    window.segmentosMapa = @json($segmentos);
</script>

{{-- Leaflet CSS + tu CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="{{ asset('css/rutas_mapa.css') }}">

{{-- Leaflet JS + tu JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="">
</script>
<script src="{{ asset('js/rutas_mapa.js') }}"></script>
@endsection