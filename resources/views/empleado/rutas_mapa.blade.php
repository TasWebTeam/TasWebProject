@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h3>Mapa de líneas de surtido</h3>

    <div id="map"></div>
</div>

{{-- 1) Leaflet CSS --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
/>

{{-- 2) Tu CSS del mapa --}}
<link rel="stylesheet" href="{{ asset('css/rutas_mapa.css') }}">

{{-- 3) Pasar segmentos del backend al frontend --}}
<script>
    window.segmentosMapa = @json($segmentos);
</script>

{{-- 4) Leaflet JS --}}
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin="">
</script>

{{-- 5) Tu JS del mapa --}}
<script src="{{ asset('js/rutas_mapa.js') }}"></script>
@endsection