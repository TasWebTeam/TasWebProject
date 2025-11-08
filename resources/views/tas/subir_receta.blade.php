@extends('layouts.template')

@section('title', 'Farmacias disponibles')

@section('content')
<link rel="stylesheet" href="{{ asset('css/receta-styles.css') }}">

<div class="container mt-5 pt-4 d-flex justify-content-center">
    <div class="card shadow-lg border-0 w-100" style="max-width: 900px; border-radius: 15px;">
        <div class="card-header text-white text-center bg-tas rounded-top">
            <h4 class="mb-0">
                <i class="fas fa-map-marker-alt me-2"></i>
                Selecciona la farmacia de tu preferencia
            </h4>
        </div>

        <div class="card-body p-4">
            <div id="map" class="map-container"></div>
        </div>

        <div class="card-footer text-center bg-light">
            <p class="mb-1">Haz clic en una farmacia para seleccionarla</p>
            <small class="text-muted">Fuente del mapa: OpenStreetMap (Leaflet.js)</small>
        </div>
    </div>
</div>

{{-- Librerías necesarias --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://kit.fontawesome.com/a2d9b2dfb1.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const map = L.map('map').setView([24.8091, -107.3940], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 📍 Farmacias disponibles
    const farmacias = [
        { nombre: "Farmacia Central", lat: 24.8091, lng: -107.3940 },
        { nombre: "Farmacia Norte", lat: 24.8150, lng: -107.3850 },
        { nombre: "Farmacia Sur", lat: 24.8020, lng: -107.4000 },
    ];

    // 🧭 Añadir marcadores
    farmacias.forEach(f => {
        const marker = L.marker([f.lat, f.lng]).addTo(map);
        marker.bindPopup(`
            <div style="text-align:center;">
                <strong>${f.nombre}</strong><br>
                <button class='btn btn-sm btn-tas-outline mt-2' onclick="seleccionarFarmacia('${f.nombre}')">
                    <i class="fas fa-check-circle me-1"></i>Seleccionar
                </button>
            </div>
        `);
    });

    // 🚀 Al seleccionar una farmacia
    function seleccionarFarmacia(nombre) {
        Swal.fire({
            title: 'Farmacia seleccionada',
            text: `Has elegido: ${nombre}`,
            icon: 'success',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#005B96'
        });
    }
</script>
@endsection
