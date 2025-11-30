<div class="card shadow-lg border-0 w-100 mapa-farmacias-card">
    <div class="card-header text-white text-center mapa-farmacias-header">
        <h4 class="mb-0">
            <i class="fas fa-map-marker-alt me-2"></i>
            Selecciona la farmacia de tu preferencia
        </h4>
    </div>

    <div class="px-4 pt-3">
        <label for="filtro_cadena" class="form-label fw-bold">
            <i class="fas fa-filter me-2"></i>Filtrar por cadena
        </label>
        <select id="filtro_cadena" class="form-select">
            <option value="">Todas las cadenas</option>
        </select>
    </div>

    <div class="card-body p-4">
        <div id="map" class="map-container"></div>
    </div>

    <div class="card-footer text-center mapa-farmacias-footer">
        <div class="footer-content">
            <p class="mb-1 footer-instruction">
                <i class="fas fa-hand-pointer me-2"></i>
                Haz clic en una farmacia para seleccionarla
            </p>
        </div>
    </div>

    <div class="card-footer text-center formulario-receta-footer">
        <span class="step-badge">
            <i class="fas fa-check-circle me-2"></i>Paso 1 de 3
        </span>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('css/mapa-farmacias-styles.css') }}" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/mapa-farmacias.js') }}"></script>
@endpush