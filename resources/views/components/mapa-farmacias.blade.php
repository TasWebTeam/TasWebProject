{{-- resources/views/components/mapa-farmacias.blade.php --}}

<div class="card shadow-lg border-0 w-100 mapa-farmacias-card">
    <div class="card-header text-white text-center mapa-farmacias-header">
        <h4 class="mb-0">
            <i class="fas fa-map-marker-alt me-2"></i>
            Selecciona la farmacia de tu preferencia
        </h4>
    </div>

    <div class="card-body p-4">
        <div id="map" class="map-container"></div>
    </div>

    <div class="card-footer text-center mapa-farmacias-footer">
        <div class="footer-content">
            <p class="mb-1 footer-instruction">
                <i class="fas fa-hand-pointer me-2"></i>Haz clic en una farmacia para seleccionarla
            </p>
            <small class="text-muted footer-attribution">
                <i class="fas fa-map me-1"></i>Fuente del mapa: OpenStreetMap (Leaflet.js)
            </small>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('css/mapa-farmacias-styles.css') }}" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://kit.fontawesome.com/a2d9b2dfb1.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/mapa-farmacias.js') }}"></script>
@endpush